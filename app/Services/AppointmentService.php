<?php

namespace App\Services;

use App\Enums\AppointmentStatusEnum;
use App\Enums\DayOfWeekEnum;
use App\Exceptions\KnownException;
use App\Models\Appointment;
use App\Models\Client;
use App\Models\Doctor;
use App\Repositories\AppointmentRepository;
use App\Repositories\DoctorRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class AppointmentService
{
    public function __construct(
        private readonly AppointmentRepository $appointmentRepository,
        private readonly DoctorRepository $doctorRepository,
    ) {}

    /**
     * @return Collection<int, Doctor>
     */
    public function getCreationDoctors(): Collection
    {
        return $this->doctorRepository->getAppointmentDirectory();
    }

    public function getClientHistory(Client $client, array $filters): LengthAwarePaginator
    {
        return $this->appointmentRepository->paginateForClient($client, $filters);
    }

    public function getClientHistoryDoctors(Client $client): Collection
    {
        return $this->appointmentRepository->getDoctorsForClientHistory($client);
    }

    public function create(Client $client, array $data): Appointment
    {
        return DB::transaction(function () use ($client, $data): Appointment {
            $doctor = $this->doctorRepository->findActiveForAppointment($data['doctor_uuid']);
            $appointmentDate = Carbon::createFromFormat('Y-m-d', $data['appointment_date'])
                ->startOfDay();

            if (! $doctor) {
                throw new KnownException('El doctor seleccionado no está disponible para citas.');
            }

            $medicalArea = $doctor->medicalAreas
                ->firstWhere('slug', $data['medical_area_slug']);

            if (! $medicalArea) {
                throw new KnownException('El área médica seleccionada no está disponible con este doctor.');
            }

            $dayOfWeek = $this->dayOfWeek($appointmentDate);

            if (! $doctor->schedules->contains('day_of_week', $dayOfWeek->value)) {
                throw new KnownException('El doctor no ofrece consultas en la fecha seleccionada.');
            }

            $isUnavailable = $doctor->unavailabilities->contains(
                fn ($period): bool => $appointmentDate->betweenIncluded(
                    $period->starts_on,
                    $period->ends_on
                )
            );

            if ($isUnavailable) {
                throw new KnownException('El doctor no está disponible en la fecha seleccionada.');
            }

            if ($this->appointmentRepository->clientHasConfirmedAppointment(
                $client,
                $doctor->id,
                $appointmentDate->toDateString()
            )) {
                throw new KnownException('Ya tienes una cita confirmada con este doctor para esa fecha.');
            }

            $appointment = $this->appointmentRepository->create([
                'client_id' => $client->id,
                'doctor_id' => $doctor->id,
                'medical_area_id' => $medicalArea->id,
                'appointment_for' => $data['appointment_for'],
                'appointment_date' => $appointmentDate->toDateString(),
                'turn_number' => $this->appointmentRepository->nextTurnNumber(
                    $doctor->id,
                    $appointmentDate->toDateString()
                ),
                'reason' => $data['reason'] ?? null,
                'status' => AppointmentStatusEnum::Confirmed->value,
            ]);

            $this->appointmentRepository->recordCreatedHistory($appointment, $client);

            return $this->appointmentRepository->loadCreationRelations($appointment);
        });
    }

    public function update(Client $client, Appointment $appointment, array $data): Appointment
    {
        if ($appointment->status !== AppointmentStatusEnum::Confirmed->value) {
            throw new KnownException('Solo puedes editar citas pendientes.');
        }

        return DB::transaction(function () use ($client, $appointment, $data): Appointment {
            $doctor = $this->doctorRepository->findActiveForAppointment($data['doctor_uuid']);
            $appointmentDate = Carbon::createFromFormat('Y-m-d', $data['appointment_date'])->startOfDay();

            if (! $doctor) {
                throw new KnownException('El doctor seleccionado no está disponible para citas.');
            }

            $medicalArea = $doctor->medicalAreas->firstWhere('slug', $data['medical_area_slug']);

            if (! $medicalArea) {
                throw new KnownException('El área médica seleccionada no está disponible con este doctor.');
            }

            if (! $doctor->schedules->contains('day_of_week', $this->dayOfWeek($appointmentDate)->value)) {
                throw new KnownException('El doctor no ofrece consultas en la fecha seleccionada.');
            }

            if ($doctor->unavailabilities->contains(fn ($period): bool => $appointmentDate->betweenIncluded($period->starts_on, $period->ends_on))) {
                throw new KnownException('El doctor no está disponible en la fecha seleccionada.');
            }

            if ($this->appointmentRepository->clientHasConfirmedAppointment(
                $client,
                $doctor->id,
                $appointmentDate->toDateString(),
                $appointment->id,
            )) {
                throw new KnownException('Ya tienes una cita pendiente con este doctor para esa fecha.');
            }

            $sameSlot = $appointment->doctor_id === $doctor->id
                && $appointment->appointment_date->isSameDay($appointmentDate);
            $oldValues = [
                'doctor_id' => $appointment->doctor_id,
                'medical_area_id' => $appointment->medical_area_id,
                'appointment_date' => $appointment->appointment_date->toDateString(),
                'turn_number' => $appointment->turn_number,
                'reason' => $appointment->reason,
            ];

            $appointment = $this->appointmentRepository->update($appointment, [
                'doctor_id' => $doctor->id,
                'medical_area_id' => $medicalArea->id,
                'appointment_date' => $appointmentDate->toDateString(),
                'turn_number' => $sameSlot
                    ? $appointment->turn_number
                    : $this->appointmentRepository->nextTurnNumber($doctor->id, $appointmentDate->toDateString()),
                'reason' => $data['reason'] ?? null,
            ]);

            $this->appointmentRepository->recordRescheduledHistory($appointment, $client, $oldValues);

            return $this->appointmentRepository->loadCreationRelations($appointment);
        });
    }

    private function dayOfWeek(Carbon $date): DayOfWeekEnum
    {
        return match ($date->dayOfWeekIso) {
            1 => DayOfWeekEnum::Monday,
            2 => DayOfWeekEnum::Tuesday,
            3 => DayOfWeekEnum::Wednesday,
            4 => DayOfWeekEnum::Thursday,
            5 => DayOfWeekEnum::Friday,
            6 => DayOfWeekEnum::Saturday,
            7 => DayOfWeekEnum::Sunday,
        };
    }
}
