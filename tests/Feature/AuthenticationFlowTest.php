<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\User;
use App\Notifications\AuthVerificationCodeNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class AuthenticationFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_visitor_can_register_only_as_a_client_and_verify_the_email_code(): void
    {
        Notification::fake();

        $response = $this->post('/register', [
            'first_name' => 'Ana',
            'last_name' => 'Rodríguez',
            'document_type' => 'id_card',
            'document_number' => '00112345678',
            'birth_date' => '1994-05-10',
            'gender' => 'female',
            'phone' => '8095550101',
            'email' => 'ANA@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'terms' => true,
        ]);

        $client = Client::query()->where('email', 'ana@example.com')->firstOrFail();
        $response->assertRedirect(route('public.auth.verify.show'))
            ->assertSessionHas('auth.challenge_uuid');
        $this->assertDatabaseCount('users', 0);
        $this->assertNull($client->email_verified_at);

        $code = $this->verificationCodeSentTo($client);

        $this->post('/auth/verify-code', ['code' => $code])
            ->assertRedirect(route('public.home'));

        $this->assertAuthenticatedAs($client->fresh(), 'client');
        $this->assertNotNull($client->fresh()->email_verified_at);
    }

    public function test_client_login_requires_a_valid_password_and_then_a_verification_code(): void
    {
        Notification::fake();
        $client = Client::factory()->create([
            'email' => 'patient@example.com',
            'password' => 'Password123!',
            'email_verified_at' => now(),
        ]);

        $this->post('/login', [
            'access_type' => 'client',
            'email' => $client->email,
            'password' => 'incorrect-password',
            'remember' => false,
        ])->assertSessionHasErrors('auth');

        $this->post('/login', [
            'access_type' => 'client',
            'email' => $client->email,
            'password' => 'Password123!',
            'remember' => true,
        ])->assertRedirect(route('public.auth.verify.show'));

        $this->assertGuest('client');
        $this->post('/auth/verify-code', ['code' => $this->verificationCodeSentTo($client)])
            ->assertRedirect(route('public.home'));
        $this->assertAuthenticatedAs($client, 'client');
    }

    public function test_staff_can_login_but_cannot_use_the_public_password_recovery_flow(): void
    {
        Notification::fake();
        $staff = User::factory()->create([
            'email' => 'staff@example.com',
            'password' => 'Password123!',
            'is_active' => true,
        ]);

        $this->post('/login', [
            'access_type' => 'staff',
            'email' => $staff->email,
            'password' => 'Password123!',
            'remember' => false,
        ])->assertRedirect(route('public.auth.verify.show'));

        $this->post('/auth/verify-code', ['code' => $this->verificationCodeSentTo($staff)])
            ->assertRedirect(route('public.home'));
        $this->assertAuthenticatedAs($staff, 'staff');

        $this->post('/password/forgot', ['email' => $staff->email])
            ->assertRedirect(route('public.auth.verify.show'));
        Notification::assertSentToTimes($staff, AuthVerificationCodeNotification::class, 1);
    }

    public function test_a_verified_client_can_reset_the_password_with_a_code(): void
    {
        Notification::fake();
        $client = Client::factory()->create([
            'email' => 'patient@example.com',
            'password' => 'OldPassword123!',
            'email_verified_at' => now(),
        ]);

        $this->post('/password/forgot', ['email' => $client->email])
            ->assertRedirect(route('public.auth.verify.show'));

        $this->post('/auth/verify-code', ['code' => $this->verificationCodeSentTo($client)])
            ->assertRedirect(route('public.password.reset.show'));

        $this->post('/password/reset', [
            'password' => 'NewPassword123!',
            'password_confirmation' => 'NewPassword123!',
        ])->assertRedirect(route('public.login'));

        $this->assertTrue(Hash::check('NewPassword123!', $client->fresh()->password));
    }

    public function test_password_recovery_does_not_reveal_if_a_client_exists(): void
    {
        Notification::fake();

        $this->post('/password/forgot', ['email' => 'missing@example.com'])
            ->assertRedirect(route('public.auth.verify.show'))
            ->assertSessionHas('auth.challenge_fake', true);

        $this->post('/auth/verify-code', ['code' => '123456'])
            ->assertSessionHasErrors('code');
        Notification::assertNothingSent();
    }

    private function verificationCodeSentTo(Client|User $account): string
    {
        $code = null;

        Notification::assertSentTo(
            $account,
            AuthVerificationCodeNotification::class,
            function (AuthVerificationCodeNotification $notification) use (&$code): bool {
                $code = $notification->code;

                return true;
            },
        );

        $this->assertIsString($code);

        return $code;
    }
}
