<?php

namespace App\Services;

use App\Events\PayrollCompletedEvent;
use Carbon\Carbon;
use App\Models\Expense;
use App\Models\Payroll;
use App\Models\Service;
use App\Models\ServiceItems;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Number;
use App\Traits\Messenger;

class FinanceService
{
    use Messenger;

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

    public function storeMonthlyClosing(array $data) : bool
    {
        DB::table('montly_balances')->insert([
            'income' => $data['income'],
            'expenses' => $data['expense'],
            'balance' => $data['balance'],
            'comments' => 'Cierre de mes exitoso',
            'close_date' => now(),
        ]);

        $this->sendNotification(
            sprintf("Cierre de mes completado con exito!\n\rIngresos: %s\n\rEgresos: %s\n\r<b>Saldo: %s</b>", 
                Number::currency($data['income']), 
                Number::currency($data['expense']), 
                Number::currency($data['balance'])
            )
        );

        return true;
    }
}