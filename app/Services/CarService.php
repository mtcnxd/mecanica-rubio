<?php

namespace App\Services;

use App\Models\Car;
use App\Traits\Messenger;

class CarService
{
    use Messenger;

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