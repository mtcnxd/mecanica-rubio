<?php

namespace App\Services;

use App\Models\Payroll;
use App\Models\PayrollItems;
use App\Events\PayrollCompletedEvent;

class PayrollsService 
{
    public function getFormDataCreatePayroll()
    {
        /* We plus one because current salarie is still not saved */

        $id    = Payroll::max('id') + 1;
        $items = PayrollItems::where('salary_id', $id)->get();

        return $items;
    }

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

    public function markAsPaid(string $id, array $data) : bool
    {
        $payroll = Payroll::find($id);
        
        $payroll->update([
            'status' => $data['action'],
            'paid_date' => now(),
        ]);

        event(new PayrollCompletedEvent($payroll));
        
        return true;
    }
}