<?php

namespace Tests\Feature;

use App\Enums\DayOfWeekEnum;
use App\Models\Doctor;
use App\Models\DoctorSchedule;
use App\Models\DoctorTranslation;
use App\Models\MedicalProcedure;
use App\Models\MedicalProcedureTranslation;
use App\Models\MedicalArea;
use App\Models\MedicalAreaTranslation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class PublicSiteTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_pages_are_rendered_by_inertia(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('<html lang="es">', false)
            ->assertSee('family=Inter', false)
            ->assertSee('Plus+Jakarta+Sans', false)
            ->assertInertia(fn (Assert $page) => $page
                ->component('Public/Home')
                ->has('medicalAreas')
                ->has('procedures'));

        $this->get('/about')->assertOk()->assertInertia(fn (Assert $page) => $page
            ->component('Public/About'));

        $this->get('/contact')->assertOk()->assertInertia(fn (Assert $page) => $page
            ->component('Public/Contact'));

        $this->get('/login')->assertOk()->assertInertia(fn (Assert $page) => $page
            ->component('Public/Auth/Portal')
            ->where('mode', 'login'));

        $this->get('/register')->assertOk()->assertInertia(fn (Assert $page) => $page
            ->component('Public/Auth/Portal')
            ->where('mode', 'register'));
    }

    public function test_home_receives_translated_medical_areas_and_procedures(): void
    {
        $medicalArea = MedicalArea::factory()->create(['slug' => 'cardiology']);
        MedicalAreaTranslation::factory()->for($medicalArea, 'medicalArea')->create([
            'locale' => 'es',
            'name' => 'Cardiología',
        ]);

        $procedure = MedicalProcedure::factory()->create(['slug' => 'radiography']);
        MedicalProcedureTranslation::factory()->for($procedure, 'medicalProcedure')->create([
            'locale' => 'es',
            'name' => 'Radiografía',
            'short_description' => 'Estudio de imagen.',
        ]);

        $this->get('/')->assertOk()->assertInertia(fn (Assert $page) => $page
            ->where('medicalAreas.0.slug', 'cardiology')
            ->where('medicalAreas.0.names.es', 'Cardiología')
            ->where('procedures.0.slug', 'radiography')
            ->where('procedures.0.translations.es.name', 'Radiografía'));
    }

    public function test_doctor_directory_and_profile_receive_real_model_data(): void
    {
        $user = User::factory()->create([
            'first_name' => 'Ana',
            'last_name' => 'Fernández',
        ]);
        $doctor = Doctor::factory()->for($user)->create([
            'consulting_room' => '105',
            'phone_extension' => '1105',
        ]);
        DoctorTranslation::factory()->for($doctor)->create([
            'locale' => 'es',
            'professional_summary' => 'Especialista en salud infantil.',
        ]);
        DoctorSchedule::factory()->for($doctor)->create([
            'day_of_week' => DayOfWeekEnum::Tuesday->value,
        ]);

        $medicalArea = MedicalArea::factory()->create(['slug' => 'pediatrics']);
        MedicalAreaTranslation::factory()->for($medicalArea, 'medicalArea')->create([
            'locale' => 'es',
            'name' => 'Pediatría',
        ]);
        $doctor->medicalAreas()->attach($medicalArea);

        $this->get('/doctors')->assertOk()->assertInertia(fn (Assert $page) => $page
            ->component('Public/Doctors/Index')
            ->has('doctors', 1)
            ->where('doctors.0.name', 'Ana Fernández')
            ->where('doctors.0.workDays.0', 2)
            ->where('doctors.0.medicalAreas.0.names.es', 'Pediatría'));

        $this->get("/doctors/{$doctor->uuid}")->assertOk()->assertInertia(fn (Assert $page) => $page
            ->component('Public/Doctors/Show')
            ->where('doctor.uuid', $doctor->uuid)
            ->where('doctor.descriptions.es', 'Especialista en salud infantil.'));
    }

    public function test_inactive_doctors_are_not_public_and_unknown_profiles_return_404(): void
    {
        $user = User::factory()->create();
        Doctor::factory()->for($user)->create(['is_active' => false]);

        $this->get('/doctors')->assertOk()->assertInertia(fn (Assert $page) => $page
            ->has('doctors', 0));

        $this->get('/doctors/00000000-0000-0000-0000-000000000000')->assertNotFound();
    }
}
