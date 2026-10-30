<?php

namespace App\Services;

use App\Models\Doctor;
use App\Repositories\DoctorRepository;
use App\Repositories\MedicalProcedureRepository;
use App\Repositories\MedicalAreaRepository;
use Illuminate\Database\Eloquent\Collection;

class PublicSiteService
{
    public function __construct(
        private readonly DoctorRepository $doctorRepository,
        private readonly MedicalAreaRepository $medicalAreaRepository,
        private readonly MedicalProcedureRepository $medicalProcedureRepository,
    ) {}

    /**
     * @return array{
     *     medicalAreas: Collection,
     *     procedures: Collection,
     * }
     */
    public function getHomeData(): array
    {
        return [
            'medicalAreas' => $this->medicalAreaRepository->getPublicTranslatedList(),
            'procedures' => $this->medicalProcedureRepository->getPublicList(),
        ];
    }

    /**
     * @return array{
     *     doctors: Collection,
     *     medicalAreas: Collection,
     * }
     */
    public function getDoctorDirectoryData(): array
    {
        return [
            'doctors' => $this->doctorRepository->getPublicDirectory(),
            'medicalAreas' => $this->medicalAreaRepository->getPublicTranslatedList(),
        ];
    }

    public function getDoctor(string $uuid): Doctor
    {
        return $this->doctorRepository->findPublicByUuid($uuid);
    }
}
