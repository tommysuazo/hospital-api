<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Resources\MedicalSpecialtySummaryResource;
use App\Services\MedicalSpecialtyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Throwable;

class MedicalSpecialtyController extends Controller
{
    public function __construct(
        private readonly MedicalSpecialtyService $medicalSpecialtyService,
    ) {}

    public function getPublicList(Request $request): AnonymousResourceCollection|JsonResponse
    {
        try {
            $medicalSpecialties = $this->medicalSpecialtyService->getPublicList(
                $request->getLocale()
            );

            return MedicalSpecialtySummaryResource::collection($medicalSpecialties);
        } catch (Throwable $exception) {
            report($exception);

            return response()->json([
                'message' => 'An unexpected error occurred.',
            ], 500);
        }
    }
}
