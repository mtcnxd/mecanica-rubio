<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\PayrollCompletedEvent;
use Illuminate\Support\Facades\Mail;
use App\Mail\PayrollCompletedMail;
use Illuminate\Support\Facades\Log;
use App\Traits\Notificator;

class PayrollCompletedListener
{
    use Notificator;

    /**
     * Handle the event.
     */
    public function handle(PayrollCompletedEvent $event): void
    {
        $employeeEmail = $event->payroll->employee->email;
        
        Mail::to($employeeEmail)->send(new PayrollCompletedMail($event->payroll));
        
        Log::info("Nomina pagada - ". json_encode($event->payroll));

        $this->sendNotification("Payroll notification sent successfully to: ". $employeeEmail, 'HTML');
    }
}
