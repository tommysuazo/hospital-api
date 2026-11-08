<?php

namespace Database\Seeders;

use App\Models\MedicalService;
use App\Models\MedicalServiceCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MedicalServiceSeeder extends Seeder
{
    public function run(): void
    {
        $catalog = [
            'clinical-laboratory' => [
                'names' => [
                    'es' => 'Laboratorio clínico',
                    'en' => 'Clinical Laboratory',
                ],
                'services' => [
                    ['complete-blood-count', 'Hemograma completo', 'Complete Blood Count'],
                    ['fasting-blood-glucose', 'Glucosa en ayunas', 'Fasting Blood Glucose'],
                    ['hemoglobin-a1c', 'Hemoglobina glicosilada (HbA1c)', 'Hemoglobin A1c'],
                    ['lipid-panel', 'Perfil lipídico', 'Lipid Panel'],
                    ['serum-creatinine', 'Creatinina sérica', 'Serum Creatinine'],
                    ['thyroid-stimulating-hormone', 'Hormona estimulante de la tiroides (TSH)', 'Thyroid Stimulating Hormone'],
                    ['urinalysis', 'Examen general de orina', 'Urinalysis'],
                    ['urine-culture', 'Urocultivo', 'Urine Culture'],
                    ['stool-ova-and-parasite-test', 'Examen coproparasitario', 'Stool Ova and Parasite Test'],
                    ['blood-pregnancy-test', 'Prueba de embarazo en sangre (β-hCG)', 'Blood Pregnancy Test'],
                ],
            ],

            'diagnostic-imaging' => [
                'names' => [
                    'es' => 'Imágenes diagnósticas',
                    'en' => 'Diagnostic Imaging',
                ],
                'services' => [
                    ['chest-x-ray', 'Radiografía de tórax', 'Chest X-ray'],
                    ['abdominal-x-ray', 'Radiografía de abdomen', 'Abdominal X-ray'],
                    ['lumbar-spine-x-ray', 'Radiografía de columna lumbar', 'Lumbar Spine X-ray'],
                    ['abdominal-ultrasound', 'Sonografía abdominal', 'Abdominal Ultrasound'],
                    ['pelvic-ultrasound', 'Sonografía pélvica', 'Pelvic Ultrasound'],
                    ['renal-ultrasound', 'Sonografía renal', 'Renal Ultrasound'],
                    ['thyroid-ultrasound', 'Sonografía tiroidea', 'Thyroid Ultrasound'],
                    ['obstetric-ultrasound', 'Sonografía obstétrica', 'Obstetric Ultrasound'],
                ],
            ],

            'endoscopic-procedures' => [
                'names' => [
                    'es' => 'Endoscopias',
                    'en' => 'Endoscopic Procedures',
                ],
                'services' => [
                    ['upper-gi-endoscopy', 'Endoscopia digestiva alta', 'Upper Gastrointestinal Endoscopy'],
                    ['colonoscopy', 'Colonoscopia', 'Colonoscopy'],
                    ['rectosigmoidoscopy', 'Rectosigmoidoscopia', 'Rectosigmoidoscopy'],
                    ['endoscopic-polypectomy', 'Polipectomía endoscópica', 'Endoscopic Polypectomy'],
                ],
            ],

            'obstetric-procedures' => [
                'names' => [
                    'es' => 'Procedimientos obstétricos',
                    'en' => 'Obstetric Procedures',
                ],
                'services' => [
                    ['childbirth', 'Parto', 'Childbirth'],
                    ['cesarean-section', 'Cesárea', 'Cesarean Section'],
                    ['fetal-monitoring', 'Monitoreo fetal', 'Fetal Monitoring'],
                ],
            ],

            'gynecological-procedures' => [
                'names' => [
                    'es' => 'Procedimientos ginecológicos',
                    'en' => 'Gynecological Procedures',
                ],
                'services' => [
                    ['pap-smear', 'Papanicolaou', 'Pap Smear'],
                    ['colposcopy', 'Colposcopia', 'Colposcopy'],
                    ['cervical-biopsy', 'Biopsia cervical', 'Cervical Biopsy'],
                    ['iud-insertion', 'Colocación de dispositivo intrauterino', 'Intrauterine Device Insertion'],
                ],
            ],

            'cardiovascular-studies' => [
                'names' => [
                    'es' => 'Estudios cardiovasculares',
                    'en' => 'Cardiovascular Studies',
                ],
                'services' => [
                    ['electrocardiogram', 'Electrocardiograma', 'Electrocardiogram'],
                    ['transthoracic-echocardiogram', 'Ecocardiograma transtorácico', 'Transthoracic Echocardiogram'],
                    ['holter-monitoring', 'Monitoreo Holter de 24 horas', '24-Hour Holter Monitoring'],
                    ['ambulatory-blood-pressure-monitoring', 'Monitoreo ambulatorio de presión arterial', 'Ambulatory Blood Pressure Monitoring'],
                    ['cardiac-stress-test', 'Prueba de esfuerzo', 'Cardiac Stress Test'],
                ],
            ],

            'respiratory-tests' => [
                'names' => [
                    'es' => 'Pruebas respiratorias',
                    'en' => 'Respiratory Tests',
                ],
                'services' => [
                    ['spirometry', 'Espirometría', 'Spirometry'],
                    ['bronchodilator-response-test', 'Prueba broncodilatadora', 'Bronchodilator Response Test'],
                    ['arterial-blood-gas-test', 'Gasometría arterial', 'Arterial Blood Gas Test'],
                ],
            ],

            'surgical-procedures' => [
                'names' => [
                    'es' => 'Procedimientos quirúrgicos',
                    'en' => 'Surgical Procedures',
                ],
                'services' => [
                    ['appendectomy', 'Apendicectomía', 'Appendectomy'],
                    ['cholecystectomy', 'Colecistectomía', 'Cholecystectomy'],
                    ['inguinal-hernia-repair', 'Reparación de hernia inguinal', 'Inguinal Hernia Repair'],
                    ['wound-suturing', 'Sutura de heridas', 'Wound Suturing'],
                    ['abscess-drainage', 'Drenaje de abscesos', 'Abscess Drainage'],
                ],
            ],
        ];

        DB::transaction(function () use ($catalog): void {
            foreach ($catalog as $categoryIndex => $data) {
                $categorySlug = $categoryIndex;

                $category = MedicalServiceCategory::withTrashed()
                    ->firstOrNew([
                        'slug' => $categorySlug,
                    ]);

                $category->sort_order = array_search(
                    $categorySlug,
                    array_keys($catalog),
                    true
                ) + 1;

                if (! $category->exists) {
                    $category->is_active = true;
                }

                $category->save();

                foreach ($data['names'] as $locale => $name) {
                    $category->translations()->updateOrCreate(
                        ['locale' => $locale],
                        ['name' => $name]
                    );
                }

                foreach ($data['services'] as $index => $item) {
                    [$slug, $esName, $enName] = $item;

                    $service = MedicalService::withTrashed()
                        ->firstOrNew([
                            'slug' => $slug,
                        ]);

                    $service->medical_service_category_id = $category->id;
                    $service->sort_order = $index + 1;

                    if (! $service->exists) {
                        $service->is_active = true;
                    }

                    $service->save();

                    foreach ([
                        'es' => $esName,
                        'en' => $enName,
                    ] as $locale => $name) {
                        $translation = $service->translations()
                            ->firstOrNew([
                                'locale' => $locale,
                            ]);

                        $translation->name = $name;
                        $translation->save();
                    }
                }
            }
        });
    }
}
