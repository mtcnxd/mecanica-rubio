<?php

namespace App\Console\Commands;

use App\Models\Calendar;
use App\Models\Service;
use App\Services\OrderService;
use App\Traits\Notificator;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class createCalendarEvent extends Command
{
    use Notificator;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-calendar-event';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $orderService = new OrderService();

        try {
            $scheduledEvents = $orderService->getScheduledEvents();

            foreach ($scheduledEvents as $scheduledEvent) {
                // TODO: Implement first and second notifications by days in settings

                if ($scheduledEvent->notified == 0){
                    $this->sendNotification(
                        sprintf("<b>First alert for scheduled event:</b><u>%s</u>\n<b>Client:</b><u>%s</u>\n<b>Car:</b><u>%s</u>",
                        $scheduledEvent->id,
                        $scheduledEvent->client->name,
                        $scheduledEvent->car->fullName, "HTML")
                    );
                    $scheduledEvent->notified = 1;
                
                } else if ($scheduledEvent->notified == 1){
                    $this->sendNotification(
                        sprintf("<b>Second alert for scheduled event:</b><u>%s</u>\n<b>Client:</b><u>%s</u>\n<b>Car:</b><u>%s</u>",
                        $scheduledEvent->id,
                        $scheduledEvent->client->name,
                        $scheduledEvent->car->fullName, "HTML")
                    );
                    $scheduledEvent->notified = 2;
                }

                $scheduledEvent->save();
            }

            $services = Service::where('entry_date','>', now()->subDays(10))
                ->whereIn('service_type',['Mayor','Menor'])
                ->get();

            $this->sendNotification(
                sprintf('*Services found for calendarization:* __%s__', $services->count())
            );

            foreach ($services as $service){
                $calendarEvent = $orderService->createCalendarEvent($service);
                
                Log::info('Calendar event created: ' . $calendarEvent);
            }

        } catch (\Exception $e){
            Log::error('Error while creating calendar events | Error: ' . $e->getMessage());
            
            $this->sendNotification(
                sprintf('Error while creating calendar events | Error: %s', $e->getMessage())
            );
        }
    }
}
