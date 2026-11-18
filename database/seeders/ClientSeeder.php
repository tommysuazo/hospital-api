<?php

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    private const EMAIL = 'tommysuazoxd@gmail.com';

    public function run(): void
    {
        $client = Client::withTrashed()
            ->where('email', self::EMAIL)
            ->first();

        $data = [
            'first_name' => 'Tommy',
            'last_name' => 'Suazo',
            'gender' => 'male',
            'phone' => '8096531356',
            'email' => self::EMAIL,
            'password' => 'password',
            'is_active' => true,
            'email_verified_at' => now(),
        ];

        if ($client === null) {
            Client::factory()->create($data);

            return;
        }

        $client->forceFill([
            ...$data,
            'deleted_at' => null,
        ])->save();
    }
}
