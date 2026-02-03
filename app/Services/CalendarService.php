<?php

namespace App\Services;

use App\Models\Calendar;

class CalendarService
{   
    public function find(string $id)
    {
        return Calendar::select('id','name','description','car_id','client_id')
        ->with([
            'client:id,name,phone,email', 
            'car:id,brand,model'
        ])->find($id);
    }

    public function all() : array
    {
        return Calendar::all();
    }

    public function render()
    {
        return $this->monthAttrbs();
    }

    protected function monthAttrbs() : array
    {
        return [
            'month'    => Calendar::monthName(1),
            'days'     => Calendar::daysOfWeek(),
            'events'   => Calendar::getEvents(),
            'startDay' => Calendar::startDay()
        ];
    }

    protected function events()
    {
        return Calendar::whereBetween('event_date',[
            now()->startOfMonth(), now()->endOfMonth()
        ])->get();
    }
}