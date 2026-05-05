<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ServiceItems extends Model
{
    use HasFactory;

    protected $table = 'services_items';

    protected $fillable = [
        'service_id',
        'item',
        'quantity',
        'price',
    ];

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    public static function findByCriteria(string $criteria)
    {
        return self::where('item','like','%'.$criteria.'%')
            ->groupBy('item')
            ->pluck('item');
    }
}
