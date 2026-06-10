<?php

namespace App\Services;

use App\Models\Payroll;
use App\Models\PayrollItems;

class PayrollsService 
{
    public function getCurrentMonth()
    {
        $startDate = now()->subMonths(2)->startofMonth()->format('Y-m-d');
        $endDate   = now()->addDay()->format('Y-m-d');

        $data = Payroll::whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->get();

        return [
            'startDate' => $startDate,
            'endDate' => $endDate,
            'data' => $data,
        ];
    }

    public function createPayroll(array $data)
    {
        $data['employee_id'] = $data['employee'];
        return Payroll::create($data);
    }

    public function createItem(array $data)
    {
        $id = Payroll::max('id') +1;

        PayrollItems::updateOrCreate([
            'salary_id'   => $id,
            'concept'     => $data['concept'],
        ],[
            'salary_id'   => $id,
            'concept'     => $data['concept'],
            'amount'      => $data['amount'],
            'employee_id' => $data['employee']
        ]);

        return true;
    }

    public function destroyItem(string $id)
    {
        return PayrollItems::find($id)->delete();
    }

    public function getPayrollItems(Int $id)
    {
        return PayrollItems::where('salary_id', $id)->get();
    }
}