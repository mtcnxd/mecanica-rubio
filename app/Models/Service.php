<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

/**
 * @property int $id
 * @property string $entry_date
 * @property string $status
 * @property string $finished_date
 */
class Service extends Model
{
    use HasFactory;

    protected $table = 'services';

    protected $fillable = [
        'client_id',
        'car_id',
        'fault',
        'service_type',
        'quote',
        'entry_date',
        'finished_date',
        'comments',
        'notes',
        'odometer',
        'status',
        'total',
    ];

    protected $casts = [
        'entry_date' => 'date',
        'finished_date' => 'date',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function car()
    {
        return $this->belongsTo(Car::class, 'car_id');
    }

    public function serviceItems()
    {
        return $this->hasMany(ServiceItems::class, 'service_id');
    }

    /**
     * Total amount of service
     */
    public function getTotalAttribute()
    {
        $total = $this->serviceItems()
            ->selectRaw('SUM(price * amount) as total')
            ->value('total');

        return $total ?? 0;
    }
}
