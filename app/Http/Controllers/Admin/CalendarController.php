<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\{Service, Calendar};
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Notifications\Telegram;
use App\Http\Controllers\Notifications\Whatsapp;

class CalendarController extends Controller
{
    public function index(Calendar $calendar)
    {
        $events = [];

        $events = $calendar->whereBetween('event_date',[
            Carbon::now()->startOfMonth(),
            Carbon::now()->endOfMonth()
        ])->get();

        return view('admin.services.calendar', compact('calendar', 'events'));
    }

    public function sendNotification()
    {
        $params = [
            "recipient" => "+529991210261",
            "customer"  => "Marcos Tzuc Cen",
            "car"       => "BMW 330i",
            "date"      => "15 de marzo"
        ];

        # $response = Whatsapp::send($template);
        
        $template = Whatsapp::createServiceTemplate($params);
        Whatsapp::send();
    }

    public function all()
    {
        return response()->json([
            'success' => true,
            'data' => [
                Calendar::all()
            ]
        ]);
    }

    public function getEvent(Request $request)
    {
        $calendar = Calendar::find($request->id);

        return response()->json([
            "success" => true,
            "data"    => [
                'event'   => $calendar,
                'service' => $calendar->service,
                'client'  => $calendar->client,
                'car'     => $calendar->car,
            ]
        ]);
    }
}
