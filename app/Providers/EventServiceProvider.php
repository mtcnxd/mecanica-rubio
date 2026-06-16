<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

use App\Events\PayrollCompletedEvent;
use App\Events\ServiceCompletedEvent;
use App\Events\SuccessLoginEvent;
use App\Listeners\PayrollCompletedListener;
use App\Listeners\ServiceCompletedListener;
use App\Listeners\SuccessLoginListener;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

        SuccessLoginEvent::class => [
            SuccessLoginListener::class,
        ],  

        PayrollCompletedEvent::class => [
            PayrollCompletedListener::class,
        ],

        ServiceCompletedEvent::class => [
            ServiceCompletedListener::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        /*
         * When false, the event are not auto discoverable
         * and the event should be added manually (this is the best)
         */

        return false;
    }
}
