<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Resources\MedicalAreaSummaryResource;
use App\Services\MedicalAreaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Throwable;

class MedicalAreaController extends Controller
{
    public function __construct(
        private readonly MedicalAreaService $medicalAreaService,
    ) {}

    public function getPublicList(Request $request): AnonymousResourceCollection|JsonResponse
    {
        try {
            $medicalAreas = $this->medicalAreaService->getPublicList(
                $request->getLocale()
            );

            return MedicalAreaSummaryResource::collection($medicalAreas);
        } catch (Throwable $exception) {
            report($exception);

            return response()->json([
                'message' => 'An unexpected error occurred.',
            ], 500);
        }
    }
}
