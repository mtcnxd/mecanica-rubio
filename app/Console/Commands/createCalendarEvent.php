<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Service;
use App\Models\Calendar;
use Illuminate\Console\Command;
use App\Http\Controllers\Notifications\Telegram;

class createCalendarEvent extends Command
{
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
    public function handle(Telegram $telegram)
    {
        $services = Service::where('created_at','>', Carbon::now()->subDays(10))
            ->whereIn('service_type',['Mayor','Menor'])
            ->get();

        $telegram->send(
            sprintf("Process started at: %s Services found: %s", Carbon::now(), $services->count())
        );

        try {
            $servicesCreatedCounter = 0;
            foreach ($services as $service){
                if ($this->createCalendarEvent($service, $telegram)){
                    $servicesCreatedCounter +1;
                }
            }
    
            if ($servicesCreatedCounter > 0){
                $telegram->send(
                    sprintf('New %s service scheduled created successfully', $servicesCreatedCounter)
                );
            }
        }

        catch (\Exception $e){
            $telegram->send(
                sprintf('Error while creating calendar events | Error: %s', $e->getMessage())
            );
        }

        /*
        $events = Calendar::where('status', 'Pendiente')->where('notified', false);

        $events->each(function ($event){
            echo sprintf(
                '(%s) => Hola %s, te recordamos que tu %s ya requiere mantenimiento. Nuestro mecanico se pondra en contacto contigo pronto'.PHP_EOL ,
                $event->client->phone,
                $event->client->name,
                $event->service->car->carName()
            );
        });
        */
    }

    protected function createCalendarEvent($service, $telegram) : bool
    {
        $eventFound = Calendar::where('client_id', $service->client_id)
            ->where('car_id', $service->car_id)
            ->first();
        
        $telegram->send(
            sprintf("Events found while creating event: \n\rClient: %s \n\rCar: %s", $service->client_id, $service->car_id)
        );

        if (!$eventFound){
            Calendar::create([
                'name'          => 'Mantenimiento programado',
                'description'   => 'Mantenimiento programado',
                'client_id'     => $service->client_id,
                'car_id'        => $service->car_id,
                'event_date'    => Carbon::parse($service->finished_date)->addMonths(5)
            ]);

            return true;
        }

        return false;
    }
}
