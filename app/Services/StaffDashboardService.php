<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\MedicalResult;
use App\Repositories\StaffDashboardRepository;
use Carbon\Carbon;

class StaffDashboardService
{
    public function __construct(private readonly StaffDashboardRepository $dashboardRepository) {}

    /** @return array<string, mixed> */
    public function getDashboardData(): array
    {
        $today = now()->startOfDay();
        $weekStart = $today->copy()->startOfWeek(Carbon::MONDAY);
        $weekEnd = $weekStart->copy()->addDays(6);
        $weeklyAppointments = $this->dashboardRepository->appointmentsBetween($weekStart, $weekEnd);

        $weeklyCounts = collect(range(0, 6))->map(function (int $offset) use ($weekStart, $weeklyAppointments): array {
            $date = $weekStart->copy()->addDays($offset);

            return [
                'date' => $date->toDateString(),
                'label' => ucfirst($date->locale('es')->isoFormat('ddd')),
                'count' => $weeklyAppointments->filter(
                    fn (Appointment $appointment): bool => $appointment->appointment_date->isSameDay($date)
                )->count(),
            ];
        });
        $weeklyMaximum = max(1, (int) $weeklyCounts->max('count'));

        return [
            'generatedAt' => now()->toIso8601String(),
            'todayLabel' => ucfirst($today->locale('es')->isoFormat('dddd D [de] MMMM [de] YYYY')),
            'stats' => [
                'appointmentsToday' => $this->dashboardRepository->countAppointmentsOn($today),
                'upcomingAppointments' => $this->dashboardRepository->countUpcomingAppointments($today),
                'activeClients' => $this->dashboardRepository->countActiveClients(),
                'activeDoctors' => $this->dashboardRepository->countActiveDoctors(),
                'draftResults' => $this->dashboardRepository->countDraftMedicalResults(),
                'currentAlerts' => $this->dashboardRepository->countCurrentDoctorUnavailabilities($today),
            ],
            'weeklyAppointments' => $weeklyCounts
                ->map(fn (array $day): array => [
                    ...$day,
                    'percentage' => (int) round(($day['count'] / $weeklyMaximum) * 100),
                ])
                ->values()
                ->all(),
            'statusDistribution' => $this->statusDistribution(),
            'upcomingAppointments' => $this->dashboardRepository
                ->upcomingAppointments($today)
                ->map(fn (Appointment $appointment): array => $this->mapAppointment($appointment))
                ->all(),
            'recentResults' => $this->dashboardRepository
                ->recentMedicalResults()
                ->map(fn (MedicalResult $result): array => $this->mapMedicalResult($result))
                ->all(),
        ];
    }

    /** @return list<array{status: string, label: string, count: int, color: string}> */
    private function statusDistribution(): array
    {
        $metadata = [
            'confirmed' => ['label' => 'Confirmadas', 'color' => '#2f6cb3'],
            'completed' => ['label' => 'Completadas', 'color' => '#2bae78'],
            'cancelled' => ['label' => 'Canceladas', 'color' => '#d65361'],
            'no_show' => ['label' => 'No asistió', 'color' => '#94a3b8'],
        ];
        $counts = $this->dashboardRepository->appointmentStatusCounts()->keyBy('status');

        return collect($metadata)->map(
            fn (array $item, string $status): array => [
                'status' => $status,
                'label' => $item['label'],
                'count' => (int) ($counts->get($status)?->total ?? 0),
                'color' => $item['color'],
            ]
        )->values()->all();
    }

    /** @return array<string, mixed> */
    private function mapAppointment(Appointment $appointment): array
    {
        $area = $appointment->medicalArea->translations->firstWhere('locale', 'es');

        return [
            'uuid' => $appointment->uuid,
            'client' => trim("{$appointment->client->first_name} {$appointment->client->last_name}"),
            'doctor' => trim("{$appointment->doctor->user?->first_name} {$appointment->doctor->user?->last_name}"),
            'medicalArea' => $area?->name ?? $appointment->medicalArea->slug,
            'date' => $appointment->appointment_date->toDateString(),
            'dateLabel' => ucfirst($appointment->appointment_date->locale('es')->isoFormat('D MMM')),
            'turnNumber' => $appointment->turn_number,
            'consultingRoom' => $appointment->doctor->consulting_room,
            'status' => $appointment->status,
        ];
    }

    /** @return array<string, mixed> */
    private function mapMedicalResult(MedicalResult $result): array
    {
        $translation = $result->translations->firstWhere('locale', 'es');

        return [
            'uuid' => $result->uuid,
            'client' => trim("{$result->client->first_name} {$result->client->last_name}"),
            'title' => $translation?->title ?? 'Resultado médico',
            'status' => $result->status,
            'createdAt' => $result->created_at?->toIso8601String(),
            'createdAtLabel' => $result->created_at?->locale('es')->diffForHumans(),
        ];
    }
}
