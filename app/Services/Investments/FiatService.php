<?php

namespace App\Services\Investments;

use App\Models\InvestmentData;
use App\Models\Investment;

class FiatService
{
    public function allInstruments()
    {
        return Investment::where('active', true)->orderBy('name','asc')->get();
    }

    public function allActive()
    {
        return Investment::where('active', true)->get();
    }

    public function dataStore(array $data): bool
    {
        $investment = Investment::where('id', $data['instrument'])->first();

        $investment->update([
            'last_amount'    => $investment->current_amount,
            'current_amount' => $data['amount'],
        ]);

        InvestmentData::create([
            'investment_id' => $data['instrument'],
            'amount'        => $data['amount'],
            'date'          => now(),
        ]);

        return true;
    }

    public function fiatDetails(int $investmentId)
    {
        return Investment::where('id', $investmentId)->first();
    }
}