<?php

namespace App\Services\Investments;

use App\Models\InvestmentData;
use App\Models\Investment;

class FiatService
{
    public function dataStore(array $data)
    {
        InvestmentData::create($data);
    }

    public function allActive()
    {
        return InvestmentData::all();
    }

    public function allInstruments()
    {
        return Investment::where('active', true)->get();
    }
}