<?php

namespace App\Services;

use App\Services\Bitso\BitsoService;
use App\Models\Investment;
use App\Models\InvestmentData;
use App\Models\BitsoData;

class InvestmentService
{
    public $bitsoService;

    public function __construct(
        BitsoService $bitsoService
    ){
        $this->bitsoService = $bitsoService;
    }

    public function getActiveTrades()
    {
        $trades = $this->bitsoService->getActiveTrades();

        return [
            'current_total' => (double) number_format($trades->sum('current_value'), 2, '.',''),
            'purchased_total' => (double) number_format($trades->sum('purchase_value'), 2, '.',''),
            'data' => $trades
        ];
    }

    public function getActiveInvestments()
    {
        return Investment::where('active', true)->orderBy('name')->get();
    }

    public function updateInvestmentBalance(array $data) : InvestmentData
    {
        $formatter = new \NumberFormatter('es_MX', \NumberFormatter::DECIMAL);

        $data['date'] = now()->format('Y-m-d');
        $data['amount'] = $formatter->parse(str_replace(' ','', $data['amount']));

        Investment::where('id', $data['investment_id'])->update([
            'last_amount'    => Investment::raw('current_amount'),
            'current_amount' => $data['amount']
        ]);

        return InvestmentData::create($data);
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

    public function delete(int $id)
    {
        $bitsoData = BitsoData::find($id);
        $bitsoData->update([
            'active' => false
        ]);
    }

    public function getTotal() :  float
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