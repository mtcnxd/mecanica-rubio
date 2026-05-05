<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Car extends Model
{
    use HasFactory;

    protected $table = "autos";

    protected $fillable = [
        'brand',
        'model',
        'serie',
        'year',
        'plate',
        'client_id',
        'comments'
    ];    

    public function findByCriteria(string $criteria)
    {
        return $this->where(function($query) use ($criteria){
            $query->orWhere('model','like', '%'.$criteria.'%')
                  ->orWhere('brand','like', '%'.$criteria.'%');
        })->get();
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function services()
    {
        return $this->hasMany(Service::class, 'car_id');
    }

    public function carName()
    {
        return $this->brand .' '.$this->model;
    }
}
