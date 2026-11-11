<?php

namespace App\Repositories;

use App\Enums\AppointmentHistoryEventEnum;
use App\Enums\AppointmentStatusEnum;
use App\Models\Appointment;
use App\Models\AppointmentHistory;
use App\Models\Client;

class AppointmentRepository
{
    public function clientHasConfirmedAppointment(
        Client $client,
        int $doctorId,
        string $appointmentDate
    ): bool {
        return Appointment::query()
            ->where('client_id', $client->id)
            ->where('doctor_id', $doctorId)
            ->whereDate('appointment_date', $appointmentDate)
            ->where('status', AppointmentStatusEnum::Confirmed->value)
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
