<?php

namespace App\Services\Investments;

use App\Models\InvestmentData;

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
}