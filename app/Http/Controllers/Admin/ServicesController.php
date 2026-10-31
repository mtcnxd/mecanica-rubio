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
                            ), "HTML"
                );
            }

            return to_route('admin.service.index')
                ->with('success', "Servicio creado correctamente | Folio: #{$service->id}");

        } catch(StoreServiceException $err){
            return to_route('admin.service.index')
                ->with('warning', "Error al crear servicio | Message: {$err->getMessage()}");
        }
    }

    public function show(string $id)
    {
        $service = $this->orderService->find($id);

        if (!$service){
            throw new ServiceNotFoundException("El servicio folio #{$id}: no fue encontrado");
        }

        return view('admin.services.show', compact('service'));
    }

    public function update(ServiceUpdateRequest $request, string $id)
    {
        try {
            $service = $this->orderService->updateOrderService($request->validated(), $id);

            if ($request->status == 'Entregado'){
                $this->telegram(
                    sprintf("<b>Service completed:</b> #%s\n\r<b>Car model:</b> %s\n\r<b>Client:</b> %s\n\r<b>Fault:</b> %s\n\r<b>Total:</b> %s", 
                        $service->id,
                        $service->car->brand ." ". $service->car->model,
                        $service->client->name,
                        $service->fault, 
                        Number::currency($request->total)
                    )
                );

                session()->flash('success', 'Guardado con exito');
            }
        
        } catch(\Exception $err){
            session()->flash('warning', 'Ocurrio un error | Mensaje: '. $err->getMessage());
            return view('admin.services.show', compact('service'));
        }

        return to_route('admin.service.index');
    }
}
