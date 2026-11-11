<?php

namespace Tests\Feature;

use App\Enums\AppointmentForEnum;
use App\Enums\AppointmentHistoryEventEnum;
use App\Enums\AppointmentStatusEnum;
use App\Enums\DayOfWeekEnum;
use App\Models\Appointment;
use App\Models\Client;
use App\Models\Doctor;
use App\Models\DoctorSchedule;
use App\Models\DoctorUnavailability;
use App\Models\MedicalArea;
use App\Models\MedicalAreaTranslation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class ClientAppointmentCreationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Carbon::setTestNow('2026-11-11 09:00:00');
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();

        parent::tearDown();
    }

    public function test_guests_cannot_open_or_submit_the_appointment_form(): void
    {
        $this->get('/portal/appointments/create')
            ->assertRedirect(route('public.login'));

        $this->post('/portal/appointments')
            ->assertRedirect(route('public.login'));
    }

    public function test_verified_client_can_open_the_form_with_available_doctors(): void
    {
        [$doctor, $medicalArea] = $this->createAvailableDoctor();
        $client = Client::factory()->create(['email_verified_at' => now()]);

        $this->actingAs($client, 'client')
            ->get(route('client.appointments.create', ['doctor' => $doctor->uuid]))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Client/Appointments/Create')
                ->has('doctors', 1)
                ->where('doctors.0.uuid', $doctor->uuid)
                ->where('doctors.0.medicalAreas.0.slug', $medicalArea->slug)
                ->where('doctors.0.schedules.0.dayOfWeek', DayOfWeekEnum::Thursday->value)
                ->where('selectedDoctorUuid', $doctor->uuid)
                ->where('minimumDate', '2026-11-11')
                ->has('appointmentForOptions', 2));
    }

    public function test_unverified_client_is_forbidden_from_creating_appointments(): void
    {
        $client = Client::factory()->create(['email_verified_at' => null]);

        $this->actingAs($client, 'client')
            ->get('/portal/appointments/create')
            ->assertForbidden();
    }

    public function test_client_can_create_an_appointment_and_the_turn_is_assigned_by_the_server(): void
    {
        [$doctor, $medicalArea] = $this->createAvailableDoctor();
        $client = Client::factory()->create(['email_verified_at' => now()]);

        Appointment::factory()->create([
            'doctor_id' => $doctor->id,
            'medical_area_id' => $medicalArea->id,
            'appointment_date' => '2026-11-12',
            'turn_number' => 4,
        ]);

        $this->actingAs($client, 'client')
            ->post('/portal/appointments', [
                'doctor_uuid' => $doctor->uuid,
                'medical_area_slug' => $medicalArea->slug,
                'appointment_for' => AppointmentForEnum::Self->value,
                'appointment_date' => '2026-11-12',
                'reason' => 'Control de seguimiento.',
                'turn_number' => 99,
                'status' => AppointmentStatusEnum::Completed->value,
            ])
            ->assertRedirect(route('client.appointments.create'))
            ->assertSessionHas('status', 'Tu cita fue confirmada. Tu número de turno es 5.');

        $appointment = Appointment::query()
            ->where('client_id', $client->id)
            ->sole();

        $this->assertSame(5, $appointment->turn_number);
        $this->assertSame(AppointmentStatusEnum::Confirmed->value, $appointment->status);
        $this->assertDatabaseHas('appointments', [
            'client_id' => $client->id,
            'doctor_id' => $doctor->id,
            'medical_area_id' => $medicalArea->id,
            'appointment_for' => AppointmentForEnum::Self->value,
            'appointment_date' => '2026-11-12',
            'turn_number' => 5,
            'reason' => 'Control de seguimiento.',
            'status' => AppointmentStatusEnum::Confirmed->value,
        ]);
        $this->assertDatabaseHas('appointment_histories', [
            'appointment_id' => $appointment->id,
            'event' => AppointmentHistoryEventEnum::Created->value,
            'actor_type' => $client->getMorphClass(),
            'actor_id' => $client->id,
        ]);
    }

    public function test_form_validation_rejects_missing_and_past_appointment_data(): void
    {
        $client = Client::factory()->create(['email_verified_at' => now()]);

        $this->actingAs($client, 'client')
            ->from('/portal/appointments/create')
            ->post('/portal/appointments', [
                'doctor_uuid' => 'not-a-uuid',
                'appointment_date' => '2026-11-10',
                'appointment_for' => 'invalid',
            ])
            ->assertRedirect('/portal/appointments/create')
            ->assertSessionHasErrors([
                'doctor_uuid',
                'medical_area_slug',
                'appointment_for',
                'appointment_date',
            ]);

        $this->assertDatabaseCount('appointments', 0);
    }

    public function test_client_cannot_book_outside_the_schedule_or_during_unavailability(): void
    {
        [$doctor, $medicalArea] = $this->createAvailableDoctor();
        $client = Client::factory()->create(['email_verified_at' => now()]);

        $this->actingAs($client, 'client')
            ->from('/portal/appointments/create')
            ->post('/portal/appointments', [
                'doctor_uuid' => $doctor->uuid,
                'medical_area_slug' => $medicalArea->slug,
                'appointment_for' => AppointmentForEnum::Self->value,
                'appointment_date' => '2026-11-13',
            ])
            ->assertRedirect('/portal/appointments/create')
            ->assertSessionHasErrors(['appointment']);

        DoctorUnavailability::factory()->for($doctor)->create([
            'starts_on' => '2026-11-12',
            'ends_on' => '2026-11-12',
        ]);

        $this->actingAs($client, 'client')
            ->from('/portal/appointments/create')
            ->post('/portal/appointments', [
                'doctor_uuid' => $doctor->uuid,
                'medical_area_slug' => $medicalArea->slug,
                'appointment_for' => AppointmentForEnum::Self->value,
                'appointment_date' => '2026-11-12',
            ])
            ->assertRedirect('/portal/appointments/create')
            ->assertSessionHasErrors(['appointment']);

        $this->assertDatabaseCount('appointments', 0);
    }

    public function test_client_cannot_use_an_area_not_assigned_to_the_doctor_or_duplicate_a_booking(): void
    {
        [$doctor, $medicalArea] = $this->createAvailableDoctor();
        $otherArea = MedicalArea::factory()->create();
        $client = Client::factory()->create(['email_verified_at' => now()]);

        $this->actingAs($client, 'client')
            ->from('/portal/appointments/create')
            ->post('/portal/appointments', [
                'doctor_uuid' => $doctor->uuid,
                'medical_area_slug' => $otherArea->slug,
                'appointment_for' => AppointmentForEnum::Self->value,
                'appointment_date' => '2026-11-12',
            ])
            ->assertSessionHasErrors(['appointment']);

        Appointment::factory()->create([
            'client_id' => $client->id,
            'doctor_id' => $doctor->id,
            'medical_area_id' => $medicalArea->id,
            'appointment_date' => '2026-11-12',
            'turn_number' => 1,
            'status' => AppointmentStatusEnum::Confirmed->value,
        ]);

        $this->actingAs($client, 'client')
            ->from('/portal/appointments/create')
            ->post('/portal/appointments', [
                'doctor_uuid' => $doctor->uuid,
                'medical_area_slug' => $medicalArea->slug,
                'appointment_for' => AppointmentForEnum::Dependent->value,
                'appointment_date' => '2026-11-12',
            ])
            ->assertSessionHasErrors(['appointment']);

        $this->assertDatabaseCount('appointments', 1);
    }

    /**
     * @return array{Doctor, MedicalArea}
     */
    private function createAvailableDoctor(): array
    {
        $user = User::factory()->create([
            'first_name' => 'Elena',
            'last_name' => 'Pérez',
            'is_active' => true,
        ]);
        $doctor = Doctor::factory()->for($user)->create(['is_active' => true]);
        $medicalArea = MedicalArea::factory()->create([
            'slug' => 'cardiology',
            'is_active' => true,
        ]);
        MedicalAreaTranslation::factory()->for($medicalArea, 'medicalArea')->create([
            'locale' => 'es',
            'name' => 'Cardiología',
        ]);
        $doctor->medicalAreas()->attach($medicalArea);
        DoctorSchedule::factory()->for($doctor)->create([
            'day_of_week' => DayOfWeekEnum::Thursday->value,
            'start_time' => '08:00:00',
            'end_time' => '12:00:00',
            'is_active' => true,
        ]);

        return [$doctor, $medicalArea];
    }
}
