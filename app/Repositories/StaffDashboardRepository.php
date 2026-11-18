<?php

namespace App\Repositories;

use App\Models\Appointment;
use App\Models\Client;
use App\Models\Doctor;
use App\Models\DoctorUnavailability;
use App\Models\MedicalResult;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;

class StaffDashboardRepository
{
    public function countAppointmentsOn(CarbonInterface $date): int
    {
        return Appointment::query()
            ->whereDate('appointment_date', $date)
            ->count();
    }

    public function countUpcomingAppointments(CarbonInterface $date): int
    {
        return Appointment::query()
            ->whereDate('appointment_date', '>=', $date)
            ->where('status', 'confirmed')
            ->count();
    }

    public function countActiveClients(): int
    {
        return Client::query()->where('is_active', true)->count();
    }

    public function countActiveDoctors(): int
    {
        return Doctor::query()->where('is_active', true)->count();
    }

    public function countDraftMedicalResults(): int
    {
        return MedicalResult::query()->where('status', 'draft')->count();
    }

    public function countCurrentDoctorUnavailabilities(CarbonInterface $date): int
    {
        return DoctorUnavailability::query()
            ->whereDate('starts_on', '<=', $date)
            ->whereDate('ends_on', '>=', $date)
            ->count();
    }

    /** @return Collection<int, Appointment> */
    public function appointmentsBetween(CarbonInterface $start, CarbonInterface $end): Collection
    {
        return Appointment::query()
            ->whereBetween('appointment_date', [$start->toDateString(), $end->toDateString()])
            ->get(['id', 'appointment_date', 'status']);
    }

    /** @return SupportCollection<int, object{status: string, total: int}> */
    public function appointmentStatusCounts(): SupportCollection
    {
        return Appointment::query()
            ->select('status')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('status')
            ->get();
    }

    /** @return Collection<int, Appointment> */
    public function upcomingAppointments(CarbonInterface $date, int $limit = 5): Collection
    {
        return Appointment::query()
            ->with([
                'client:id,first_name,last_name',
                'doctor:id,user_id,consulting_room',
                'doctor.user:id,first_name,last_name',
                'medicalArea:id,slug',
                'medicalArea.translations:id,medical_area_id,locale,name',
            ])
            ->whereDate('appointment_date', '>=', $date)
            ->orderBy('appointment_date')
            ->orderBy('turn_number')
            ->limit($limit)
            ->get();
    }

    /** @return Collection<int, MedicalResult> */
    public function recentMedicalResults(int $limit = 5): Collection
    {
        return MedicalResult::query()
            ->with([
                'client:id,first_name,last_name',
                'translations:id,medical_result_id,locale,title',
            ])
            ->latest()
            ->limit($limit)
            ->get();
    }
}
