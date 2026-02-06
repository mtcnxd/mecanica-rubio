<?php

namespace App\Services;

use App\Traits\Messenger;
use App\Services\ApiBitsoService;
use App\Models\BitsoData;

class BitsoInvestmentsService
{
    use Messenger;

    public function __construct()
    {
        $this->apiBitsoService = new ApiBitsoService();
    }

    public function create(array $data) : BitsoData
    {
        return BitsoData::create($data);
    }

    public function placeOrder(array $data) : bool
    {
        return $this->apiBitsoService->placeOrder($data);
    }
}