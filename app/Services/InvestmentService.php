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
}