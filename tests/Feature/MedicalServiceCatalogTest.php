<?php

namespace Tests\Feature;

use App\Models\MedicalServiceCategory;
use Database\Seeders\MedicalServiceSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class MedicalServiceCatalogTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_medical_service_catalog_is_seeded_in_the_expected_order(): void
    {
        $this->seed(MedicalServiceSeeder::class);

        $this->assertSame([
            'clinical-laboratory',
            'diagnostic-imaging',
            'endoscopic-procedures',
            'obstetric-procedures',
            'gynecological-procedures',
            'cardiovascular-studies',
            'respiratory-tests',
            'surgical-procedures',
        ], MedicalServiceCategory::query()
            ->orderBy('sort_order')
            ->pluck('slug')
            ->all());

        $clinicalLaboratory = MedicalServiceCategory::query()
            ->where('slug', 'clinical-laboratory')
            ->firstOrFail();

        $this->assertSame([
            'complete-blood-count',
            'fasting-blood-glucose',
            'hemoglobin-a1c',
            'lipid-panel',
            'serum-creatinine',
            'thyroid-stimulating-hormone',
            'urinalysis',
            'urine-culture',
            'stool-ova-and-parasite-test',
            'blood-pregnancy-test',
        ], $clinicalLaboratory->medicalServices()
            ->orderBy('sort_order')
            ->pluck('slug')
            ->all());

        $this->assertDatabaseCount('medical_service_categories', 8);
        $this->assertDatabaseCount('medical_service_category_translations', 16);
        $this->assertDatabaseCount('medical_services', 42);
        $this->assertDatabaseCount('medical_service_translations', 84);
        $this->assertTrue(Schema::hasTable('medical_service_categories'));
        $this->assertTrue(Schema::hasTable('medical_services'));
    }

    public function test_seeding_the_catalog_again_does_not_duplicate_services(): void
    {
        $this->seed(MedicalServiceSeeder::class);
        $this->seed(MedicalServiceSeeder::class);

        $this->assertDatabaseCount('medical_service_categories', 8);
        $this->assertDatabaseCount('medical_service_category_translations', 16);
        $this->assertDatabaseCount('medical_services', 42);
        $this->assertDatabaseCount('medical_service_translations', 84);
    }
}
