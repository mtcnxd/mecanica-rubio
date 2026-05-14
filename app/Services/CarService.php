<?php

namespace App\Services;

use App\Models\Car;
use App\Traits\Messenger;
use Illuminate\Support\Facades\DB;

class CarService
{
    use Messenger;

    public function all()
    {
        return Car::where('status', 'Activo')
            ->with('lastService')
            ->get();
    }

    public function find(string $id)
    {
        return Car::find($id);
    }

    public function getCarsByClient(string $id)
    {
        $carsFound = Car::where('client_id', $id)->get();

        if ($carsFound->isEmpty()){
            throw new \Exception("El cliente aun no tiene automoviles registrados.");
        }   

        return $carsFound ;
    }

    public function getAllBrands()
    {
        return DB::table('brands')->orderBy('brand')->get();
    }

    public function getAllModels(string $brand)
    {
        return DB::table('models')->where('brand', $brand)->orderBy('model')->get();
    }

    public function createCarBrand(array $data) : bool
    {
        $brand = DB::table('brands')->where('brand', $data['brand'])->first();

        if ($brand){
            throw new \Exception('La marca que intentas crear ya existe');
        }

        $created = DB::table('brands')->insert([
            'brand'   => $data['brand'],
            'premium' => ($data['premium'] == 'true') ? 1 : 0
        ]);

        return true;
    }

    public function createCarModel(array $data) : bool
    {
        $model = DB::table('models')->where('model', $data['model'])->first();

        if ($model){
            throw new \Exception('El modelo del auto que intentas crear ya existe');
        }

        $created = DB::table('models')->insert([
            'brand' => $data['brand'],
            'model' => $data['model']
        ]);

        return true;
    }

    public function createClientCar(array $data) : Car
    {
        $data['client_id'] = $data['client'];
        
        $car = Car::create($data);

        $this->telegram(
            sprintf("<b>New car created:</b> %s \n\r<b>Model:</b> %s", $data['brand'], $data['model'])
        );

        return $car;
    }

    public function findByCriteria(array $criteria)
    {
        return Car::where(function($query) use ($criteria) {
            if(isset($criteria['id'])){
                $query->where('id', $criteria['id']);
            }
        })->get();
    }
}