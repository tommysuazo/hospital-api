<?php

namespace Database\Seeders;

use App\Models\MedicalProcedure;
use App\Models\MedicalProcedureTranslation;
use Illuminate\Database\Seeder;

class MedicalProcedureSeeder extends Seeder
{
    public function run(): void
    {
        $procedures = [
            'x-ray' => [
                'es' => [
                    'name' => 'Radiografía',
                    'short_description' => 'Estudios de imagen para un diagnóstico rápido y preciso.',
                ],
                'en' => [
                    'name' => 'X-Ray',
                    'short_description' => 'Imaging studies for a fast and accurate diagnosis.',
                ],
            ],
            'ultrasound' => [
                'es' => [
                    'name' => 'Sonografía',
                    'short_description' => 'Ecografías y ultrasonidos con equipos de alta precisión.',
                ],
                'en' => [
                    'name' => 'Ultrasound',
                    'short_description' => 'Sonography exams with high-precision equipment.',
                ],
            ],
            'colonoscopy' => [
                'es' => [
                    'name' => 'Colonoscopia',
                    'short_description' => 'Procedimientos especializados para el cuidado de tu salud digestiva.',
                ],
                'en' => [
                    'name' => 'Colonoscopy',
                    'short_description' => 'Specialized procedures for your digestive health.',
                ],
            ],
        ];

        foreach ($procedures as $slug => $translations) {
            $procedure = MedicalProcedure::updateOrCreate(
                ['slug' => $slug],
                ['is_active' => true]
            );

            foreach ($translations as $locale => $translation) {
                MedicalProcedureTranslation::updateOrCreate(
                    [
                        'medical_procedure_id' => $procedure->id,
                        'locale' => $locale,
                    ],
                    [
                        'name' => $translation['name'],
                        'short_description' => $translation['short_description'],
                    ]
                );
            }
        }
    }
}
