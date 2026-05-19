<?php

namespace App\Services;

use App\Models\Service;
use App\Models\Payroll;
use App\Models\Expense;
use App\Models\Investment;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ChartService
{
    protected $lastCutDate;

    protected static $months = [
        'En', 'Feb', 'Mar',
        'Ab', 'May', 'Jun',
        'Jul', 'Ago', 'Sep',
        'Oct', 'Nov', 'Dic'
    ];

    public function __construct()
    {
        $this->lastCutDate = $this->getLastCutDate();
    }

    private function getLastCutDate()
    {
        $lastDate = DB::table('montly_balances')->orderBy('created_at','desc')->first();

        if ($lastDate){
            return Carbon::parse($lastDate->close_date);
        }

        return now()->subMonth();
    }

    public function servicesCompletedThisMonth()
    {
        $carsCompleted = [];
        $carsCompleted = Service::where('status','Entregado')
            ->whereBetween('finished_date', [now()->startOfMonth(), now()])
            ->get();

        return $carsCompleted;
    }

    public function labourThisMonth()
    {
        $servicesCompleted = Service::where('status','Entregado')
            ->whereBetween('finished_date', [now()->startOfMonth(), now()])
            ->get()
            ->sum(function($services){
                return $services->serviceItems->where('labour', true)->sum('price');
            });

        return $servicesCompleted;
    }

    public function expensesThisMonth()
    {
        $expenses = Expense::whereBetween('expense_date', [now()->startOfMonth(), now()])
            ->get()
            ->sum(function($expense){
                return ($expense->price * $expense->amount);
            });
        
        return $expenses;
    }

    public function getTotalCurrentMonth()
    {    
        $lastBalance = Payroll::where('paid_date','>', $this->lastCutDate)
            ->get();
        
        if ($lastBalance){
            return $lastBalance->sum('total');
        }
        
        return 0.0;
    }

    public static function convertToPercentage(float $first, float $second) : float
    {
        $difference = ($first - $second);
        if ($first != 0){
            return ($difference / $first) * 100;
        }
        return 0;
    }

    /** Charts */

    public function chartServicesByMonth()
    {
        $services = Service::select(
                Service::raw("DATE_FORMAT(entry_date, '%M') as month"), 
                Service::raw("COUNT(*) as services")
            )
            ->groupBy(Service::raw("DATE_FORMAT(entry_date, '%M')"))
            ->get();

        return [
            'labels' => $services->pluck('month'),
            'values' => $services->pluck('services'),
        ];
    }

    public function chartIncomeByMonth()
    {
        $services = Service::join('services_items', 'services.id','services_items.service_id')
            ->selectRaw("DATE_FORMAT(services.finished_date, '%M') as month")
            ->selectRaw("SUM(services_items.price) as amount")
            ->where('services.status', 'Entregado')
            ->where('services_items.labour', true)
            ->groupByRaw("DATE_FORMAT(services.finished_date, '%M')")
            ->get();

        return [
            'labels' => $services->pluck('month'),
            'values' => $services->pluck('amount'),
        ];
    }








    public function chartAssetsIncrement()
    {
        return DB::table('assets_increment_chart')
            ->select('export_date', DB::raw('SUM(amount) as total'))
            ->groupBy('export_date')
            ->get();
    }

    static function getRevenueChart()
    {
        $data = DB::table('chart_assets_increment')
            ->select(DB::raw('sum(amount) as amount, export_date'))
            ->groupBy('export_date')
            ->orderBy('export_date', 'desc')
            ->limit(31)
            ->get();
        
        $labels = [];
        $values = [];

        foreach($data as $value){
            $labels[] = $value->export_date;
            $values[] = $value->amount;
        }

        return [
            'labels' => $labels,
            'values' => $values,
        ];
    }

    static function chartProfitPercentage()
    {
        $investments = Investment::where('active', true)->orderBy('name')->get();

        $labels = [];
        $values = [];

        foreach ($investments as $investment) {
            $labels[] = $investment->name;
            $values[] = $investment->profitPercentage;
        }

        return [
            'labels' => $labels,
            'values' => $values,
        ];
    }    

}