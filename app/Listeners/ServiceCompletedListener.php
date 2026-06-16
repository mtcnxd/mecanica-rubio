<?php

namespace App\Listeners;

use App\Events\ServiceCompletedEvent;
use App\Traits\Notificator;

class ServiceCompletedListener
{
    use Notificator;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     */
    public function handle(ServiceCompletedEvent $event): void
    {
        $phoneNumber = null;

        if (isset($event->service->client->phone)){
            $phoneNumber = $event->service->client->phone;
        }

        if (is_null($phoneNumber)){
            throw new \Exception("Client phone number not found");
        }

        $this->sendNotification('Service completed notification sent: ' . $phoneNumber, 'HTML');
    }
}
