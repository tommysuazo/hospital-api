<?php

namespace App\Services;

use App\Models\MedicalSpecialty;
use App\Repositories\MedicalSpecialtyRepository;
use Illuminate\Database\Eloquent\Collection;

class MedicalSpecialtyService
{
    public function __construct(
        private readonly MedicalSpecialtyRepository $medicalSpecialtyRepository,
    ) {}

    /**
     * @return Collection<int, MedicalSpecialty>
     */
    public function getPublicList(string $locale): Collection
    {
        return $this->medicalSpecialtyRepository->getPublicList($locale);
    }
}
