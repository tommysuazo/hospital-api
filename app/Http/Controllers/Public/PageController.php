<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Resources\PublicMedicalAreaResource;
use App\Http\Resources\PublicMedicalServiceCategoryResource;
use App\Http\Resources\PublicMedicalServiceResource;
use App\Services\PublicSiteService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PageController extends Controller
{
    public function __construct(
        private readonly PublicSiteService $publicSiteService,
    ) {}

    public function home(Request $request): Response
    {
        $data = $this->publicSiteService->getHomeData();

        return Inertia::render('Public/Home', [
            'medicalAreas' => PublicMedicalAreaResource::collection(
                $data['medicalAreas']
            )->resolve($request),
            'medicalServices' => PublicMedicalServiceResource::collection(
                $data['medicalServices']
            )->resolve($request),
        ]);
    }

    public function about(): Response
    {
        return Inertia::render('Public/About');
    }

    public function services(Request $request): Response
    {
        return Inertia::render('Public/Services/Index', [
            'medicalServiceCategories' => PublicMedicalServiceCategoryResource::collection(
                $this->publicSiteService->getMedicalServiceCategories()
            )->resolve($request),
        ]);
    }

    public function contact(): Response
    {
        return Inertia::render('Public/Contact');
    }

    public function login(): Response
    {
        return Inertia::render('Public/Auth/Portal', ['mode' => 'login']);
    }

    public function register(): Response
    {
        return Inertia::render('Public/Auth/Portal', ['mode' => 'register']);
    }
}
