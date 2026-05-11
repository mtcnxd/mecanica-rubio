<?php

namespace App\Services;

use App\Events\PayrollCompletedEvent;
use App\Models\Expense;
use App\Models\Payroll;
use App\Models\Service;

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
}