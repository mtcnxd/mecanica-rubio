<?php

namespace App\Http\Controllers\Admin;

use DB;
use Carbon\Carbon;
use App\Models\{Client, Service, ServiceItems};
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\ServicesService;
use App\Traits\Messenger;

class ServicesController extends Controller
{
    use Messenger;

    public function __construct(ServicesService $servicesService)
    {
        $this->servicesService = $servicesService;
    }

    public function index()
    {
        /**
         * Server side processing
         */
        
        $service = $this->servicesService->all();
        return view('admin.services.index', compact('service'));
    }

    public function create(Client $clients)
    {
        return view('admin.services.create', compact('clients'));
    }

    public function store(Request $request)
    {
        $isQuote = isset($request->quote) ? true :false;
        
        $request->merge([
            'quote' => $isQuote,
        ]);
        
        Service::create($request->except('_token'));

        // $this->servicesService->create($request->except('_token'));

        if (!$isQuote){
            try {
                $latestServiceCreated = Service::latest()->first();

                $this->notify(
                    sprintf("<b>New service created ID:</b> %s \n\r<b>Client name:</b> %s \n\r<b>Car model:</b> %s \n\r<b>Fault:</b> %s", 
                        $latestServiceCreated->id,
                        $latestServiceCreated->client->name,
                        $latestServiceCreated->car->carName(),
                        $latestServiceCreated->fault
                    )
                );

                session()->flash('success', 
                    sprintf("Servicio creado con folio: #%s", $latestServiceCreated->id)
                );
            }
            
            catch (\Exception $err){
                session()->flash('warning', 
                    sprintf("Ocurrio un error | Mensaje: %s", $err->getMessage())
                );
            }
        }

        return to_route('services.index');
    }

    public function show(string $id)
    {
        $service = $this->servicesService->find($id);
        return view('admin.services.show', compact('service'));
    }

    public function update(Request $request, string $id)
    {
        $finishedDate = now();
        $service = $this->servicesService->find($id);

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
                $this->notify(
                    sprintf("<b>Service completed:</b> #%s - %s \n\r<b>Client:</b> %s \n\r<b>Fault:</b> %s \n\r<b>Total:</b> $%s", 
                        $service->id,
                        $service->car->brand ." ". $service->car->model,
                        $service->client->name,
                        $service->fault, 
                        number_format($request->total,2)
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
        $services = $this->servicesService->findByCriteria($request->all());

        return response()->json([
            "success" => true,
            "data"    => $services
        ]);
    }

    public function createServicePDF(Request $request)
    {
        return $this->servicesService->createPDF($request->serviceid);
    }

    public function getServiceItems(Request $request)
    {
        return Response()->json([
            "success" => true,
            "data"    => ServiceItems::findByCriteria($request->input('text'))
        ]);
    }

    public function getItemInformation(Request $request)
    {
        $data = DB::table('services_items')
            ->select('brand','model','supplier','services_items.price')
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
        }

        catch (\Exception $err) {
            return response()->json([
                'success' => false,
                'message' => $err->getMessage(),
            ]);
        }
    }

    public function createItemInvoice(Request $request)
    {
        $labour = false;
        $item   = $request->item;
        $amount = $request->amount;

        if ($request->labour == 'true'){
            if ( empty($request->item) ){
                $item   = "Servicio (mano de obra)";
            }

            $labour = true;
            $amount = 1;
        }

        DB::table('services_items')->insert([
            'service_id' => $request->service,
            'amount'     => $amount,
            'item'       => $item,
            'supplier'   => $request->supplier,
            'price'      => $request->price,
            'labour'     => $labour,
        ]);

        $data = DB::table('services_items')->where('service_id', $request->service)->get();

        return response()->json([
            "success" => true,
            "message" => "Agregado correctamente",
            "data"    => $data
        ]);
    }

    public function removeItemInvoice(Request $request)
    {
        DB::table('services_items')
            ->where('id', $request->item)
            ->delete();

        return 'Eliminado correctamente';
    }

    public function getDataTableServices(Request $request)
    {
        $servicesQuery = DB::table('services_view');

        if ($request->client != 'Todos') {
            $servicesQuery->where('client_id', [$request->client]);
        }

        if ($request->status != 'Todos') {
            $servicesQuery->where('status', $request->status);
        }

        $servicesQuery->orderBy('status', 'desc')->get();

        return \DataTables::of($servicesQuery)
            ->addColumn('service_id', function($service){
                return $service->service_id;
            })
            ->addColumn('fault', function($service){
                return '<a href="'. route("services.show", $service->service_id) .'">'. \Str::limit($service->fault, 32) ."</a>";
            })
            ->addColumn('entry_date', function($service){
                return Carbon::parse($service->entry_date)->format('j M Y');
            })
            ->addColumn('finished_date', function($service){
                if ($service->finished_date == null){
                    return null;
                }

                return Carbon::parse($service->finished_date)->format('j M Y');
            })
            ->addColumn('status', function($service){
                if ($service->status == 'Entregado'){
                    return '<span class="badge text-bg-success">'. $service->status .'</span>';
                }
                else if ($service->status == 'Cancelado' || $service->status == 'Esperando refaccion') {
                    return '<span class="badge text-bg-secondary">'. $service->status .'</span>';
                }
                else {
                    return '<span class="badge text-bg-warning">'. $service->status .'</span>';
                }
            })
            ->addColumn('total', function($service){
                return '$'.number_format($service->total, 2);
            })
            ->rawColumns(['fault','status'])
            ->make(true);
    }
}
