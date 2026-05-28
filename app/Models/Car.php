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

    /**
     * Accessor to get the full name of the car.
     */
    
    public function getFullNameAttribute()
    {
        return $this->brand .' '.$this->model.' ['.$this->year.']';
    }

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

    public function service()
    {
        return $this->hasMany(Service::class, 'car_id');
    }

    public function lastService()
    {
        return $this->hasOne(Service::class, 'car_id')->latest('entry_date');
    }
}
