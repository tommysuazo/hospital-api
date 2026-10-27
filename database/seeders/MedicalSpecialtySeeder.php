<?php

namespace Database\Seeders;

use App\Models\MedicalSpecialty;
use App\Models\MedicalSpecialtyTranslation;
use Illuminate\Database\Seeder;

class MedicalSpecialtySeeder extends Seeder
{
    public function run(): void
    {
        $specialties = [
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

        foreach ($specialties as $slug => $translations) {
            $specialty = MedicalSpecialty::updateOrCreate(
                ['slug' => $slug],
                ['is_active' => true]
            );

            foreach ($translations as $locale => $name) {
                MedicalSpecialtyTranslation::updateOrCreate(
                    [
                        'medical_specialty_id' => $specialty->id,
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