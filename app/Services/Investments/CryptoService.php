<?php

namespace App\Services\Investments;

use App\Models\BitsoData;
use App\Services\Investments\BitsoApi;

class CryptoService
{
    public function __construct(
        private BitsoApi $api
    ) {}

    public function dataStore(array $data)
    {
        $data['purchase_value'] = $data['amount'] * $data['price'];
        BitsoData::create($data);
    }

    public function allActive()
    {
        $results = [];
        $currencies = BitsoData::where('active', true)->get();

        foreach ($currencies as $currency){
            $results[] = [
                'id'             => $currency->id,
                'amount'         => $currency->amount,
                'price'          => $currency->price,
                'value'          => $currency->purchase_value,
                'book'           => $currency->book,
                'last'           => $currency->price,
                'percentage'     => $currency->getDiffPercentageAttribute($currency->book),
                'current_value'  => $currency->getCurrentValueAttribute($currency->book),
                'purchase_value' => $currency->purchaseValue,
                'created_at'     => $currency->created_at
            ];
        }

        return $results;
    }

    public function destroy(string $id)
    {
        if ($id == "" || $id == null){
            throw new \Exception("ID is required.");
        }

        BitsoData::where('id', $id)->update([
            'active' => false
        ]);
    }
    
    public function trades()
    {
        return $this->api->userTrades(10);
    }
}