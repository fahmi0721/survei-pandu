<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\DashboardService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(DashboardService $service): View
    {
        $statistics = $service->getStatistics();

        return view('admin.dashboard', compact('statistics'));
    }
}