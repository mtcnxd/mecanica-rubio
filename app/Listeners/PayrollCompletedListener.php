<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Traits\Messenger;
use Illuminate\Support\Facades\Log;

class PayrollCompletedListener
{
    use Messenger;

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
        Log::info("Nomina pagada - ". json_encode($event));

        $this->telegram("Nomina pagada - {$event->payroll->start_date} a {$event->payroll->end_date}");
    }
}
