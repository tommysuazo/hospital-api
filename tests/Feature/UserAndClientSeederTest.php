<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\User;
use Database\Seeders\ClientSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class UserAndClientSeederTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'seeding.super_admin' => [
                'first_name' => 'Super',
                'last_name' => 'Admin',
                'phone' => '8095564979',
                'email' => 'tommysuazoxd@gmail.com',
                'password' => 'password',
            ],
        ]);
    }

    public function test_user_seeder_creates_an_active_super_admin(): void
    {
        $this->seed(UserSeeder::class);

        $user = User::query()
            ->where('email', 'tommysuazoxd@gmail.com')
            ->firstOrFail();

        $this->assertSame('Super', $user->first_name);
        $this->assertSame('Admin', $user->last_name);
        $this->assertSame('8095564979', $user->phone);
        $this->assertNotEmpty($user->uuid);
        $this->assertContains($user->gender, ['male', 'female']);
        $this->assertTrue($user->is_active);
        $this->assertTrue(Hash::check('password', $user->password));
        $this->assertTrue($user->hasRole('super-admin'));
    }

    public function test_only_clients_have_an_email_verification_column(): void
    {
        $this->assertFalse(Schema::hasColumn('users', 'email_verified_at'));
        $this->assertTrue(Schema::hasColumn('clients', 'email_verified_at'));
    }

    public function test_client_seeder_creates_a_client_ready_to_log_in(): void
    {
        $this->seed(ClientSeeder::class);

        $client = Client::query()
            ->where('email', 'tommysuazoxd@gmail.com')
            ->firstOrFail();

        $this->assertSame('Tommy', $client->first_name);
        $this->assertSame('Suazo', $client->last_name);
        $this->assertSame('male', $client->gender);
        $this->assertSame('8096531356', $client->phone);
        $this->assertNotEmpty($client->uuid);
        $this->assertNotEmpty($client->document_type);
        $this->assertNotEmpty($client->document_number);
        $this->assertNotNull($client->birth_date);
        $this->assertTrue($client->is_active);
        $this->assertNotNull($client->email_verified_at);
        $this->assertTrue(Hash::check('password', $client->password));
        $this->assertTrue(Auth::guard('client')->attempt([
            'email' => 'tommysuazoxd@gmail.com',
            'password' => 'password',
        ]));
    }

    public function test_seeders_are_idempotent(): void
    {
        $this->seed(UserSeeder::class);
        $this->seed(ClientSeeder::class);
        $this->seed(UserSeeder::class);
        $this->seed(ClientSeeder::class);

        $this->assertSame(1, User::query()->where('email', 'tommysuazoxd@gmail.com')->count());
        $this->assertSame(1, Client::query()->where('email', 'tommysuazoxd@gmail.com')->count());
    }
}
