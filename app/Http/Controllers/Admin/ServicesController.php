<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Number;
use App\Services\OrderService;
use App\Services\ClientService;
use App\Traits\Notificator;
use App\Http\Requests\ServiceStoreRequest;
use App\Http\Requests\ServiceUpdateRequest;
use App\Models\Service;
use App\Models\ServiceItems;
use App\Events\ServiceCompleted;
use App\Exceptions\StoreServiceException;
use App\Exceptions\ServiceNotFoundException;

class ServicesController extends Controller
{
    use Notificator;

    public function __construct(
        private OrderService $orderService,
        private ClientService $clientService
    ){ }

    public function index()
    {
        $services = [];

        if ($_GET) {
            $services = $this->orderService->findByCriteria($_GET);
        } else {
            $services = $this->orderService->all();
        }

        return view('admin.services.index', compact('services'));
    }

    public function create()
    {
        $clients = $this->clientService->all();

        return view('admin.services.create', compact('clients'));
    }

    public function store(ServiceStoreRequest $request)
    {
        try {
            $service = $this->orderService->createOrder($request->validated());

            if ($service->quote == false) {
                $this->sendNotification(
                    sprintf("<b>✅ New service order:</b> <u>%s</u>\r
                             <b>Client:</b> <u>%s</u>\r
                             <b>Car model:</b> <u>%s</u>\r
                             <b>Fault:</b> <u>%s</u>\r
                             <a href='https://mecanicarubio.com/admin/service/%s'>View Details</a>",
                                $service->id,
                                $service->client->name,
                                $service->car->fullName,
                                $service->fault,
                                $service->id
                            ),
                            "HTML"
                );
            }

            session()->flash('success', "Servicio creado correctamente | Folio: #{$service->id}");

        } catch(StoreServiceException $err){
            session()->flash('error', $err->getMessage());
        }

        return to_route('admin.service.index');
    }

    public function show(string $id)
    {
        $service = $this->orderService->find($id);

        return view('admin.services.show', compact('service'));
    }

    public function update(ServiceUpdateRequest $request, string $id)
    {
        try {
            $service = $this->orderService->updateOrderService($request->validated(), $id);

            if ($service->status == 'Entregado'){
                $this->sendNotification(
                    sprintf("<b>Service completed:</b> #%s\r
                            <b>Car:</b> %s\r
                            <b>Client:</b> %s\r
                            <b>Fault:</b> %s\r
                            <b>Total:</b> %s", 
                            $service->id,
                            $service->car->fullName,
                            $service->client->name,
                            $service->fault, 
                            Number::currency($service->total)
                    ),
                    "HTML"
                );
            }

            session()->flash('success', 'Servicio actualizado correctamente');
        
        } catch(\Exception $err){
            session()->flash('error', $err->getMessage());
        }

        return view('admin.services.show', compact('service'));
    }
}
