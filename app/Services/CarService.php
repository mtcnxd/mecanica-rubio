<?php

namespace App\Services;

use App\Models\Car;

class CarService
{
    public function all()
    {
        return Car::where('status', 'Activo')->get();
    }

    public function find(string $id)
    {
        return Car::find($id);
    }

    public function create(array $data) : Car
    {
        return Car::create($data);
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