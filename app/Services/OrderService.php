<?php

namespace App\Services;

use PDF;
use App\Models\Service;
use App\Models\ServiceItems;
use App\Traits\Messenger;

class OrderService
{
    use Messenger;

    public function all()
    {
        return Service::whereBetween('created_at', [now()->subMonths(3), now()->endOfMonth()])
            ->where('quote', false)->get();
    }

    public function find(string $id) : Service
    {
        return Service::findOrFail($id);
    }

    public function createOrder(array $data) : Service
    {
        $isQuote = false;

        if (isset($data['quote']) && $data['quote'] == 'quote'){
            $isQuote = true;
        }

        $data['entry_date'] = now();
        $data['quote']      = $isQuote;
        
        $service = Service::create($data);

        if (!$isQuote){
            $this->telegram(
                sprintf("<b>Service created:</b> #%s\n\r<b>Client:</b> %s\n\r<b>Car model:</b> %s\n\r<b>Fault:</b> %s", 
                    $service->id,
                    $service->client->name,
                    $service->car->carName(),
                    $service->fault
                )
            );
        }

        return $service;
    }

    public function createOrderItem(array $request) : ?ServiceItems
    {
        $amount = $request['amount'];
        $item   = $request['item'];

        if ($request['labour'] == true){
            $amount = 1;
            $item   = "Servicio (mano de obra)";
        }

        return ServiceItems::create([
            'service_id' => $request['service'],
            'amount'     => $amount,
            'item'       => $item,
            'supplier'   => $request['supplier'],
            'price'      => $request['price'],
            'labour'     => $request['labour'],
        ]);
    }

    public function deleteOrderItem(string $id)
    {
        return ServiceItems::destroy($id);
    }

    public function findByCriteria(array $criteria)
    {
        return Service::select('client_id','car_id','service_type','fault','status','entry_date','finished_date','total')
            ->with('client:id,name,email,phone')
            ->with('car:id,brand,model,year')
            ->where(function($query) use ($criteria) {
                if (isset($criteria['status'])){
                    $query->where('status', $criteria['status']);
                }
                if(isset($criteria['id'])){
                    $query->where('id', $criteria['id']);
                }
            })->get();
    }

    public function servicesThisMonth()
    {
        return Service::select(
            'id',
            //'client_id',
            //'car_id',
            'service_type',
            'fault',
            'status',
            'entry_date',
            'finished_date',
            'total')
            ->whereBetween(
                'created_at', [now()->startOfMonth(), now()->endOfMonth()], 
            )
            //->with('client:id,name,email,phone')
            //->with('car:id,brand,model,year')
            //->with('serviceItems:id,service_id,item,price,amount')
            ->where('quote', false)
            ->get();
    }

    public function servicesSummary()
    {
        return Service::select('id','service_type','fault','status','entry_date','finished_date','total')
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
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($image);

        $pdf = PDF::loadView('admin.templates.pdf_invoice', [
            "title"   => 'COTIZACION',
            "service" => $service,
            "image"   => $base64,
        ]);

        return $pdf->download('invoice.pdf');
    }
}