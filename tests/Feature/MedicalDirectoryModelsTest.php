<?php

namespace Tests\Feature;

use App\Models\Doctor;
use App\Models\DoctorTranslation;
use App\Models\MedicalProcedure;
use App\Models\MedicalProcedureTranslation;
use App\Models\MedicalArea;
use App\Models\MedicalAreaTranslation;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Tests\TestCase;

class MedicalDirectoryModelsTest extends TestCase
{
    use RefreshDatabase;

    public function test_doctor_can_belong_to_a_user_and_can_exist_without_one(): void
    {
        $user = User::factory()->create();
        $doctor = Doctor::factory()->for($user)->create();
        $independentDoctor = Doctor::factory()->create();

        $this->assertTrue($user->doctor->is($doctor));
        $this->assertTrue($doctor->user->is($user));
        $this->assertNull($independentDoctor->user);
    }

    public function test_a_user_linked_to_a_doctor_can_be_soft_deleted(): void
    {
        $user = User::factory()->create();
        $doctor = Doctor::factory()->for($user)->create();

        $user->delete();

        $this->assertSoftDeleted($user);
        $this->assertDatabaseHas('doctors', [
            'id' => $doctor->id,
            'user_id' => $user->id,
        ]);
    }

    public function test_doctor_generates_a_uuid_when_created_without_one(): void
    {
        $doctor = Doctor::create();
        $doctor->refresh();

        $this->assertTrue(Str::isUuid($doctor->uuid));
        $this->assertTrue($doctor->is_active);
    }

    public function test_doctor_uses_user_identity_and_updated_contact_columns(): void
    {
        $this->assertFalse(Schema::hasColumn('doctors', 'first_name'));
        $this->assertFalse(Schema::hasColumn('doctors', 'last_name'));
        $this->assertFalse(Schema::hasColumn('doctors', 'extension'));
        $this->assertFalse(Schema::hasColumn('doctors', 'photo_path'));
        $this->assertTrue(Schema::hasColumn('doctors', 'phone_extension'));
        $this->assertTrue(Schema::hasColumn('doctors', 'picture'));
    }

    public function test_a_user_cannot_be_associated_with_more_than_one_doctor(): void
    {
        $user = User::factory()->create();
        Doctor::factory()->for($user)->create();

        $this->expectException(QueryException::class);

        Doctor::factory()->for($user)->create();
    }

    public function test_entities_have_multiple_translations(): void
    {
        $doctor = Doctor::factory()->create();
        DoctorTranslation::factory()->for($doctor)->create(['locale' => 'es']);
        DoctorTranslation::factory()->for($doctor)->create(['locale' => 'en']);

        $medicalArea = MedicalArea::factory()->create();
        MedicalAreaTranslation::factory()
            ->for($medicalArea, 'medicalArea')
            ->create(['locale' => 'es']);
        MedicalAreaTranslation::factory()
            ->for($medicalArea, 'medicalArea')
            ->create(['locale' => 'en']);

        $procedure = MedicalProcedure::factory()->create();
        MedicalProcedureTranslation::factory()
            ->for($procedure, 'medicalProcedure')
            ->create(['locale' => 'es']);
        MedicalProcedureTranslation::factory()
            ->for($procedure, 'medicalProcedure')
            ->create(['locale' => 'en']);

        $this->assertCount(2, $doctor->translations);
        $this->assertCount(2, $medicalArea->translations);
        $this->assertCount(2, $procedure->translations);
        $this->assertFalse(Schema::hasColumn('medical_area_translations', 'description'));
    }

    public function test_doctors_and_medical_areas_have_a_many_to_many_relationship(): void
    {
        $doctor = Doctor::factory()->create();
        $medicalAreas = MedicalArea::factory()->count(2)->create();

        $doctor->medicalAreas()->attach($medicalAreas);

        $this->assertCount(2, $doctor->medicalAreas);
        $this->assertTrue($medicalAreas[0]->doctors->contains($doctor));
        $this->assertTrue($medicalAreas[1]->doctors->contains($doctor));
        $this->assertNotNull($doctor->medicalAreas[0]->pivot->created_at);
    }

    public function test_the_same_medical_area_cannot_be_attached_twice_to_a_doctor(): void
    {
        $doctor = Doctor::factory()->create();
        $medicalArea = MedicalArea::factory()->create();
        $doctor->medicalAreas()->attach($medicalArea);

        $this->expectException(QueryException::class);

        $doctor->medicalAreas()->attach($medicalArea);
    }

    public function test_a_doctor_cannot_have_duplicate_translation_locales(): void
    {
        $doctor = Doctor::factory()->create();
        DoctorTranslation::factory()->for($doctor)->create(['locale' => 'es']);

        $this->expectException(QueryException::class);

        DoctorTranslation::factory()->for($doctor)->create(['locale' => 'es']);
    }

    public function test_a_medical_area_cannot_have_duplicate_translation_locales(): void
    {
        $medicalArea = MedicalArea::factory()->create();
        MedicalAreaTranslation::factory()
            ->for($medicalArea, 'medicalArea')
            ->create(['locale' => 'en']);

        $this->expectException(QueryException::class);

        MedicalAreaTranslation::factory()
            ->for($medicalArea, 'medicalArea')
            ->create(['locale' => 'en']);
    }

    public function test_a_medical_procedure_cannot_have_duplicate_translation_locales(): void
    {
        $procedure = MedicalProcedure::factory()->create();
        MedicalProcedureTranslation::factory()
            ->for($procedure, 'medicalProcedure')
            ->create(['locale' => 'es']);

        $this->expectException(QueryException::class);

        MedicalProcedureTranslation::factory()
            ->for($procedure, 'medicalProcedure')
            ->create(['locale' => 'es']);
    }

    public function test_catalogs_use_slugs_and_boolean_active_casts(): void
    {
        $medicalArea = MedicalArea::factory()->create();
        $procedure = MedicalProcedure::factory()->create();

        $this->assertNotEmpty($medicalArea->slug);
        $this->assertNotEmpty($procedure->slug);
        $this->assertIsBool($medicalArea->is_active);
        $this->assertIsBool($procedure->is_active);
    }
}
