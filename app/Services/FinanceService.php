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

    public function updateExpense(array $data, string $id) : Expense
    {
        $expense = Expense::find($id);

        $expense->category     = $data['category'];
        $expense->expense_date = $data['expense_date'];
        $expense->status       = $data['status'];

        if (isset($data['attach'])) {
            $expense->attach = $data['attach'];
        }

        $expense->save();

        return $expense;
    }

    public function storeExpense(array $data): Expense
    {
        return Expense::create([
            'name'         => $data['name'],
            'category'     => $data['category'],
            'description'  => $data['description'] ?? null,
            'status'       => $data['status'],
            'amount'       => $data['amount'],
            'price'        => $data['price'],
            'employee_id'  => $data['employee_id'],
            'attach'       => $data['attach'] ?? null,
            'expense_date' => now()
        ]);
    }

    public function getExpense(string $id): Expense
    {
        $expense = Expense::find($id);

        if (is_null($expense)) {
            throw new \Exception("NO SE ENCONTRO EL GASTO CON ID: {$id}");
        }

        return $expense;
    }

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
            $startDate = Carbon::parse($latestCloseDate->close_date);
        }

        $services = Service::where('status', 'Entregado')
            ->where('finished_date', '>=', $startDate)
            ->get();

        $expenses = Expense::where('expense_date', '>=', $startDate)->get();

        $payrolls = Payroll::where('paid_date', '>=', $startDate)->get();

        return [
            'closeDate' => Carbon::parse($latestCloseDate->close_date)->format('d/m/Y h:i A'),
            'services'  => $services, 
            'expenses'  => $expenses, 
            'payrolls'  => $payrolls,
            'balance'   => $latestCloseDate->balance ?? 0,
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