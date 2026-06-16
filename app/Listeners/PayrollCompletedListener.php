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
    public function handle(object $event): void
    {
        Mail::to($this->payroll->employee->email)->send(new PayrollCompletedMail($this->payroll));

        $this->sendNotification('Payroll email successfully sent to: ' . $payroll->employee->email, 'HTML');
        
        Log::info("Nomina pagada - ". json_encode($event));
    }
}
