<?php

namespace Tests\Feature;

use App\Models\MedicalArea;
use App\Models\MedicalAreaTranslation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MedicalAreaApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_anyone_can_list_active_medical_areas(): void
    {
        $pediatrics = MedicalArea::factory()->create([
            'slug' => 'pediatrics',
        ]);
        MedicalAreaTranslation::factory()
            ->for($pediatrics, 'medicalArea')
            ->create([
                'locale' => 'es',
                'name' => 'Pediatría',
            ]);
        MedicalAreaTranslation::factory()
            ->for($pediatrics, 'medicalArea')
            ->create([
                'locale' => 'en',
                'name' => 'Pediatrics',
            ]);

        $cardiology = MedicalArea::factory()->create([
            'slug' => 'cardiology',
        ]);
        MedicalAreaTranslation::factory()
            ->for($cardiology, 'medicalArea')
            ->create([
                'locale' => 'en',
                'name' => 'Cardiology',
            ]);
        MedicalAreaTranslation::factory()
            ->for($cardiology, 'medicalArea')
            ->create([
                'locale' => 'es',
                'name' => 'Cardiología',
            ]);

        $inactiveMedicalArea = MedicalArea::factory()->create([
            'slug' => 'inactive-medical-area',
            'is_active' => false,
        ]);
        MedicalAreaTranslation::factory()
            ->for($inactiveMedicalArea, 'medicalArea')
            ->create([
                'locale' => 'es',
                'name' => 'Área médica inactiva',
            ]);

        $response = $this->getJson('/api/medical-areas');

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
