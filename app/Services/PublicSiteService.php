<?php

namespace App\Services;

use App\Models\Doctor;
use App\Models\MedicalService;
use App\Repositories\DoctorRepository;
use App\Repositories\MedicalAreaRepository;
use App\Repositories\MedicalServiceCategoryRepository;
use App\Repositories\MedicalServiceRepository;
use Illuminate\Database\Eloquent\Collection;

class PublicSiteService
{
    public function __construct(
        private readonly DoctorRepository $doctorRepository,
        private readonly MedicalAreaRepository $medicalAreaRepository,
        private readonly MedicalServiceCategoryRepository $medicalServiceCategoryRepository,
        private readonly MedicalServiceRepository $medicalServiceRepository,
    ) {}

    /**
     * @return array{
     *     medicalAreas: Collection,
     *     medicalServices: Collection,
     * }
     */
    public function getHomeData(): array
    {
        return [
            'medicalAreas' => $this->medicalAreaRepository->getPublicTranslatedList(),
            'medicalServices' => $this->medicalServiceRepository->getPublicList(),
        ];
    }

    public function getMedicalServiceCategories(): Collection
    {
        return $this->medicalServiceCategoryRepository->getPublicList();
    }

    public function getMedicalService(string $slug): MedicalService
    {
        return $this->medicalServiceRepository->findPublicBySlug($slug);
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
