<?php

namespace App\Services;

use App\Models\BitsoData as BitsoAPI;
use App\Models\Investment;
use App\Services\BitsoInvestmentService;

class InvestmentService extends BitsoInvestmentService
{
    public function activeInvestments()
    {
        return Investment::where('active', true)->orderBy('name')->get();
    }

    public function investmentCreate(array $data) : Investment
    {
        return Investment::create($data);
    }

    public function investmentDetails(int $investmentId)
    {
        $investment = Investment::find($investmentId);

        $investment = $investment->load(['investmentData' => function($query){
            $query->orderBy('date','desc')->take(13);
        }]);

        return $investment;
    }

    public function getTotal() :  int
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