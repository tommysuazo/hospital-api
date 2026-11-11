<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Doctor;
use App\Models\MedicalService;
use App\Models\MedicalServiceCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class ClientDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_to_login(): void
    {
        $this->get('/portal')->assertRedirect(route('public.login'));
        $this->get('/portal/doctors')->assertRedirect(route('public.login'));
        $this->get('/portal/services')->assertRedirect(route('public.login'));
    }

    public function test_an_authenticated_client_can_see_the_dashboard_with_their_name(): void
    {
        $client = Client::factory()->create([
            'first_name' => 'Ana',
            'last_name' => 'Rodríguez',
            'email_verified_at' => now(),
        ]);

        $this->actingAs($client, 'client')
            ->get('/portal')
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Client/Dashboard')
                ->where('auth.type', 'client')
                ->where('auth.first_name', 'Ana')
                ->where('auth.last_name', 'Rodríguez'));
    }

    public function test_an_authenticated_client_can_browse_doctors_without_leaving_the_portal(): void
    {
        $client = Client::factory()->create(['email_verified_at' => now()]);
        $doctor = Doctor::factory()
            ->for(User::factory()->create())
            ->create();

        $this->actingAs($client, 'client')
            ->get('/portal/doctors')
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Client/Doctors/Index')
                ->has('doctors', 1)
                ->where('doctors.0.url', route('client.doctors.show', $doctor->uuid)));

        $this->actingAs($client, 'client')
            ->get(route('client.doctors.show', $doctor->uuid))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Client/Doctors/Show')
                ->where('doctor.uuid', $doctor->uuid));
    }

    public function test_an_authenticated_client_can_browse_services_without_leaving_the_portal(): void
    {
        $client = Client::factory()->create(['email_verified_at' => now()]);
        $category = MedicalServiceCategory::factory()->create();
        $medicalService = MedicalService::factory()
            ->for($category, 'category')
            ->create();

        $this->actingAs($client, 'client')
            ->get('/portal/services')
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Client/Services/Index')
                ->has('medicalServiceCategories', 1)
                ->where(
                    'medicalServiceCategories.0.medicalServices.0.url',
                    route('client.services.show', $medicalService->slug)
                ));

        $this->actingAs($client, 'client')
            ->get(route('client.services.show', $medicalService->slug))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Client/Services/Show')
                ->where('medicalService.slug', $medicalService->slug));
    }
}
