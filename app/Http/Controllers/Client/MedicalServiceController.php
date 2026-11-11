<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Resources\PublicMedicalServiceCategoryResource;
use App\Http\Resources\PublicMedicalServiceResource;
use App\Services\PublicSiteService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class MedicalServiceController extends Controller
{
    public function __construct(
        private readonly PublicSiteService $publicSiteService,
    ) {}

    public function index(Request $request): Response
    {
        return Inertia::render('Client/Services/Index', [
            'medicalServiceCategories' => PublicMedicalServiceCategoryResource::collection(
                $this->publicSiteService->getMedicalServiceCategories()
            )->resolve($request),
        ]);
    }

    public function show(Request $request, string $medicalService): Response
    {
        return Inertia::render('Client/Services/Show', [
            'medicalService' => PublicMedicalServiceResource::make(
                $this->publicSiteService->getMedicalService($medicalService)
            )->resolve($request),
        ]);
    }
}
