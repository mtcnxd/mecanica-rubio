<?php

namespace App\Services\Bitso;

use App\Services\Bitso\BitsoClient;
use App\Models\BitsoData;
use App\Traits\Messenger;

class BitsoService
{
    use Messenger;

    protected $bitsoClient;
    protected $amountToBuy = 20;

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

    public function calculateAmount($currentPrice)
    {
        $priceFloat = (float) $currentPrice;
        return ($this->amountToBuy / $priceFloat);
    }

    public function getBookPrice(string $book)
    {
        return $this->bitsoClient->getBookPrice($book)->last;
    }

    public function placeOrder(string $book, string $currentPrice)
    {
        $amount = $this->calculateAmount($currentPrice);

        $orderData = [
            'book' => $book,
            'side' => "buy",
            'type' => "limit",
            'major' => $amount,
            'price' => $currentPrice,
        ];

        return $this->bitsoClient->placeOrder($orderData);
    }
}