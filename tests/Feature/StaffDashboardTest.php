<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\Client;
use App\Models\Doctor;
use App\Models\MedicalArea;
use App\Models\MedicalAreaTranslation;
use App\Models\MedicalResult;
use App\Models\MedicalResultTranslation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class StaffDashboardTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Carbon::setTestNow('2026-11-18 09:00:00');
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();

        parent::tearDown();
    }

    public function test_guests_and_clients_cannot_access_the_staff_panel(): void
    {
        $this->get('/staff')->assertRedirect(route('public.login'));

        $client = Client::factory()->create(['email_verified_at' => now()]);

        $this->actingAs($client, 'client')
            ->get('/staff')
            ->assertRedirect(route('public.login'));
    }

    public function test_authenticated_staff_can_view_the_dashboard_without_role_restrictions(): void
    {
        $staff = User::factory()->create(['first_name' => 'Super', 'last_name' => 'Admin']);
        $client = Client::factory()->create(['first_name' => 'Tommy', 'last_name' => 'Suazo']);
        $doctorUser = User::factory()->create(['first_name' => 'María', 'last_name' => 'Rodríguez']);
        $doctor = Doctor::factory()->for($doctorUser)->create(['consulting_room' => '204']);
        $medicalArea = MedicalArea::factory()->create(['slug' => 'cardiologia']);
        MedicalAreaTranslation::factory()->for($medicalArea)->create(['locale' => 'es', 'name' => 'Cardiología']);

        Appointment::factory()->create([
            'client_id' => $client->id,
            'doctor_id' => $doctor->id,
            'medical_area_id' => $medicalArea->id,
            'appointment_date' => now()->toDateString(),
            'turn_number' => 1,
            'status' => 'confirmed',
        ]);

        $result = MedicalResult::factory()->for($client)->create(['status' => 'draft']);
        MedicalResultTranslation::factory()->for($result)->create(['locale' => 'es', 'title' => 'Hemograma completo']);

        $this->actingAs($staff, 'staff')
            ->get('/staff')
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Staff/Dashboard')
                ->where('auth.type', 'staff')
                ->where('auth.first_name', 'Super')
                ->where('stats.appointmentsToday', 1)
                ->where('stats.upcomingAppointments', 1)
                ->where('stats.activeClients', 1)
                ->where('stats.activeDoctors', 1)
                ->where('stats.draftResults', 1)
                ->has('weeklyAppointments', 7)
                ->has('statusDistribution', 4)
                ->has('upcomingAppointments', 1)
                ->where('upcomingAppointments.0.client', 'Tommy Suazo')
                ->where('upcomingAppointments.0.doctor', 'María Rodríguez')
                ->where('upcomingAppointments.0.medicalArea', 'Cardiología')
                ->has('recentResults', 1)
                ->where('recentResults.0.title', 'Hemograma completo'));
    }

    public function test_all_staff_module_destinations_are_available(): void
    {
        $staff = User::factory()->create();
        $modules = [
            '/staff/appointments' => 'appointments',
            '/staff/clients' => 'clients',
            '/staff/doctors' => 'doctors',
            '/staff/medical-results' => 'medical-results',
            '/staff/schedules' => 'schedules',
            '/staff/medical-areas' => 'medical-areas',
            '/staff/medical-services' => 'medical-services',
            '/staff/facilities' => 'facilities',
            '/staff/insurance' => 'insurance',
            '/staff/documents' => 'documents',
            '/staff/users' => 'users',
            '/staff/roles' => 'roles',
            '/staff/audit' => 'audit',
            '/staff/settings' => 'settings',
        ];

        foreach ($modules as $url => $key) {
            $this->actingAs($staff, 'staff')
                ->get($url)
                ->assertOk()
                ->assertInertia(fn (Assert $page) => $page
                    ->component('Staff/Module')
                    ->where('module.key', $key));
        }
    }
}
