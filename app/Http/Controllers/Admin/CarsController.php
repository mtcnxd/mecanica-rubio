<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Notifications\Telegram;
use App\Services\CarService;
use App\Traits\Messenger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CarsController extends Controller
{
    use Messenger;

    public function __construct(CarService $carService)
    {
        $this->carService = $carService;
    }

    public function index()
    {
        $cars = $this->carService->all();

        return view ('admin.cars.index', compact('cars'));
    }

    public function create()
    {
        $brands  = DB::table('brands')->orderBy('brand')->get();
        $clients = DB::table('clients')->where('status', 'Activo')->orderBy('name')->get();

        return view('admin.cars.create', compact('brands','clients'));
    }

    public function store(Request $request)
    {
        $request->merge(['client_id' => $request->client]);
        
        try {
            $this->carService->create($request->except('_method','_token'));
            
            $this->notify(new Telegram(),
                sprintf("<b>New car created:</b> %s <b>Model:</b> %s", $request->brand, $request->model)
            );

            session()->flash('success', 'Los datos se guardaron correctamente');
        }
        
        catch (\Exception $err){
            session()->flash('warning', sprintf('Ocurrio un error | %s ', $err->getMessage()));
		}

        return to_route('cars.index');
    }

    public function show(string $id)
    {
        $car = $this->carService->find($id);

        $services = DB::table('autos')
            ->join('services', 'services.car_id', 'autos.id')
            ->where('autos.id', $id)
            ->get();

        return view('admin.cars.show', compact('car'));
    }

    public function edit(string $id)
    {
        $brands  = array();
        $clients = array();
        
        $auto = DB::table('autos')
            ->select('autos.*', 'clients.name')
            ->join('clients', 'autos.client_id','clients.id')
            ->where('autos.id', $id)
            ->first();

        return view('admin.cars.edit', compact('brands','clients','auto'));
    }

    public function update(Request $request, string $id)
    {
        DB::table('autos')->where('id', $id)->update([
            "brand"    => $request->brand,
            "model"    => $request->model,
            "year"     => $request->year,
            "plate"    => $request->plate,
            "serie"    => $request->serie,
            "comments" => $request->comments,
        ]);

        return to_route('cars.index')->with('message', 'Los datos se guardaron correctamente');
    }

    public function createBrand(Request $request)
    {
        $brandExists = DB::table('brands')->where('brand', $request->brand)->first();

        if ($brandExists){
            return response()->json([
                'success' => false,
                'message' => 'La marca que intentas crear ya existe'
            ]);
        }

        try {
            DB::table('brands')->insert([
                'brand'   => $request->brand,
                'premium' => ($request->premium == 'true') ? 1 : 0
            ]);

            $brands = DB::table('brands')->get();

            return response()->json([
                'success' => true,
                'message' => 'Los datos se guardaron con exito',
                'data'    => $brands
            ]);            

        } catch (Exception $e){
            return $e->getMessage();
        }
    }    

    public function loadModels(Request $request)
    {
        $models = DB::table('models')->where('brand', $request->brand)->get();
        
        return response()->json([
            'success' => true,
            'data'    => $models
        ]);
    }

    public function SearchCar(Request $request)
    {
        $cars = Car::where(function($query) use ($request) {
            $query->orWhere('brand','like', '%'.$request->text.'%')
                  ->orWhere('model','like', '%'.$request->text.'%');
        });

        return response()->json([
            "success" => true,
            "data"    => $cars->get()
        ]);
    }
    
    public function createModel(Request $request)
    {
        $exists = DB::table('models')->where('model', $request->model)->first();

        if ($exists){
            return response()->json([
                'success' => false,
                'message' => 'El modelo que intentas crear ya existe'
            ]);
        }

        try {
            DB::table('models')->insert([
                'brand' => $request->brand,
                'model' => $request->model
            ]);

            $models = DB::table('models')
                ->where('brand', $request->brand)
                ->orderBy('model')
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Los datos se guardaron con exito',
                'data'    => $models
            ]);

        } catch (Exception $e){
            return $e->getMessage();
        }
    }

    public function searchByClient(Request $request)
    {
        $cars = DB::table('autos')
            ->where('client_id', $request->client)
            ->orderBy('brand')
            ->get();
        
        return response()->json([
            "success" => true,
            "data"    => $cars
        ]);
    }

    public function report()
    {
        $brands = DB::table('autos')
            ->select(DB::raw('count(*) as count, brand'))
            ->groupBy('brand')
            ->orderBy('count', 'desc')
            ->get();

        $items = DB::table('services_items')
            ->select('item', DB::raw('count(*) as count'))
            ->whereNotIn('item',['Servicio (mano de obra)'])
            ->groupBy('item') 
            ->orderBy('count', 'desc')
            ->get();
    
        return view('admin.reports.autos', compact('brands', 'items'));
    }
}
