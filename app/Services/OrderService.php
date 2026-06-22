<?php

namespace App\Services;

use App\Models\Service;
use App\Models\ServiceItems;
use App\Models\Calendar;
use App\Events\ServiceCompletedEvent;
use Illuminate\Support\Number;
use App\Traits\Notificator;
use PDF;

class OrderService
{
    use Notificator;

    public function all()
    {
        return Service::whereBetween('created_at', [now()->subMonths(4), now()->endOfMonth()])
            ->whereNotIn('status',['Cancelado'])
            ->where('quote', false)
            ->get();
    }

    public function find(string $id): ?Service
    {
        return Service::find($id);
    }

    public function createOrder(array $data): Service
    {
        $isQuote = false;

        if (isset($data['quote']) && $data['quote'] == 'quote') {
            $isQuote = true;
        }

        $data['entry_date'] = now();
        $data['quote'] = $isQuote;

        $service = Service::create($data);

        if (!$isQuote) {
            $this->sendNotification(
                sprintf("<b>Service created:</b><u>%s</u>\n<b>Client:</b><u>%s</u>\n<b>Car model:</b><u>%s</u>\n<b>Fault:</b>%s\n<a href='https://mecanicarubio.com/admin/service/%s'>Ver Detalles</a>",
                    $service->id,
                    $service->client->name,
                    $service->car->fullName,
                    $service->fault,
                    $service->id
                ), "HTML"
            );
        }

        return $service;
    }

    public function markAsCompleted(string $id)
    {
        $service = Service::find($id);
        
        $service->update([
            'status' => 'Entregado',
            'finished_date' => now(),
        ]);
        
        ServiceCompletedEvent::dispatch($service);

        $this->sendNotification(
            sprintf("<b>Service completed:</b> <u>%s</u>\n<b>Car:</b> <u>%s</u>\n<b>Client:</b> <u>%s</u>\n<b>Fault:</b> %s", 
                $service->id,
                $service->car->fullName,
                $service->client->name,
                $service->fault
        ), 'HTML'
        );
    }

    public function createOrderItem(array $request): ?ServiceItems
    {
        $amount = $request['amount'];
        $item = $request['item'];

        if ($request['labour'] == true) {
            $amount = 1;
            $item = 'Servicio (mano de obra)';
        }

        return ServiceItems::updateOrCreate([
            'service_id' => $request['service'],
            'item'       => $item,
        ], [
            'service_id' => $request['service'],
            'item'       => $item,
            'amount'     => $amount,
            'supplier'   => $request['supplier'],
            'price'      => $request['price'],
            'labour'     => $request['labour'],
        ]);
    }

    public function deleteOrderItem(string $id)
    {
        return ServiceItems::destroy($id);
    }

    /*
    public function findByCriteria(array $criteria)
    {
        return Service::select('client_id', 'car_id', 'service_type', 'fault', 'status', 'entry_date', 'finished_date', 'total')
            ->with('client:id,name,email,phone')
            ->with('car:id,brand,model,year')
            ->where(function ($query) use ($criteria) {
                if (isset($criteria['status'])) {
                    $query->where('status', $criteria['status']);
                }
                if (isset($criteria['id'])) {
                    $query->where('id', $criteria['id']);
                }
            })->get();
    }
    */

    public function servicesSummary()
    {
        return Service::select('id', 'service_type', 'fault', 'status', 'entry_date', 'finished_date', 'total')
            ->whereBetween(
                'entry_date', [now()->startOfMonth(), now()->endOfMonth()],
            )
            ->where('quote', false)
            ->get();
    }

    public function createPDF(string $id)
    {
        $service = Service::find($id);

        $path = public_path('images/mainlogo.png');
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $image = file_get_contents($path);
        $base64 = 'data:image/'.$type.';base64,'.base64_encode($image);

        $pdf = PDF::loadView('admin.templates.pdf_invoice', [
            'title' => 'COTIZACION',
            'service' => $service,
            'image' => $base64,
        ]);

        return $pdf->download('invoice.pdf');
    }

    public function createCalendarEvent(Service $service) : Calendar
    {
        $calendarEvent = Calendar::firstOrCreate([
            'client_id' => $service->client_id,
            'car_id'    => $service->car_id
        ],[
            'name'          => 'Mantenimiento programado',
            'description'   => 'Mantenimiento programado',
            'client_id'     => $service->client_id,
            'car_id'        => $service->car_id,
            'event_date'    => now()->addDays(5),
        ]);

        return $calendarEvent;
    }

    public function getScheduledEvents()
    {
        return Calendar::whereBetween('event_date', [now(), now()->addDays(15)])->get();
    }
}
