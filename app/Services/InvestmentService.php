<?php

namespace App\Services;

use App\Models\BitsoData;
use App\Models\Investment;
use App\Models\InvestmentData;
use App\Services\Bitso\BitsoService;
use Illuminate\Support\Number;

class InvestmentService
{
    public $bitsoService;

    public function __construct(){
        $this->bitsoService = new BitsoService();
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

    public function getTotal() : array
    {
        $actives = $this->getActiveInvestments();
        
        return [
            'total' => $actives->sum('current_amount'),
            'items' => $actives->map(function($item){
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'last_amount' => $item->last_amount,
                    'current_amount' => $item->current_amount
                ];
            })
        ];
    }
}