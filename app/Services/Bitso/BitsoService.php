<?php

namespace App\Services\Bitso;

use App\Services\Bitso\BitsoClient;
use App\Models\BitsoData;
use App\Traits\Messenger;

class BitsoService
{
    use Messenger;

    public function __construct()
    {
        $this->bitsoClient = new BitsoClient();
    }

    public function getActiveTrades()
    {
        return BitsoData::where('active', true)->get();
    }

    public function bitsoDataCreate(array $data) : BitsoData
    {
        return BitsoData::create($data);
    }

    public function delete(int $id) : void
    {
        BitsoData::where('id', $id)->update([
            'active' => false 
        ]);
    }

    public function placeOrder(array $data) : bool
    {
        $data = [
            'book' => "btc_mxn",
            'side' => "buy",
            'type' => "limit",
            'amount' => $data['amount'],
            'price' => $data['price']
        ];

        return $this->apiBitsoService->placeOrder($data);
    }
}