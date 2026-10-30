<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Resources\PublicDoctorResource;
use App\Http\Resources\PublicMedicalSpecialtyResource;
use App\Services\PublicSiteService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DoctorController extends Controller
{
    public function __construct(
        private readonly PublicSiteService $publicSiteService,
    ) {}

    public function index(Request $request): Response
    {
        $data = $this->publicSiteService->getDoctorDirectoryData();

        return Inertia::render('Public/Doctors/Index', [
            'doctors' => PublicDoctorResource::collection(
                $data['doctors']
            )->resolve($request),
            'specialties' => PublicMedicalSpecialtyResource::collection(
                $data['specialties']
            )->resolve($request),
        ]);
    }

    public function show(Request $request, string $doctor): Response
    {
        return Inertia::render('Public/Doctors/Show', [
            'doctor' => PublicDoctorResource::make(
                $this->publicSiteService->getDoctor($doctor)
            )->resolve($request),
        ]);
    }
}
