<?php

namespace App\Services;

use App\Models\Service;

class ServicesService
{
    public function all()
    {
        return Service::all();
    }

    public function find(string $id) : Service
    {
        return Service::find($id);
    }

    public function create(array $data) : Service
    {
        return Service::create($data);
    }

    public function findByCriteria(array $criteria)
    {
        return Service::select('client_id','car_id','service_type','fault','status','entry_date','finished_date','total')
            ->with('client:id,name,email,phone')
            ->with('car:id,brand,model,year')
            ->where(function($query) use ($criteria) {
                if (isset($criteria['status'])){
                    $query->where('status', $criteria['status']);
                }

                if(isset($criteria['id'])){
                    $query->where('id', $criteria['id']);
                }
            })->get();
    }

    public function createPDF(string $id)
    {
        $service = Service::find($id);

        $path = public_path('images/mainlogo.png');
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $image = file_get_contents($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($image);

        $pdf = \PDF::loadView('admin.templates.pdf_invoice', [
            "title"   => 'COTIZACION',
            "service" => $service,
            "image"   => $base64,
        ]);

        return $pdf->download('invoice.pdf');
    }
}