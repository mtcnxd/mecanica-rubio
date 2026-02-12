<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'postcode',
        'street',
        'address',
        'city',
        'state',
        'rfc',
        'comments',
    ];

    public function setAddressAttribute($value)
    {
        $this->attributes['address'] = empty($value) ? null : $value;
    }

    public function getAddressAttribute($value)
    {
        return $value ?? "No establecido";
    }

    public function getPostcodeAttribute($value)
    {
        return $value ?? "No establecito";
    }

    public function getCityAttribute($value)
    {
        return $value ?? "No establecito";
    }

    public function getStateAttribute($value)
    {
        return $value ?? "No establecito";
    }

    public function getEmailAttribute($value)
    {
        return $value ?? '';
    }

    public function services()
    {
        return $this->hasMany(Service::class, 'client_id');
    }

    public function cars()
    {
        return $this->hasMany(Car::class, 'client_id');
    }
}
