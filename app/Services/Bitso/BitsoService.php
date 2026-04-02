<?php

namespace App\Services\Bitso;

use App\Services\Bitso\BitsoClient;
use App\Models\BitsoData;
use App\Traits\Messenger;

class BitsoService
{
    use Messenger;

    protected $bitsoClient;

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

    public function lastPurchasedPrice(string $book)
    {
        return BitsoData::where('book', $book)
            ->where('active', true)
            ->orderBy('created_at', 'desc')
            ->first();
    }

    public function getBookPrice(string $book)
    {
        return $this->bitsoClient->getBookPrice($book);
    }

    public function placeOrder(array $data)
    {
        return $this->bitsoClient->placeOrder($data);
    }
}