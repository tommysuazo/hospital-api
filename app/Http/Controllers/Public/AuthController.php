<?php

namespace App\Http\Controllers\Public;

use App\Exceptions\KnownException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Http\Requests\Auth\VerifyAuthCodeRequest;
use App\Models\AuthVerificationCode;
use App\Services\AuthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;
use Throwable;

class AuthController extends Controller
{
    public function __construct(private readonly AuthService $authService) {}

    public function showLogin(Request $request): Response
    {
        return Inertia::render('Public/Auth/Portal', [
            'mode' => 'login',
            'status' => $request->session()->get('status'),
        ]);
    }

    public function showRegister(): Response
    {
        return Inertia::render('Public/Auth/Portal', ['mode' => 'register']);
    }

    public function register(RegisterRequest $request): RedirectResponse
    {
        try {
            $data = $request->validated();
            $challenge = $this->authService->registerClient($data, $request->ip(), $request->userAgent());
            $this->storeChallenge($request, $challenge, $data['email']);

            return to_route('public.auth.verify.show');
        } catch (KnownException $exception) {
            return back()->withErrors(['auth' => $exception->getMessage()])->withInput();
        } catch (Throwable $exception) {
            report($exception);

            return back()->withErrors(['auth' => 'No pudimos crear tu cuenta en este momento. Inténtalo nuevamente.'])->withInput();
        }
    }

    public function login(LoginRequest $request): RedirectResponse
    {
        try {
            $data = $request->validated();
            $challenge = $this->authService->startLogin($data, $request->ip(), $request->userAgent());

            if ($challenge === null) {
                $request->session()->forget(['auth.challenge_uuid', 'auth.masked_email', 'auth.challenge_purpose', 'auth.challenge_fake']);
                $request->session()->regenerate();

                return to_route('client.dashboard')->with('status', 'La sesión se inició correctamente.');
            }

            $this->storeChallenge($request, $challenge, $data['email']);

            return to_route('public.auth.verify.show');
        } catch (KnownException $exception) {
            return back()->withErrors(['auth' => $exception->getMessage()])->withInput();
        } catch (Throwable $exception) {
            report($exception);

            return back()->withErrors(['auth' => 'No pudimos iniciar el proceso de acceso. Inténtalo nuevamente.'])->withInput();
        }
    }

    public function showVerification(Request $request): Response|RedirectResponse
    {
        if (! $request->session()->has('auth.challenge_uuid')) {
            return to_route('public.login');
        }

        return Inertia::render('Public/Auth/VerifyCode', [
            'maskedEmail' => $request->session()->get('auth.masked_email'),
            'purpose' => $request->session()->get('auth.challenge_purpose'),
            'status' => $request->session()->get('status'),
        ]);
    }

    public function verifyCode(VerifyAuthCodeRequest $request): RedirectResponse
    {
        $uuid = $request->session()->get('auth.challenge_uuid');

        if (! $uuid || $request->session()->get('auth.challenge_fake', false)) {
            return back()->withErrors(['code' => 'El código ingresado no es válido.']);
        }

        try {
            $challenge = $this->authService->verifyCode($uuid, $request->validated('code'));

            if ($challenge->purpose === AuthService::PURPOSE_PASSWORD_RESET) {
                $request->session()->put('auth.password_reset_uuid', $challenge->uuid);
                $request->session()->forget(['auth.challenge_uuid', 'auth.masked_email', 'auth.challenge_purpose', 'auth.challenge_fake']);

                return to_route('public.password.reset.show');
            }

            $request->session()->forget(['auth.challenge_uuid', 'auth.masked_email', 'auth.challenge_purpose', 'auth.challenge_fake']);
            $request->session()->regenerate();

            $destination = $challenge->account_type === AuthService::ACCOUNT_CLIENT
                ? 'client.dashboard'
                : 'public.home';

            return to_route($destination)->with('status', 'Tu correo fue validado y la sesión se inició correctamente.');
        } catch (KnownException $exception) {
            return back()->withErrors(['code' => $exception->getMessage()]);
        } catch (Throwable $exception) {
            report($exception);

            return back()->withErrors(['code' => 'No pudimos validar el código en este momento.']);
        }
    }

    public function resendCode(Request $request): RedirectResponse
    {
        $uuid = $request->session()->get('auth.challenge_uuid');

        if (! $uuid) {
            return to_route('public.login');
        }

        if ($request->session()->get('auth.challenge_fake', false)) {
            return back()->with('status', 'Si la cuenta existe, enviamos un código nuevo.');
        }

        try {
            $challenge = $this->authService->resendCode($uuid, $request->ip(), $request->userAgent());
            $request->session()->put('auth.challenge_uuid', $challenge->uuid);

            return back()->with('status', 'Enviamos un código nuevo a tu correo.');
        } catch (KnownException $exception) {
            return back()->withErrors(['code' => $exception->getMessage()]);
        } catch (Throwable $exception) {
            report($exception);

            return back()->withErrors(['code' => 'No pudimos reenviar el código en este momento.']);
        }
    }

    public function showForgotPassword(Request $request): Response
    {
        return Inertia::render('Public/Auth/ForgotPassword', [
            'status' => $request->session()->get('status'),
        ]);
    }

    public function sendPasswordResetCode(ForgotPasswordRequest $request): RedirectResponse
    {
        try {
            $email = $request->validated('email');
            $challenge = $this->authService->requestClientPasswordReset($email, $request->ip(), $request->userAgent());

            if ($challenge) {
                $this->storeChallenge($request, $challenge, $email);
            } else {
                $request->session()->put([
                    'auth.challenge_uuid' => (string) Str::uuid(),
                    'auth.masked_email' => $this->maskEmail($email),
                    'auth.challenge_purpose' => AuthService::PURPOSE_PASSWORD_RESET,
                    'auth.challenge_fake' => true,
                ]);
            }

            return to_route('public.auth.verify.show')
                ->with('status', 'Si existe una cuenta activa con ese correo, enviamos un código de recuperación.');
        } catch (Throwable $exception) {
            report($exception);

            return back()->withErrors(['email' => 'No pudimos procesar la solicitud en este momento.'])->withInput();
        }
    }

    public function showResetPassword(Request $request): Response|RedirectResponse
    {
        if (! $request->session()->has('auth.password_reset_uuid')) {
            return to_route('public.password.forgot.show');
        }

        return Inertia::render('Public/Auth/ResetPassword');
    }

    public function resetPassword(ResetPasswordRequest $request): RedirectResponse
    {
        $uuid = $request->session()->get('auth.password_reset_uuid');

        if (! $uuid) {
            return to_route('public.password.forgot.show');
        }

        try {
            $this->authService->resetClientPassword($uuid, $request->validated('password'));
            $request->session()->forget('auth.password_reset_uuid');

            return to_route('public.login')->with('status', 'Tu contraseña fue actualizada. Ya puedes iniciar sesión.');
        } catch (KnownException $exception) {
            return back()->withErrors(['password' => $exception->getMessage()]);
        } catch (Throwable $exception) {
            report($exception);

            return back()->withErrors(['password' => 'No pudimos actualizar la contraseña en este momento.']);
        }
    }

    public function logout(Request $request): RedirectResponse
    {
        $this->authService->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return to_route('public.login')->with('status', 'Tu sesión se cerró correctamente.');
    }

    private function storeChallenge(Request $request, AuthVerificationCode $challenge, string $email): void
    {
        $request->session()->put([
            'auth.challenge_uuid' => $challenge->uuid,
            'auth.masked_email' => $this->maskEmail($email),
            'auth.challenge_purpose' => $challenge->purpose,
            'auth.challenge_fake' => false,
        ]);
    }

    private function maskEmail(string $email): string
    {
        [$local, $domain] = array_pad(explode('@', $email, 2), 2, '');
        $visible = mb_substr($local, 0, min(2, mb_strlen($local)));

        return $visible.str_repeat('•', max(3, mb_strlen($local) - 2)).'@'.$domain;
    }
}
