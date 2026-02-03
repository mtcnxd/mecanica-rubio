<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\CalendarService;

class CalendarController extends Controller
{
    public function __construct(
        private $calendarService = new CalendarService
    ){

    }

    public function index()
    {
        $calendar = $this->calendarService->render();
        return view('admin.services.calendar', compact('calendar'));
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
                $this->calendarService->all()
            ]
        ]);
    }

    public function getEvent(Request $request)
    {
        $calendar = $this->calendarService->find($request->id);

        return response()->json([
            "success" => true,
            "data"    => [
                'event'   => $calendar,
            ]
        ]);
    }
}
