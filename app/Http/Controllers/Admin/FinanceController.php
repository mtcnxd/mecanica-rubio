<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;
use \Carbon\Carbon;
use \DB;
use PDF;

class FinanceController extends Controller
{
    public function index(Service $service)
    {
        $list = $service->where('status', 'Entregado')
            ->whereMonth('finished_date', now()->month)
            ->get();

        return view('admin.reports.services', compact('list'));
    }

    public function show($client, Service $service)
    {
        $servicesByClient = $service->where('client_id', $client)->get();

        return view('admin.reports.client', compact('servicesByClient'));
    }

    public function close(Request $request)
    {
        try {
            DB::table('montly_balances')->insert([
                "income"     => $request->income,
                "expenses"   => $request->expenses,
                "balance"    => $request->balance,
                "close_date" => Carbon::now(),
                "comments"   => 'Comentarios del cierre de mes',
                "created_at" => Carbon::now()
            ]);
        }
        
        catch (\Exception $err){
            return Response()->json([
                "success"  => false,
                "messsage" => sprintf('Error: %s', $err->getMessage()) ,
                "data"     => $request->all()
            ]);
        }

        sleep(5);

        return Response()->json([
            "success"  => true,
            "message" => 'El mes actual se ha cerrado correctamente',
        ]);
    }

    public function createBalancePDF(Request $request)
    {
        $latest = DB::table('montly_balances')->latest()->first();

        $rows = DB::table('montly_balance_view')
            ->whereBetween('date', [Carbon::parse($latest->close_date), Carbon::now()])
            ->orderBy('date')
            ->get();

        $path = public_path('images/mainlogo.png');
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $image = file_get_contents($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($image);

        $data = [
            "services" => array(),
            "image"    => $base64,
            "rows"     => $rows,
        ];

        $pdf = PDF::loadView('admin.templates.pdf_balance', $data);
        
        return $pdf->download('balance.pdf');
    }
}
