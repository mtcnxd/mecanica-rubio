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
        $ticker = $this->bitsoApi->getTicker();
        $currencies = BitsoData::where('active', true)->get();

        $results = [];
        foreach ($currencies as $currency){
            $results[] = array_filter($ticker, function($subTicker) use ($currency){
                dd($currency->book);
                in_array($subTicker);
            });
        }

        dd($results);

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
}