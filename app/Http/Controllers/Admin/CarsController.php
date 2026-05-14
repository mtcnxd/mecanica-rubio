<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\CarService;
use App\Services\ClientService;
use App\Traits\Messenger;

class CarsController extends Controller
{
    use Messenger;

    public function __construct(
        private CarService $carService,
        private ClientService $clientService
    ){ }

    public function index()
    {
        $cars = $this->carService->all();

        return view('admin.cars.index', compact('cars'));
    }

    public function create()
    {
        $brands  = $this->carService->getAllBrands();
        $clients = $this->clientService->all();

        return view('admin.cars.create', compact('brands','clients'));
    }

    public function store(Request $request)
    {        
        try {
            $this->carService->createClientCar($request->all());
            session()->flash('success', 'Los datos se guardaron correctamente');

        } catch (\Exception $e){
            session()->flash('warning', "ERROR | MESSAGE: {$e->getMessage()}");
		}

        return to_route('admin.car.index');
    }

    public function show(string $id)
    {
        $car = $this->carService->find($id);

        return view('admin.cars.show', compact('car'));
    }

    public function edit(string $id)
    {
        $brands  = array();
        $clients = array();
        
        $auto = \DB::table('autos')
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

        return to_route('car.index')->with('message', 'Los datos se guardaron correctamente');
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

    /**
     * Crea una nueva marca de coche.
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createCarBrand(Request $request)
    {
        try {
            $this->carService->createCarBrand($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Los datos se guardaron con exito',
            ]);            

        } catch (\Exception $e){
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * Crea un nuevo modelo de coche.
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createCarModel(Request $request)
    {
        try {
            $this->carService->createCarModel($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Los datos se guardaron con exito',
                'request' => $request->all()
            ]);            
        
        } catch (\Exception $e){
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function getCarsByClient(String $id)
    {
        try {
            $cars = $this->carService->getCarsByClient($id);

            return response()->json([
                "success" => true,
                "data"    => $cars
            ]);

        } catch (\Exception $e){
            return response()->json([
                "success" => false,
                "message" => $e->getMessage()
            ]);
        }
    }

    public function getAllModels(String $brand)
    {
        try {
            $models = $this->carService->getAllModels($brand);

            return response()->json([
                "success" => true,
                "data"    => $models
            ]);

        } catch (\Exception $e){
            return response()->json([
                "success" => false,
                "message" => $e->getMessage()
            ]);
        }
    }
}
