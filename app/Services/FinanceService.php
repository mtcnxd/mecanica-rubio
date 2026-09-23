<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Expense;
use App\Models\Payroll;
use App\Models\Service;
use App\Models\ServiceItems;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Number;
use App\Traits\Notificator;

class FinanceService
{
    use Notificator;

    public function deleteExpenseItem(string $id) : bool
    {
        $expenseItem = Expense::find($id);
        $expenseItem->delete();

        return true;
    }

    public function montlyClosing() : array
    {
        $latestCloseDate = \DB::table('montly_balances')
            ->orderBy('close_date', 'desc')
            ->first();

        $startDate = now()->startOfMonth();

        if (!is_null($latestCloseDate)) {
            $startDate = $latestCloseDate->close_date;
        }

        $services = Service::where('status', 'Entregado')
            ->where('finished_date', '>=', $startDate)
            ->get();

        $expenses = Expense::where('expense_date', '>=', $startDate)->get();

        $payrolls = Payroll::where('paid_date', '>=', $startDate)->get();

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
            sprintf("<b>Cierre de mes exitoso</b>\n\rIngresos: %s\n\rEgresos: %s\n\rSaldo: %s\n\r",
                Number::currency($data['income']),
                Number::currency($data['expense']),
                Number::currency($data['balance'])
            ), "HTML");

        return true;
    }
}