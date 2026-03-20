<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Charts;
use App\Models\Expense;
use App\Models\Payroll;
use App\Models\Service;

class Dashboard extends Controller
{
    public function index (
        Expense $expense, 
        Payroll $payroll,
        Charts $charts,
        Service $service
    ){
        $data['income'] = $charts->chartCarsReleaseThisMonth()->sum(function ($service) {
            return $service->serviceItems->where('labour', true)->sum('price');
        });

        $data['avg'] = $service->select(Service::raw('AVG(DATEDIFF(finished_date, entry_date)) as avg'))
            ->where('created_at', '>', now()->subMonths(6))
            ->where('status', 'Entregado')
            ->first()->avg;

        return view('admin.reports.dashboard', compact('expense','payroll','charts','data'));
    }
}
