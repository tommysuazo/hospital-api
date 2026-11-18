<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use InvalidArgumentException;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $data = config('seeding.super_admin');

        $this->ensureRequiredData($data);

        if (! Role::query()
            ->where('name', 'super-admin')
            ->where('guard_name', 'web')
            ->exists()) {
            $this->call(RoleAndPermissionSeeder::class);
        }

        $user = User::withTrashed()
            ->where('email', $data['email'])
            ->first();

        if ($user === null) {
            $user = User::factory()->create([
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'phone' => $data['phone'],
                'email' => $data['email'],
                'password' => $data['password'],
            ]);
        } else {
            $user->forceFill([
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'phone' => $data['phone'],
                'password' => $data['password'],
                'is_active' => true,
                'email_verified_at' => $user->email_verified_at ?? now(),
                'deleted_at' => null,
            ])->save();
        }

        $user->syncRoles(['super-admin']);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private function ensureRequiredData(array $data): void
    {
        foreach (['first_name', 'last_name', 'phone', 'email', 'password'] as $key) {
            if (! is_string($data[$key] ?? null) || trim($data[$key]) === '') {
                throw new InvalidArgumentException("Missing required seeding configuration: seeding.super_admin.{$key}");
            }
        }
    }
}
