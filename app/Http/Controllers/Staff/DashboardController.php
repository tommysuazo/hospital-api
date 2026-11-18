<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Services\StaffDashboardService;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __construct(private readonly StaffDashboardService $dashboardService) {}

    public function index(): Response
    {
        return Inertia::render('Staff/Dashboard', $this->dashboardService->getDashboardData());
    }
}
