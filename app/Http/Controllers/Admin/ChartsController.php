<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ChartService;
use App\Models\Charts;
use App\Models\Expense;
use App\Models\Payroll;
use App\Models\Service;

class ChartsController extends Controller
{
    public function __construct(
        private ChartService $chartService
    ) {}

    public function index (){

        $charts = $this->chartService;
        
        return view('admin.reports.dashboard', compact('charts'));
    }
}
