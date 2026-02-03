<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{Service, Client, Car};
use Carbon\Carbon;

class Calendar extends Model
{
    use HasFactory;

    protected $table = 'calendar';

    protected $fillable = [
        'event',
        'description',
        'service_id',
        'date',
        'status',
        'notified',
    ];

    protected $dates = [
        'date'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    /*
    protected $with = [
        'client'
    ];
    */

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    public function car()
    {
        return $this->belongsTo(Car::class, 'car_id');
    }

    public static function getEvents() : array
    {
        for($i = 1; $i<= date('t'); $i++) {
            $createdDate = Carbon::parse(date('Y-m-').$i);
            $events[$i]  = self::where('event_date', $createdDate)->first();
        }
        
        return $events;
    }

    public static function startDay()
    {
        $month    = now()->month;
        $firstDay = mktime(0, 0, 0, $month, 0, date("Y"));

        if ( date('N', $firstDay) == 7 ){
            return 0;
        }

        return date('N', $firstDay);
    }

    public static function daysOfWeek()
    {
        return ['Lunes','Martes','Miercoles','Jueves','Viernes','Sabado','Domingo'];
    }

    public static function monthName($index){
        $months = ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
        return $months[$index];
    }
}
