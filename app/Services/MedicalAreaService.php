<?php

namespace App\Services;

use App\Models\MedicalArea;
use App\Repositories\MedicalAreaRepository;
use Illuminate\Database\Eloquent\Collection;

class MedicalAreaService
{
    public function __construct(
        private readonly MedicalAreaRepository $medicalAreaRepository,
    ) {}

    /**
     * @return Collection<int, MedicalArea>
     */
    public function getPublicList(string $locale): Collection
    {
        return $this->medicalAreaRepository->getPublicList($locale);
    }
}
