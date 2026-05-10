<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Traits\Messenger;
use App\Events\ServiceCompleted;

class SendEmailServiceNotification implements ShouldQueue
{
    use Messenger;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     */
    public function handle(ServiceCompleted $event): void
    {
        $this->telegram(
            sprintf("Hola desde el listener con queue")
        );
    }
}
