<?php

namespace App\Services;

use App\Models\Doctor;
use App\Repositories\DoctorRepository;
use App\Repositories\MedicalProcedureRepository;
use App\Repositories\MedicalSpecialtyRepository;
use Illuminate\Database\Eloquent\Collection;

class PublicSiteService
{
    public function __construct(
        private readonly DoctorRepository $doctorRepository,
        private readonly MedicalSpecialtyRepository $medicalSpecialtyRepository,
        private readonly MedicalProcedureRepository $medicalProcedureRepository,
    ) {}

    /**
     * @return array{
     *     specialties: Collection,
     *     procedures: Collection,
     * }
     */
    public function getHomeData(): array
    {
        return [
            'specialties' => $this->medicalSpecialtyRepository->getPublicTranslatedList(),
            'procedures' => $this->medicalProcedureRepository->getPublicList(),
        ];
    }

    /**
     * @return array{
     *     doctors: Collection,
     *     specialties: Collection,
     * }
     */
    public function getDoctorDirectoryData(): array
    {
        return [
            'doctors' => $this->doctorRepository->getPublicDirectory(),
            'specialties' => $this->medicalSpecialtyRepository->getPublicTranslatedList(),
        ];
    }

    public function getDoctor(string $uuid): Doctor
    {
        return $this->doctorRepository->findPublicByUuid($uuid);
    }
}
