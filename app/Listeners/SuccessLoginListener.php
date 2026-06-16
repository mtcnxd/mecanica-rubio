<?php

namespace App\Listeners;

use App\Events\SuccessLoginEvent;

class SuccessLoginListener
{
    /**
     * Handle the event.
     */
    public function handle(SuccessLoginEvent $event): void
    {
        $event->user->update([
            'last_login_at' => now(),
        ]);
    }
}
