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
        if (!$event->payroll->employee?->email){
            Log::info("PAYROLL COMPLETED LISTENER ERROR | MESSAGE: Employee email not found");

            return;
        }

        $employeeEmail = $event->payroll->employee->email;

        try {            
            Mail::to($employeeEmail)
                ->send(new PayrollCompletedMail($event->payroll));
            
            Log::info("Nomina pagada - ". json_encode($event->payroll));
    
            $this->sendNotification("Payroll notification sent successfully to: ". $employeeEmail, 'HTML');

        } catch (\Exception $e){

            Log::error("Failed to send payroll email", [
                'payroll_id' => $event->payroll->id,
                'error' => $e->getMessage()
            ]);

            return;

        }
    }
}
