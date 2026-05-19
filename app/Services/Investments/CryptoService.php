<?php

namespace App\Services\Investments;

use App\Models\BitsoData;
use App\Services\Investments\BitsoApi;

class CryptoService
{
    public function __construct(
        private BitsoApi $bitsoApi
    ) {}

    public function dataStore(array $data)
    {
        $data['purchase_value'] = $data['amount'] * $data['price'];
        BitsoData::create($data);
    }

    public function allActive()
    {
        $results = [];
        $ticker = $this->bitsoApi->getTicker();
        $currencies = BitsoData::where('active', true)->get();

        foreach ($currencies as $currency){
            $results[] = array_filter(
                array_map(function($subTicker) use ($currency) {
                    if (in_array($currency->book, (array) $subTicker)){
                        return [
                            'id'             => $currency->id,
                            'amount'         => $currency->amount,
                            'price'          => $currency->price,
                            'value'          => $currency->purchase_value,
                            'book'           => $subTicker->book,
                            'last'           => $subTicker->last,
                            'percentage'     => $currency->getDiffPercentageAttribute($currency->book),
                            'current_value'  => $subTicker->last * $currency->amount,
                            'purchase_value' => $currency->amount * $currency->price,
                            'created_at'     => $currency->created_at
                        ];
                    }
                }, $ticker)
            );
        }

        $response = [];
        foreach ($results as $key => $result) {
            $response[] = array_values($result)[0];
        }

        return $response;
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
}