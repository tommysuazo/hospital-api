<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Resources\PublicMedicalProcedureResource;
use App\Http\Resources\PublicMedicalSpecialtyResource;
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
            'specialties' => PublicMedicalSpecialtyResource::collection(
                $data['specialties']
            )->resolve($request),
            'procedures' => PublicMedicalProcedureResource::collection(
                $data['procedures']
            )->resolve($request),
        ]);
    }

    public function about(): Response
    {
        return Inertia::render('Public/About');
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
