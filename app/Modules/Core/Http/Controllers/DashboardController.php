<?php

namespace App\Modules\Core\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Core\Services\DashboardWidgetService;

class DashboardController extends Controller
{
    public function __construct(
        protected DashboardWidgetService $widgetService
    ) {}

    public function index()
    {
        $widgets = $this->widgetService->buildDashboardForUser(auth()->user());

        return view('modules.core.dashboard.index', compact('widgets'));
    }
}
