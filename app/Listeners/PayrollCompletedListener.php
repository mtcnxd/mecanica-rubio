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
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(PayrollCompletedEvent $payrollCompletedEvent): void
    {
        $employeeEmail = $this->payroll->employee->email;
        
        Mail::to($employeeEmail)->send(new PayrollCompletedMail($this->payroll));
        
        Log::info("Nomina pagada - ". json_encode($event));

        $this->sendNotification('Payroll email successfully sent to: ' . $payroll->employee->email, 'HTML');
    }
}
