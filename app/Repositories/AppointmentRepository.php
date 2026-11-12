<?php

namespace App\Repositories;

use App\Enums\AppointmentHistoryEventEnum;
use App\Enums\AppointmentStatusEnum;
use App\Models\Appointment;
use App\Models\AppointmentHistory;
use App\Models\Client;
use App\Models\Doctor;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class AppointmentRepository
{
    public function paginateForClient(Client $client, array $filters): LengthAwarePaginator
    {
        return Appointment::query()
            ->with([
                'doctor:id,uuid,user_id,picture,consulting_room',
                'doctor.user:id,first_name,last_name',
                'medicalArea:id,slug',
                'medicalArea.translations:id,medical_area_id,locale,name',
            ])
            ->where('client_id', $client->id)
            ->when($filters['date'] ?? null, fn ($query, $date) => $query->whereDate('appointment_date', $date))
            ->when($filters['doctor'] ?? null, fn ($query, $uuid) => $query->whereHas('doctor', fn ($doctorQuery) => $doctorQuery->where('uuid', $uuid)))
            ->when($filters['status'] ?? null, fn ($query, $status) => $query->where('status', $status))
            ->orderByDesc('appointment_date')
            ->orderByDesc('id')
            ->paginate(8)
            ->withQueryString();
    }

    public function getDoctorsForClientHistory(Client $client): Collection
    {
        return Doctor::query()
            ->select(['doctors.id', 'doctors.uuid', 'doctors.user_id'])
            ->with('user:id,first_name,last_name')
            ->whereHas('appointments', fn ($query) => $query->where('client_id', $client->id))
            ->orderBy('id')
            ->get();
    }

    public function clientHasConfirmedAppointment(
        Client $client,
        int $doctorId,
        string $appointmentDate,
        ?int $excludeAppointmentId = null,
    ): bool {
        return Appointment::query()
            ->where('client_id', $client->id)
            ->where('doctor_id', $doctorId)
            ->whereDate('appointment_date', $appointmentDate)
            ->where('status', AppointmentStatusEnum::Confirmed->value)
            ->when($excludeAppointmentId, fn ($query) => $query->whereKeyNot($excludeAppointmentId))
            ->exists();
    }

    public function nextTurnNumber(int $doctorId, string $appointmentDate): int
    {
        return ((int) Appointment::withTrashed()
            ->where('doctor_id', $doctorId)
            ->whereDate('appointment_date', $appointmentDate)
            ->max('turn_number')) + 1;
    }

    public function create(array $data): Appointment
    {
        return Appointment::query()->create([
            'client_id' => $data['client_id'],
            'doctor_id' => $data['doctor_id'],
            'medical_area_id' => $data['medical_area_id'],
            'appointment_for' => $data['appointment_for'],
            'appointment_date' => $data['appointment_date'],
            'turn_number' => $data['turn_number'],
            'reason' => $data['reason'] ?? null,
            'status' => $data['status'],
        ]);
    }

    public function update(Appointment $appointment, array $data): Appointment
    {
        $appointment->update([
            'doctor_id' => $data['doctor_id'],
            'medical_area_id' => $data['medical_area_id'],
            'appointment_date' => $data['appointment_date'],
            'turn_number' => $data['turn_number'],
            'reason' => $data['reason'] ?? null,
        ]);

        return $appointment->refresh();
    }

    public function recordRescheduledHistory(Appointment $appointment, Client $client, array $oldValues): void
    {
        AppointmentHistory::query()->create([
            'appointment_id' => $appointment->id,
            'event' => AppointmentHistoryEventEnum::Rescheduled->value,
            'actor_type' => $client->getMorphClass(),
            'actor_id' => $client->id,
            'old_values' => $oldValues,
            'new_values' => [
                'doctor_id' => $appointment->doctor_id,
                'medical_area_id' => $appointment->medical_area_id,
                'appointment_date' => $appointment->appointment_date->toDateString(),
                'turn_number' => $appointment->turn_number,
                'reason' => $appointment->reason,
            ],
        ]);
    }

    public function recordCreatedHistory(Appointment $appointment, Client $client): void
    {
        AppointmentHistory::query()->create([
            'appointment_id' => $appointment->id,
            'event' => AppointmentHistoryEventEnum::Created->value,
            'actor_type' => $client->getMorphClass(),
            'actor_id' => $client->id,
            'old_values' => null,
            'new_values' => [
                'doctor_id' => $appointment->doctor_id,
                'medical_area_id' => $appointment->medical_area_id,
                'appointment_for' => $appointment->appointment_for,
                'appointment_date' => $appointment->appointment_date->toDateString(),
                'turn_number' => $appointment->turn_number,
                'status' => $appointment->status,
            ],
        ]);
    }

    public function loadCreationRelations(Appointment $appointment): Appointment
    {
        return $appointment->load([
            'doctor.user:id,first_name,last_name',
            'medicalArea.translations:id,medical_area_id,locale,name',
        ]);
    }
}
