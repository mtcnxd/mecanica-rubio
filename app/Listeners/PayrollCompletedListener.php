<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use App\Traits\Notificator;

class PayrollCompletedListener
{
    use Notificator;

    /**
     * Handle the event.
     */
    public function handle(PayrollCompletedEvent $payrollCompletedEvent): void
    {
        $employeeEmail = $payrollCompletedEvent->payroll->employee->email;
        
        Mail::to($employeeEmail)->send(new PayrollCompletedMail($payrollCompletedEvent->payroll));
        
        Log::info("Nomina pagada - ". json_encode($payrollCompletedEvent->payroll));

        $this->sendNotification("Payroll notification sent successfully to: ". $payroll->employee->email, 'HTML');
    }
}
