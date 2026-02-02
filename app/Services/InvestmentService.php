<?php

namespace App\Services;

use App\Models\BitsoData as BitsoAPI;
use App\Models\Investment;

class InvestmentService 
{
    public function activeTrades()
    {
        return BitsoAPI::where('active', true)->get();
    }

    public function activeInvestments()
    {
        return Investment::where('active', true)->orderBy('name')->get();
    }

    public function create(array $data) : Investment
    {
        return Investment::create($data);
    }

    public function getTotal()
    {
        $total = 0;
        $actives = $this->activeInvestments();

        foreach($actives as $active){
            if (!is_null($active->investmentData->last())) {
                $total += $active->investmentData->last()->amount;
            }
        }

        return $total;
    }
}