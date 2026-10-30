<?php

namespace Tests\Feature;

use App\Enums\AppointmentForEnum;
use App\Enums\AppointmentHistoryEventEnum;
use App\Enums\AppointmentStatusEnum;
use App\Enums\DayOfWeekEnum;
use App\Models\Appointment;
use App\Models\AppointmentHistory;
use App\Models\Client;
use App\Models\Doctor;
use App\Models\DoctorSchedule;
use App\Models\DoctorUnavailability;
use App\Models\MedicalArea;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use LogicException;
use Tests\TestCase;

class AppointmentStructureTest extends TestCase
{
    use RefreshDatabase;

    public function test_doctor_schedule_relations_and_casts_are_configured(): void
    {
        $doctor = Doctor::factory()->create();
        $schedule = DoctorSchedule::factory()->for($doctor)->create([
            'day_of_week' => DayOfWeekEnum::Monday->value,
            'is_active' => true,
        ]);

        $this->assertTrue($schedule->doctor->is($doctor));
        $this->assertCount(1, $doctor->schedules);
        $this->assertSame(DayOfWeekEnum::Monday->value, $schedule->day_of_week);
        $this->assertIsBool($schedule->is_active);
        $this->assertTrue($schedule->is_active);
    }

    public function test_doctor_can_have_two_different_schedule_blocks_on_the_same_day(): void
    {
        $doctor = Doctor::factory()->create();

        DoctorSchedule::factory()->for($doctor)->create([
            'day_of_week' => DayOfWeekEnum::Monday->value,
            'start_time' => '08:00:00',
            'end_time' => '12:00:00',
        ]);
        DoctorSchedule::factory()->for($doctor)->create([
            'day_of_week' => DayOfWeekEnum::Monday->value,
            'start_time' => '14:00:00',
            'end_time' => '18:00:00',
        ]);

        $this->assertCount(2, $doctor->schedules);
    }

    public function test_doctor_unavailability_relations_and_date_casts_are_configured(): void
    {
        $doctor = Doctor::factory()->create();
        $unavailabilities = DoctorUnavailability::factory()
            ->count(2)
            ->for($doctor)
            ->create();

        $this->assertTrue($unavailabilities->first()->doctor->is($doctor));
        $this->assertCount(2, $doctor->unavailabilities);
        $this->assertInstanceOf(Carbon::class, $unavailabilities->first()->starts_on);
        $this->assertInstanceOf(Carbon::class, $unavailabilities->first()->ends_on);
        $this->assertTrue(
            $unavailabilities->every(
                fn (DoctorUnavailability $period): bool => $period->ends_on->gte($period->starts_on)
            )
        );
    }

    public function test_appointment_generates_a_uuid_and_has_expected_casts(): void
    {
        $appointment = Appointment::factory()->create([
            'uuid' => null,
            'appointment_for' => AppointmentForEnum::Dependent->value,
            'appointment_date' => '2026-10-30',
            'turn_number' => 7,
            'status' => AppointmentStatusEnum::Confirmed->value,
        ]);

        $this->assertTrue(Str::isUuid($appointment->uuid));
        $this->assertSame(AppointmentForEnum::Dependent->value, $appointment->appointment_for);
        $this->assertSame(AppointmentStatusEnum::Confirmed->value, $appointment->status);
        $this->assertInstanceOf(Carbon::class, $appointment->appointment_date);
        $this->assertSame('2026-10-30', $appointment->appointment_date->toDateString());
        $this->assertIsInt($appointment->turn_number);
        $this->assertSame(7, $appointment->turn_number);
    }

    public function test_appointment_belongs_to_client_doctor_and_medical_area(): void
    {
        $appointment = Appointment::factory()->create();

        $this->assertTrue($appointment->client instanceof Client);
        $this->assertTrue($appointment->doctor instanceof Doctor);
        $this->assertTrue($appointment->medicalArea instanceof MedicalArea);
        $this->assertTrue(
            $appointment->doctor->medicalAreas->contains($appointment->medicalArea)
        );
    }

    public function test_client_doctor_and_medical_area_can_have_multiple_appointments(): void
    {
        $client = Client::factory()->create();
        $doctor = Doctor::factory()->create();
        $medicalArea = MedicalArea::factory()->create();
        $doctor->medicalAreas()->attach($medicalArea);

        Appointment::factory()->create([
            'client_id' => $client->id,
            'doctor_id' => $doctor->id,
            'medical_area_id' => $medicalArea->id,
            'appointment_date' => '2026-10-30',
            'turn_number' => 1,
        ]);
        Appointment::factory()->create([
            'client_id' => $client->id,
            'doctor_id' => $doctor->id,
            'medical_area_id' => $medicalArea->id,
            'appointment_date' => '2026-10-30',
            'turn_number' => 2,
        ]);

        $this->assertCount(2, $client->appointments);
        $this->assertCount(2, $doctor->appointments);
        $this->assertCount(2, $medicalArea->appointments);
    }

    public function test_same_doctor_date_and_turn_number_conflicts(): void
    {
        $doctor = Doctor::factory()->create();

        Appointment::factory()->create([
            'doctor_id' => $doctor->id,
            'appointment_date' => '2026-10-30',
            'turn_number' => 1,
        ]);

        $this->expectException(QueryException::class);

        Appointment::factory()->create([
            'doctor_id' => $doctor->id,
            'appointment_date' => '2026-10-30',
            'turn_number' => 1,
        ]);
    }

    public function test_different_doctors_can_use_the_same_date_and_turn_number(): void
    {
        Appointment::factory()->create([
            'doctor_id' => Doctor::factory()->create()->id,
            'appointment_date' => '2026-10-30',
            'turn_number' => 1,
        ]);
        Appointment::factory()->create([
            'doctor_id' => Doctor::factory()->create()->id,
            'appointment_date' => '2026-10-30',
            'turn_number' => 1,
        ]);

        $this->assertDatabaseCount('appointments', 2);
    }

    public function test_same_doctor_can_use_the_same_turn_number_on_different_dates(): void
    {
        $doctor = Doctor::factory()->create();

        Appointment::factory()->create([
            'doctor_id' => $doctor->id,
            'appointment_date' => '2026-10-30',
            'turn_number' => 1,
        ]);
        Appointment::factory()->create([
            'doctor_id' => $doctor->id,
            'appointment_date' => '2026-10-31',
            'turn_number' => 1,
        ]);

        $this->assertDatabaseCount('appointments', 2);
    }

    public function test_appointment_history_relation_and_casts_are_configured(): void
    {
        $appointment = Appointment::factory()->create();
        $history = AppointmentHistory::factory()->for($appointment)->create([
            'event' => AppointmentHistoryEventEnum::Rescheduled->value,
            'old_values' => [
                'appointment_date' => '2026-10-25',
                'turn_number' => 8,
            ],
            'new_values' => [
                'appointment_date' => '2026-10-28',
                'turn_number' => 3,
            ],
        ]);
        AppointmentHistory::factory()->for($appointment)->create();

        $this->assertTrue($history->appointment->is($appointment));
        $this->assertCount(2, $appointment->histories);
        $this->assertSame(AppointmentHistoryEventEnum::Rescheduled->value, $history->event);
        $this->assertIsArray($history->old_values);
        $this->assertIsArray($history->new_values);
        $this->assertInstanceOf(Carbon::class, $history->created_at);
        $this->assertNull($history->getUpdatedAtColumn());
        $this->assertFalse(Schema::hasColumn('appointment_histories', 'updated_at'));
    }

    public function test_appointment_history_actor_can_be_a_user_or_client(): void
    {
        $appointment = Appointment::factory()->create();
        $user = User::factory()->create();
        $client = Client::factory()->create();

        $userHistory = AppointmentHistory::factory()
            ->for($appointment)
            ->forActor($user)
            ->create();
        $clientHistory = AppointmentHistory::factory()
            ->for($appointment)
            ->forActor($client)
            ->create();

        $this->assertTrue($userHistory->actor->is($user));
        $this->assertTrue($clientHistory->actor->is($client));
    }

    public function test_appointment_history_records_are_immutable_and_permanent(): void
    {
        $history = AppointmentHistory::factory()->create();

        try {
            $history->update(['event' => AppointmentHistoryEventEnum::Cancelled->value]);
            $this->fail('Updating appointment history should not be allowed.');
        } catch (LogicException) {
            $history->refresh();

            $this->assertDatabaseHas('appointment_histories', [
                'id' => $history->id,
                'event' => $history->event,
            ]);
        }

        $this->expectException(LogicException::class);

        $history->delete();
    }
}
