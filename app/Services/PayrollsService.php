<?php

namespace App\Services;

use App\Models\Payroll;

class PayrollsService 
{
    public function getCurrentMonth()
    {
        $startDate = now()->subMonths(2)->startofMonth()->format('Y-m-d');
        $endDate   = now()->addDay()->format('Y-m-d');

        $data = Payroll::whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->get();

        return [
            'startDate' => $startDate,
            'endDate' => $endDate,
            'data' => $data,
        ];
    }

    public function create(array $data) : Payroll
    {
        return Payroll::create($data);
    }

    public function find (string $id)
    {
    }
    
    public function update (string $id, array $data)
    {
        
    }
}