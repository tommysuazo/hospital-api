<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
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

    public function show(Request $request, string $medicalService): Response
    {
        return Inertia::render('Public/Services/Show', [
            'medicalService' => PublicMedicalServiceResource::make(
                $this->publicSiteService->getMedicalService($medicalService)
            )->resolve($request),
        ]);
    }
}
