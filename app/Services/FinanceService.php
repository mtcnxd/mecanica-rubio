<?php

namespace App\Services;

use App\Models\Expense;
use App\Models\Payroll;
use App\Models\Service;

class FinanceService
{
    public function updatePayroll(string $id, array $data)
    {
        $payroll = Payroll::find($id);
        $payroll->update([
            'status' => $data['action'],
            'paid_date' => now(),
        ]);

        return true;
    }
}