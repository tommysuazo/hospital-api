<?php

namespace Database\Factories;

use App\Enums\UserDocumentTypeEnum;
use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<Client>
 */
class ClientFactory extends Factory
{
    public function definition(): array
    {
        $documentType = fake()->randomElement(UserDocumentTypeEnum::cases());

        $documentNumber = match ($documentType) {
            UserDocumentTypeEnum::IdCard => fake()->unique()->numerify('###########'),
            UserDocumentTypeEnum::Passport => strtoupper(
                fake()->unique()->bothify('??#######')
            ),
        };

        return [
            'uuid' => (string) Str::uuid(),
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),

            'document_type' => $documentType->value,
            'document_number' => $documentNumber,

            'birth_date' => fake()->dateTimeBetween('-80 years', '-18 years')
                ->format('Y-m-d'),

            'gender' => fake()->randomElement([
                'male',
                'female',
            ]),

            'phone' => fake()->numerify('809#######'),

            'email' => fake()->unique()->safeEmail(),

            'password' => Hash::make('password'),

            'is_active' => true,

            'email_verified_at' => null,
            'last_login_at' => null,

            'remember_token' => Str::random(10),
        ];
    }
}
