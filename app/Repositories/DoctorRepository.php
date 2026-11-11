<?php

namespace App\Repositories;

use App\Models\Doctor;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class DoctorRepository
{
    /**
     * @return Builder<Doctor>
     */
    protected function publicQuery(): Builder
    {
        $query =  Doctor::query();

        $query->select([
                'id',
                'uuid',
                'user_id',
                'picture',
                'consulting_room',
                'phone_extension',
            ])
            ->with([
                'user:id,first_name,last_name',
                'translations:id,doctor_id,locale,professional_summary',
                'medicalAreas' => fn ($query) => $query
                    ->select(['medical_areas.id', 'slug'])
                    ->where('is_active', true)
                    ->with('translations:id,medical_area_id,locale,name'),
                'schedules' => fn ($query) => $query
                    ->select(['id', 'doctor_id', 'day_of_week'])
                    ->where('is_active', true),
            ])
            ->where('is_active', true)
            ->whereHas('user', fn ($query) => $query->where('is_active', true))
            ->orderBy('id');

        return $query;
    }

    /**
     * @return Collection<int, Doctor>
     */
    public function getPublicDirectory(): Collection
    {
        return $this->publicQuery()->get();
    }

    public function findPublicByUuid(string $uuid): Doctor
    {
        return $this->publicQuery()
            ->where('uuid', $uuid)
            ->firstOrFail();
    }

    /**
     * @return Collection<int, Doctor>
     */
    public function getAppointmentDirectory(): Collection
    {
        return Doctor::query()
            ->select([
                'id',
                'uuid',
                'user_id',
                'picture',
                'consulting_room',
            ])
            ->with([
                'user:id,first_name,last_name',
                'medicalAreas' => fn ($query) => $query
                    ->select(['medical_areas.id', 'slug'])
                    ->where('is_active', true)
                    ->with('translations:id,medical_area_id,locale,name'),
                'schedules' => fn ($query) => $query
                    ->select(['id', 'doctor_id', 'day_of_week', 'start_time', 'end_time'])
                    ->where('is_active', true)
                    ->orderBy('start_time'),
                'unavailabilities:id,doctor_id,starts_on,ends_on',
            ])
            ->where('is_active', true)
            ->whereHas('user', fn ($query) => $query->where('is_active', true))
            ->whereHas('medicalAreas', fn ($query) => $query->where('is_active', true))
            ->whereHas('schedules', fn ($query) => $query->where('is_active', true))
            ->orderBy('id')
            ->get();
    }

    public function findActiveForAppointment(string $uuid): ?Doctor
    {
        return Doctor::query()
            ->with([
                'user:id,first_name,last_name',
                'medicalAreas' => fn ($query) => $query
                    ->where('is_active', true),
                'schedules' => fn ($query) => $query
                    ->where('is_active', true),
                'unavailabilities',
            ])
            ->where('uuid', $uuid)
            ->where('is_active', true)
            ->whereHas('user', fn ($query) => $query->where('is_active', true))
            ->lockForUpdate()
            ->first();
    }
}
