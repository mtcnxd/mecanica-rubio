<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Number;

use App\Http\Controllers\Controller;
use App\Services\OrderService;
use App\Services\ClientService;
use App\Traits\Messenger;
use App\Models\Service;
use App\Models\ServiceItems;
use App\Events\ServiceCompleted;

class ServicesController extends Controller
{
    use Messenger;

    public function __construct(
        private OrderService $orderService
    ){
        $this->orderService = $orderService;
    }

    public function index()
    {
        $services = [];
        $services = $this->orderService->all();

        return view('admin.services.index', compact('services'));
    }

    public function create(ClientService $clientService)
    {
        $clients = $clientService->all();

        return view('admin.services.create', compact('clients'));
    }

    public function store(Request $request)
    {
        try {
            $service = $this->orderService->createOrder($request->all());

            session()->flash('success', "Servicio creado correctamente | Folio: #{$service->id}");

        } catch(\Exception $err){
            session()->flash('warning', "Error al crear servicio | Message: {$err->getMessage()}");
        }

        return to_route('services.index');
    }

    public function show(string $id)
    {
        $service = $this->orderService->find($id);

        event(new ServiceCompleted($service));

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
                    sprintf("<b>Service completed:</b> #%s\n\r <b>Car:</b> %s\n\r<b>Client:</b> %s\n\r<b>Fault:</b> %s\n\r<b>Total:</b> %s", 
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

        return to_route('services.index');
    }

    public function search(Request $request)
    {
        $services = $this->orderService->findByCriteria($request->all());

        return response()->json([
            "success" => true,
            "data"    => $services
        ]);
    }

    public function createServicePDF(Request $request)
    {
        return $this->orderService->createPDF($request->serviceid);
    }

    public function itemGetInfo(Request $request)
    {
        $data = ServiceItems::select('brand','model','supplier','services_items.price')
            ->join('services','services_items.service_id','services.id')
            ->join('autos','services.car_id', 'autos.id')
            ->where('item', $request->item)
            ->get();

        return response()->json([
            "success" => true,
            "data"    => $data
        ]);
    }

    public function fromQuoteToService(Request $request)
    {
        try {
            Service::where('id', $request->id)->update([
                'quote' => false
            ]);

            return response()->json([
                'success' => true,
                'message' => 'La cotizacion es ahora un servicio',
            ]);
        
        } catch (\Exception $err) {
            return response()->json([
                'success' => false,
                'message' => $err->getMessage(),
            ]);
        }
    }

    public function itemByCriteria(Request $request)
    {
        try {
            return Response()->json([
                "success" => true,
                "data"    => ServiceItems::findByCriteria($request->input('text'))
            ]);

        } catch (\Exception $err){
            return response()->json([
                'success' => false,
                'message' => $err->getMessage(),
            ]);
        }
    }

    public function createOrderItem(Request $request)
    {
        try {
            $orderItem = $this->orderService->createOrderItem($request->all());

            return response()->json([
                "success"    => true,
                "message"    => "Elemento agregado correctamente",
                "saved_item" => $orderItem
            ]);

        } catch (\Exception $err){
            return response()->json([
                'success' => false,
                'message' => $err->getMessage(),
            ]);
        }
    }

    public function deleteOrderItem(Request $request)
    {
        try {
            $this->orderService->deleteOrderItem($request->id);
            
            return response()->json([
                'success' => true,
                'message' => "Eliminado correctamente",
            ]);

        } catch (\Exception $err){
            return response()->json([
                'success' => false,
                'message' => $err->getMessage(),
                'data' => $request->all(),
            ]);
        }
    }

    /**
     * Summary by status of services (API REST)
     * 
     * @return 
     */
    public function servicesThisMonth(Request $request)
    {
        try {
            return response()->json([
                'success' => true,
                'data' => $this->orderService->servicesThisMonth(),
            ]);

        } catch (\Exception $err){
            return response()->json([
                'success' => false,
                'message' => $err->getMessage(),
            ]);
        }
    }

    /**
     * Summary by status of services (API REST)
     * 
     * @return 
     */
    public function servicesSummary()
    {
        try {
            return response()->json([
                'success' => true,
                'data' => $this->orderService->servicesSummary(),
            ]);

        } catch (\Exception $err){
            return response()->json([
                'success' => false,
                'message' => $err->getMessage(),
            ]);
        }
    }

    /**
     * Summary by status of services (API REST)
     * 
     * @return 
     */
    public function serviceDetails(Request $request)
    {
        try {
            $service = $this->orderService->find($request->id);
            $service->with('car');
            $service->with('client');

            return response()->json([
                'success' => true,
                'data'    => [
                    'client'     => $service->client->name,
                    'car'        => $service->car->brand ." ".$service->car->model ." ".$service->car->year,
                    'entry_date' => $service->entry_date,
                    'fault'      => $service->fault,
                    'status'     => $service->status,
                    'total'      => $service->total,
                ],
            ]);

        } catch (\Exception $err){
            return response()->json([
                'success' => false,
                'message' => $err->getMessage(),
            ]);
        }
    }
}
