<?php

namespace App\Services;

use App\Events\PayrollCompletedEvent;
use Carbon\Carbon;
use App\Models\Expense;
use App\Models\Payroll;
use App\Models\Service;
use App\Models\ServiceItems;

class FinanceService
{
    public function updatePayroll(string $id, array $data) : bool
    {
        try {
            $payroll = Payroll::find($id);
            $payroll->update([
                'status' => $data['action'],
                'paid_date' => now(),
            ]);
    
            event(new PayrollCompletedEvent($payroll));
            
            return true;
        
        } catch (\Exception $e) {
            throw new Exception("Error ocurred: ". $e->getMessage());
        }

    }

    public function deleteExpenseItem(string $id) : bool
    {
        $expenseItem = Expense::find($id);
        $expenseItem->delete();

        return true;
    }

    public function montlyClosing() : array
    {
        $startDate = now()->startOfMonth();

        $latestCloseDate = \DB::table('montly_balances')
            ->orderBy('close_date', 'desc')
            ->first();

        if (!is_null($latestCloseDate)) {
            $startDate = $latestCloseDate->close_date;
        }

        $services = Service::whereBetween('finished_date', [$startDate, now()])
            ->whereHas('serviceItems', function ($query){
                $query->where('labour', true);
            })
            ->with(['serviceItems' => function ($query) {
                $query->where('labour', true);
            }])
            ->get();

        $expenses = Expense::whereBetween('expense_date', [$startDate, now()])->get();

        $payrolls = Payroll::whereBetween('paid_date', [$startDate, now()])->get();

        return [
            'services' => $services, 
            'expenses' => $expenses, 
            'payrolls' => $payrolls,
            'balance' => $latestCloseDate->balance ?? 0,
        ];
    }
}