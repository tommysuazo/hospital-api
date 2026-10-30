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
                'medicalSpecialties' => fn ($query) => $query
                    ->select(['medical_specialties.id', 'slug'])
                    ->where('is_active', true)
                    ->with('translations:id,medical_specialty_id,locale,name'),
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
}
