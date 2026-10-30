<?php

namespace Database\Seeders;

use App\Models\MedicalArea;
use App\Models\MedicalAreaTranslation;
use Illuminate\Database\Seeder;

class MedicalAreaSeeder extends Seeder
{
    public function run(): void
    {
        $medicalAreas = [
            'general-medicine' => [
                'es' => 'Medicina General',
                'en' => 'General Medicine',
            ],
            'pediatrics' => [
                'es' => 'Pediatría',
                'en' => 'Pediatrics',
            ],
            'gynecology' => [
                'es' => 'Ginecología',
                'en' => 'Gynecology',
            ],
            'obstetrics' => [
                'es' => 'Obstetricia',
                'en' => 'Obstetrics',
            ],
            'cardiology' => [
                'es' => 'Cardiología',
                'en' => 'Cardiology',
            ],
            'psychology' => [
                'es' => 'Psicología',
                'en' => 'Psychology',
            ],
            'urology' => [
                'es' => 'Urología',
                'en' => 'Urology',
            ],
            'dermatology' => [
                'es' => 'Dermatología',
                'en' => 'Dermatology',
            ],
            'gastroenterology' => [
                'es' => 'Gastroenterología',
                'en' => 'Gastroenterology',
            ],
            'hepatology' => [
                'es' => 'Hepatología',
                'en' => 'Hepatology',
            ],
            'endocrinology' => [
                'es' => 'Endocrinología',
                'en' => 'Endocrinology',
            ],
            'geriatrics' => [
                'es' => 'Geriatría',
                'en' => 'Geriatrics',
            ],
        ];

        foreach ($medicalAreas as $slug => $translations) {
            $medicalArea = MedicalArea::updateOrCreate(
                ['slug' => $slug],
                ['is_active' => true]
            );

            foreach ($translations as $locale => $name) {
                MedicalAreaTranslation::updateOrCreate(
                    [
                        'medical_area_id' => $medicalArea->id,
                        'locale' => $locale,
                    ],
                    [
                        'name' => $name,
                    ]
                );
            }
        }
    }
}
