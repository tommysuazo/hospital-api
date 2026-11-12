<?php

namespace Tests\Feature;

use App\Enums\AppointmentHistoryEventEnum;
use App\Enums\AppointmentStatusEnum;
use App\Enums\DayOfWeekEnum;
use App\Models\Appointment;
use App\Models\Client;
use App\Models\Doctor;
use App\Models\DoctorSchedule;
use App\Models\MedicalArea;
use App\Models\MedicalAreaTranslation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class ClientAppointmentHistoryTest extends TestCase
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

    public function test_client_can_filter_only_their_appointment_history(): void
    {
        [$doctor, $medicalArea] = $this->createAvailableDoctor();
        $client = Client::factory()->create(['email_verified_at' => now()]);
        $otherClient = Client::factory()->create(['email_verified_at' => now()]);

        Appointment::factory()->for($client)->create([
            'doctor_id' => $doctor->id,
            'medical_area_id' => $medicalArea->id,
            'appointment_date' => '2026-11-12',
            'status' => AppointmentStatusEnum::Confirmed->value,
        ]);
        Appointment::factory()->for($client)->create([
            'doctor_id' => $doctor->id,
            'medical_area_id' => $medicalArea->id,
            'appointment_date' => '2026-11-19',
            'status' => AppointmentStatusEnum::Completed->value,
        ]);
        Appointment::factory()->for($otherClient)->create([
            'doctor_id' => $doctor->id,
            'medical_area_id' => $medicalArea->id,
            'appointment_date' => '2026-11-12',
            'status' => AppointmentStatusEnum::Confirmed->value,
        ]);

        $this->actingAs($client, 'client')
            ->get(route('client.appointments.index', [
                'date' => '2026-11-12',
                'doctor' => $doctor->uuid,
                'status' => AppointmentStatusEnum::Confirmed->value,
            ]))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Client/Appointments/Index')
                ->has('appointments.data', 1)
                ->where('appointments.total', 1)
                ->where('appointments.data.0.canEdit', true)
                ->where('appointments.data.0.doctor.uuid', $doctor->uuid));
    }

    public function test_client_can_edit_their_pending_appointment(): void
    {
        [$doctor, $medicalArea] = $this->createAvailableDoctor();
        $client = Client::factory()->create(['email_verified_at' => now()]);
        $appointment = Appointment::factory()->for($client)->create([
            'doctor_id' => $doctor->id,
            'medical_area_id' => $medicalArea->id,
            'appointment_date' => '2026-11-12',
            'turn_number' => 2,
            'status' => AppointmentStatusEnum::Confirmed->value,
        ]);

        $this->actingAs($client, 'client')
            ->put(route('client.appointments.update', ['appointment' => $appointment->uuid]), [
                'doctor_uuid' => $doctor->uuid,
                'medical_area_slug' => $medicalArea->slug,
                'appointment_date' => '2026-11-19',
                'reason' => 'Motivo actualizado.',
            ])
            ->assertRedirect(route('client.appointments.index'));

        $appointment->refresh();
        $this->assertSame('2026-11-19', $appointment->appointment_date->toDateString());
        $this->assertSame('Motivo actualizado.', $appointment->reason);
        $this->assertDatabaseHas('appointment_histories', [
            'appointment_id' => $appointment->id,
            'event' => AppointmentHistoryEventEnum::Rescheduled->value,
            'actor_id' => $client->id,
        ]);
    }

    public function test_completed_appointments_and_other_clients_appointments_cannot_be_edited(): void
    {
        [$doctor, $medicalArea] = $this->createAvailableDoctor();
        $client = Client::factory()->create(['email_verified_at' => now()]);
        $otherClient = Client::factory()->create(['email_verified_at' => now()]);
        $completed = Appointment::factory()->for($client)->create([
            'doctor_id' => $doctor->id,
            'medical_area_id' => $medicalArea->id,
            'status' => AppointmentStatusEnum::Completed->value,
        ]);
        $otherAppointment = Appointment::factory()->for($otherClient)->create([
            'doctor_id' => $doctor->id,
            'medical_area_id' => $medicalArea->id,
        ]);

        $this->actingAs($client, 'client')->get(route('client.appointments.edit', ['appointment' => $completed->uuid]))->assertForbidden();
        $this->actingAs($client, 'client')->get(route('client.appointments.edit', ['appointment' => $otherAppointment->uuid]))->assertForbidden();
    }

    private function createAvailableDoctor(): array
    {
        $user = User::factory()->create(['first_name' => 'Elena', 'last_name' => 'Pérez', 'is_active' => true]);
        $doctor = Doctor::factory()->for($user)->create(['is_active' => true]);
        $medicalArea = MedicalArea::factory()->create(['slug' => 'cardiology', 'is_active' => true]);
        MedicalAreaTranslation::factory()->for($medicalArea, 'medicalArea')->create(['locale' => 'es', 'name' => 'Cardiología']);
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
