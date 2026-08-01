<?php

namespace App\Http\Controllers\Dashboard\Reports;

use App\Http\Controllers\Controller;

class ReportController extends Controller
{
    public function occupancy()
    {
        return view('dashboard.reports.occupancy-report');
    }

    public function revenue()
    {
        return view('dashboard.reports.revenue-report');
    }

    public function guests()
    {
        return view('dashboard.reports.guest-report');
    }

    public function dailyCollection()
    {
        return view('dashboard.reports.daily-collection');
    }

    public function inventory()
    {
        return view('dashboard.reports.inventory-report');
    }
}