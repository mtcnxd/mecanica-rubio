<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Number;
use App\Services\OrderService;
use App\Services\ClientService;
use App\Traits\Notificator;
use App\Models\Service;
use App\Models\ServiceItems;
use App\Events\ServiceCompleted;

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
        $services = $this->orderService->all();

        return view('admin.services.index', compact('services'));
    }

    public function create()
    {
        $clients = $this->clientService->all();

        return view('admin.services.create', compact('clients'));
    }

    public function store(Request $request)
    {
        try {
            $service = $this->orderService->createOrder($request->all());

            return to_route('admin.service.index')
                ->with('success', "Servicio creado correctamente | Folio: #{$service->id}");

        } catch(\Exception $err){
            return to_route('admin.service.index')
                ->with('warning', "Error al crear servicio | Message: {$err->getMessage()}");
        }
    }

    public function show(string $id)
    {
        $service = $this->orderService->find($id);

        return view('admin.services.show', compact('service'));
    }

    public function update(Request $request, string $id)
    {
        $finishedDate = now();
        $service = $this->orderService->find($id);

        if ($request->status == 'Entregado'){
            $finishedDate = $request->finished_date ? $request->finished_date : now();
        }
        
        $request->merge([
            'entry_date'    => isset($request->entry_date) ? Carbon::parse($request->entry_date) : null,
            'finished_date' => ($request->status == 'Entregado') ? Carbon::parse($finishedDate) : null,
        ]);

        try {
            $service->update($request->except('_token','_method'));
            session()->flash('success', 'Guardado con exito');
        }

        catch(\Exception $err){
            session()->flash('warning', 'Ocurrio un error | Mensaje: '. $err->getMessage());
        }

        if ($request->status == 'Entregado'){
            try {
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
            
            catch (\Exception $err) {
                session()->flash('warning', 'Ocurrio un error | Mensaje: '. $err->getMessage());
            }
        }

        return to_route('admin.service.index');
    }
}
