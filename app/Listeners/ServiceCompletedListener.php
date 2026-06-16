<?php

namespace App\Listeners;

use App\Events\ServiceCompletedEvent;
use App\Traits\Notificator;
use Illuminate\Support\Facades\Log;

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
        $phoneNumber = $event->service->client->phone ?? null;

        if (is_null($phoneNumber)){
            Log::info("Client phone number not found for service: " . $event->service->id);

            return;
        }

        try {
            $this->sendNotification('Service completed notification sent: ' . $phoneNumber, 'HTML');

            /**
             * TODO: Send message to whatsapp phone number not implemented yet
             */

        } catch (\Exception $e){
            Log::info("SERVICE COMPLETED LISTENER ERROR | MESSAGE: {$e->getMessage()}");
            
            return;
        }
    }
}
