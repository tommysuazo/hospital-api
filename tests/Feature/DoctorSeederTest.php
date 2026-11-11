<?php

namespace Tests\Feature;

use App\Models\Doctor;
use App\Models\DoctorSchedule;
use App\Models\DoctorUnavailability;
use App\Models\User;
use Database\Seeders\DoctorSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class DoctorSeederTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Carbon::setTestNow('2026-11-11 09:00:00');
        config(['seeding.doctor_count' => 4]);
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();

        parent::tearDown();
    }

    public function test_seeder_creates_doctors_with_their_expected_relations_and_no_appointments(): void
    {
        $this->seed(DoctorSeeder::class);

        $this->assertDatabaseCount('users', 4);
        $this->assertDatabaseCount('doctors', 4);
        $this->assertSame(4, Doctor::query()->distinct('user_id')->count('user_id'));
        $this->assertDatabaseCount('doctor_translations', 8);
        $this->assertDatabaseCount('doctor_unavailabilities', 4);
        $this->assertDatabaseCount('appointments', 0);
        $this->assertGreaterThanOrEqual(4, DB::table('doctor_medical_area')->count());
        $this->assertLessThanOrEqual(8, DB::table('doctor_medical_area')->count());
        $this->assertGreaterThanOrEqual(8, DoctorSchedule::query()->count());
        $this->assertLessThanOrEqual(16, DoctorSchedule::query()->count());

        Doctor::query()
            ->with(['user', 'translations', 'medicalAreas', 'schedules', 'unavailabilities'])
            ->get()
            ->each(function (Doctor $doctor): void {
                $this->assertNotNull($doctor->user);
                $this->assertTrue($doctor->user->is_active);
                $this->assertNotNull($doctor->user->email_verified_at);
                $this->assertCount(2, $doctor->translations);
                $this->assertNotEmpty($doctor->medicalAreas);
                $this->assertNotEmpty($doctor->schedules);
                $this->assertCount(1, $doctor->unavailabilities);
                $this->assertFileExists(public_path(ltrim($doctor->picture, '/')));
            });
    }

    public function test_each_run_creates_the_configured_number_of_doctors(): void
    {
        $this->seed(DoctorSeeder::class);
        $this->seed(DoctorSeeder::class);

        $this->assertDatabaseCount('users', 8);
        $this->assertDatabaseCount('doctors', 8);
        $this->assertSame(8, Doctor::query()->distinct('user_id')->count('user_id'));
        $this->assertDatabaseCount('doctor_translations', 16);
        $this->assertDatabaseCount('doctor_unavailabilities', 8);
        $this->assertDatabaseCount('appointments', 0);
    }

    public function test_seeded_doctors_use_active_schedules_and_unique_verified_users(): void
    {
        $this->seed(DoctorSeeder::class);

        $this->assertSame(4, User::query()->distinct('email')->count('email'));
        $this->assertTrue(
            DoctorSchedule::query()->get()->every(
                fn (DoctorSchedule $schedule): bool => $schedule->is_active
            )
        );
        $this->assertTrue(
            DoctorUnavailability::query()->get()->every(
                fn (DoctorUnavailability $period): bool => $period->ends_on->gte($period->starts_on)
            )
        );
    }
}
