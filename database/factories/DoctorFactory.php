<?php

namespace Database\Factories;

use App\Enums\DayOfWeekEnum;
use App\Models\Doctor;
use App\Models\DoctorSchedule;
use App\Models\DoctorTranslation;
use App\Models\DoctorUnavailability;
use App\Models\MedicalArea;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Doctor>
 */
class DoctorFactory extends Factory
{
    private const MALE_PICTURES = [
        '/images/doctors/doctor-01.jpg',
        '/images/doctors/doctor-02.jpg',
    ];

    private const FEMALE_PICTURES = [
        '/images/doctors/doctor-03.jpg',
        '/images/doctors/doctor-04.jpg',
    ];

    public function configure(): static
    {
        return $this->afterMaking(function (Doctor $doctor): void {
            if ($doctor->picture !== null) {
                return;
            }

            $pictures = match ($doctor->user?->gender) {
                'male' => self::MALE_PICTURES,
                'female' => self::FEMALE_PICTURES,
                default => [...self::MALE_PICTURES, ...self::FEMALE_PICTURES],
            };

            $doctor->picture = fake()->randomElement($pictures);
        });
    }

    public function definition(): array
    {
        return [
            'uuid' => (string) Str::uuid(),
            'user_id' => null,
            'picture' => null,
            'consulting_room' => fake()->bothify('##'),
            'phone_extension' => fake()->numerify('####'),
            'is_active' => true,
        ];
    }

    public function withDirectoryRelations(): static
    {
        return $this
            ->state(fn (): array => [
                'user_id' => User::factory(),
            ])
            ->afterCreating(function (Doctor $doctor): void {
                foreach (['es', 'en'] as $locale) {
                    DoctorTranslation::factory()
                        ->for($doctor)
                        ->create([
                            'locale' => $locale,
                            'professional_summary' => fake(
                                $locale === 'es' ? 'es_ES' : 'en_US'
                            )->paragraphs(2, true),
                        ]);
                }

                $medicalAreas = MedicalArea::query()
                    ->where('is_active', true)
                    ->inRandomOrder()
                    ->limit(fake()->numberBetween(1, 2))
                    ->get();

                $doctor->medicalAreas()->sync($medicalAreas->modelKeys());

                $availableDays = fake()->randomElements(
                    array_column(DayOfWeekEnum::cases(), 'value'),
                    fake()->numberBetween(2, 4)
                );

                foreach ($availableDays as $dayOfWeek) {
                    DoctorSchedule::factory()
                        ->for($doctor)
                        ->create(['day_of_week' => $dayOfWeek]);
                }

                DoctorUnavailability::factory()
                    ->for($doctor)
                    ->create();
            });
    }
}
