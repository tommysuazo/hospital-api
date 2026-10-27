<?php

namespace Tests\Feature;

use App\Models\MedicalSpecialty;
use App\Models\MedicalSpecialtyTranslation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MedicalSpecialtyApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_anyone_can_list_active_medical_specialties(): void
    {
        $pediatrics = MedicalSpecialty::factory()->create([
            'slug' => 'pediatrics',
        ]);
        MedicalSpecialtyTranslation::factory()
            ->for($pediatrics, 'medicalSpecialty')
            ->create([
                'locale' => 'es',
                'name' => 'Pediatría',
            ]);
        MedicalSpecialtyTranslation::factory()
            ->for($pediatrics, 'medicalSpecialty')
            ->create([
                'locale' => 'en',
                'name' => 'Pediatrics',
            ]);

        $cardiology = MedicalSpecialty::factory()->create([
            'slug' => 'cardiology',
        ]);
        MedicalSpecialtyTranslation::factory()
            ->for($cardiology, 'medicalSpecialty')
            ->create([
                'locale' => 'en',
                'name' => 'Cardiology',
            ]);
        MedicalSpecialtyTranslation::factory()
            ->for($cardiology, 'medicalSpecialty')
            ->create([
                'locale' => 'es',
                'name' => 'Cardiología',
            ]);

        $inactiveSpecialty = MedicalSpecialty::factory()->create([
            'slug' => 'inactive-specialty',
            'is_active' => false,
        ]);
        MedicalSpecialtyTranslation::factory()
            ->for($inactiveSpecialty, 'medicalSpecialty')
            ->create([
                'locale' => 'es',
                'name' => 'Especialidad inactiva',
            ]);

        $response = $this->getJson('/api/medical-specialties');

        $response
            ->assertOk()
            ->assertExactJson([
                'data' => [
                    [
                        'id' => $cardiology->id,
                        'slug' => 'cardiology',
                        'name' => 'Cardiology',
                    ],
                    [
                        'id' => $pediatrics->id,
                        'slug' => 'pediatrics',
                        'name' => 'Pediatrics',
                    ],
                ],
            ]);
    }
}
