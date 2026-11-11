<?php

namespace App\Services;

use App\Enums\AppointmentStatusEnum;
use App\Enums\DayOfWeekEnum;
use App\Exceptions\KnownException;
use App\Models\Appointment;
use App\Models\Client;
use App\Repositories\AppointmentRepository;
use App\Repositories\DoctorRepository;
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
     * @return Collection<int, \App\Models\Doctor>
     */
    public function getCreationDoctors(): Collection
    {
        return $this->doctorRepository->getAppointmentDirectory();
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
