<?php

namespace App\Services;

use App\Exceptions\KnownException;
use App\Models\AuthVerificationCode;
use App\Models\Client;
use App\Models\User;
use App\Notifications\AuthVerificationCodeNotification;
use App\Repositories\AuthVerificationCodeRepository;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthService
{
    public const ACCOUNT_CLIENT = 'client';

    public const ACCOUNT_STAFF = 'staff';

    public const PURPOSE_REGISTRATION = 'registration';

    public const PURPOSE_LOGIN = 'login';

    public const PURPOSE_PASSWORD_RESET = 'password_reset';

    private const MAX_ATTEMPTS = 5;

    private const CODE_EXPIRATION_MINUTES = 30;

    private const RESEND_DELAY_SECONDS = 60;

    public function __construct(
        private readonly ClientService $clientService,
        private readonly UserService $userService,
        private readonly AuthVerificationCodeRepository $verificationCodeRepository,
    ) {}

    public function registerClient(array $data, ?string $ipAddress, ?string $userAgent): AuthVerificationCode
    {
        [$client, $challenge, $code] = DB::transaction(function () use ($data, $ipAddress, $userAgent): array {
            $client = $this->clientService->create($data);
            [$challenge, $code] = $this->createChallenge(
                $client,
                self::ACCOUNT_CLIENT,
                self::PURPOSE_REGISTRATION,
                false,
                $ipAddress,
                $userAgent,
            );

            return [$client, $challenge, $code];
        });

        $this->sendCode($client, $code, self::PURPOSE_REGISTRATION);

        return $challenge;
    }

    public function startLogin(array $data, ?string $ipAddress, ?string $userAgent): AuthVerificationCode
    {
        $accountType = $data['access_type'];
        $account = $accountType === self::ACCOUNT_STAFF
            ? $this->userService->findActiveByEmail($data['email'])
            : $this->clientService->findActiveByEmail($data['email']);

        if (! $account || ! Hash::check($data['password'], $account->password)) {
            throw new KnownException('Las credenciales proporcionadas no son válidas.');
        }

        [$challenge, $code] = $this->createChallenge(
            $account,
            $accountType,
            self::PURPOSE_LOGIN,
            (bool) ($data['remember'] ?? false),
            $ipAddress,
            $userAgent,
        );

        $this->sendCode($account, $code, self::PURPOSE_LOGIN);

        return $challenge;
    }

    public function verifyCode(string $challengeUuid, string $code): AuthVerificationCode
    {
        $challenge = $this->pendingChallenge($challengeUuid);

        if ($challenge->attempts >= self::MAX_ATTEMPTS) {
            throw new KnownException('Superaste el número máximo de intentos. Solicita un código nuevo.');
        }

        if (! Hash::check($code, $challenge->code_hash)) {
            $this->verificationCodeRepository->incrementAttempts($challenge);
            throw new KnownException('El código ingresado no es válido.');
        }

        $this->verificationCodeRepository->markVerified($challenge);

        if ($challenge->purpose === self::PURPOSE_PASSWORD_RESET) {
            return $challenge;
        }

        $account = $this->resolveAccount($challenge);
        $guard = $challenge->account_type === self::ACCOUNT_STAFF ? 'staff' : 'client';

        if ($account instanceof Client) {
            $this->clientService->markEmailAsVerified($account);
            $this->clientService->updateLastLogin($account);
        } else {
            $this->userService->markEmailAsVerified($account);
            $this->userService->updateLastLogin($account);
        }

        Auth::guard($guard)->login($account, $challenge->remember);
        $this->verificationCodeRepository->consume($challenge);

        return $challenge;
    }

    public function resendCode(string $challengeUuid, ?string $ipAddress, ?string $userAgent): AuthVerificationCode
    {
        $previous = $this->pendingChallenge($challengeUuid, checkExpiration: false);

        if ($previous->resend_available_at->isFuture()) {
            throw new KnownException('Espera un minuto antes de solicitar otro código.');
        }

        $account = $this->resolveAccount($previous);
        [$challenge, $code] = $this->createChallenge(
            $account,
            $previous->account_type,
            $previous->purpose,
            $previous->remember,
            $ipAddress,
            $userAgent,
        );

        $this->sendCode($account, $code, $previous->purpose);

        return $challenge;
    }

    public function requestClientPasswordReset(string $email, ?string $ipAddress, ?string $userAgent): ?AuthVerificationCode
    {
        $client = $this->clientService->findActiveByEmail($email);

        if (! $client || $client->email_verified_at === null) {
            return null;
        }

        [$challenge, $code] = $this->createChallenge(
            $client,
            self::ACCOUNT_CLIENT,
            self::PURPOSE_PASSWORD_RESET,
            false,
            $ipAddress,
            $userAgent,
        );

        $this->sendCode($client, $code, self::PURPOSE_PASSWORD_RESET);

        return $challenge;
    }

    public function resetClientPassword(string $challengeUuid, string $password): void
    {
        $challenge = $this->verificationCodeRepository->findByUuid($challengeUuid);

        if (! $challenge
            || $challenge->account_type !== self::ACCOUNT_CLIENT
            || $challenge->purpose !== self::PURPOSE_PASSWORD_RESET
            || $challenge->verified_at === null
            || $challenge->consumed_at !== null
            || $challenge->expires_at->isPast()) {
            throw new KnownException('La autorización para restablecer la contraseña venció.');
        }

        $client = $this->resolveAccount($challenge);

        DB::transaction(function () use ($client, $password, $challenge): void {
            $this->clientService->resetPassword($client, $password);
            $this->verificationCodeRepository->consume($challenge);
        });
    }

    public function logout(): void
    {
        foreach (['client', 'staff'] as $guard) {
            if (Auth::guard($guard)->check()) {
                Auth::guard($guard)->logout();
            }
        }
    }

    private function createChallenge(
        Authenticatable $account,
        string $accountType,
        string $purpose,
        bool $remember,
        ?string $ipAddress,
        ?string $userAgent,
    ): array {
        $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $this->verificationCodeRepository->invalidateActive($accountType, (int) $account->getAuthIdentifier(), $purpose);

        $challenge = $this->verificationCodeRepository->create([
            'uuid' => (string) Str::uuid(),
            'account_type' => $accountType,
            'account_id' => (int) $account->getAuthIdentifier(),
            'purpose' => $purpose,
            'code_hash' => Hash::make($code),
            'remember' => $remember,
            'expires_at' => now()->addMinutes(self::CODE_EXPIRATION_MINUTES),
            'resend_available_at' => now()->addSeconds(self::RESEND_DELAY_SECONDS),
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent ? mb_substr($userAgent, 0, 500) : null,
        ]);

        return [$challenge, $code];
    }

    private function pendingChallenge(string $uuid, bool $checkExpiration = true): AuthVerificationCode
    {
        $challenge = $this->verificationCodeRepository->findByUuid($uuid);

        if (! $challenge || $challenge->consumed_at !== null) {
            throw new KnownException('Esta solicitud ya no está disponible.');
        }

        if ($checkExpiration && $challenge->expires_at->isPast()) {
            throw new KnownException('El código venció. Solicita uno nuevo.');
        }

        return $challenge;
    }

    private function resolveAccount(AuthVerificationCode $challenge): Client|User
    {
        $account = $challenge->account_type === self::ACCOUNT_STAFF
            ? $this->userService->findActiveById($challenge->account_id)
            : $this->clientService->findActiveById($challenge->account_id);

        if (! $account) {
            throw new KnownException('La cuenta no está disponible.');
        }

        return $account;
    }

    private function sendCode(Client|User $account, string $code, string $purpose): void
    {
        $account->notify(new AuthVerificationCodeNotification(
            $code,
            $purpose,
            self::CODE_EXPIRATION_MINUTES,
        ));
    }
}
