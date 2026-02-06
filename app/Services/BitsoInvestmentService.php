<?php

namespace App\Services;

use App\Traits\Messenger;
use App\Services\ApiBitsoService;
use App\Models\BitsoData;

class BitsoInvestmentService
{
    use Messenger;

    public function __construct()
    {
        $this->apiBitsoService = new ApiBitsoService();
    }

    public function activeTrades()
    {
        return BitsoAPI::where('active', true)->get();
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
        return $this->apiBitsoService->placeOrder($data);
    }
}