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
        $this->bitsoModel = new BitsoData();
    }

    public function getActiveTrades()
    {
        return $this->bitsoModel->where('active', true)->get();
    }

    public function bitsoDataCreate(array $data) : BitsoData
    {
        return $this->bitsoModel->create($data);
    }

    public function lastPurchasedPrice(string $book)
    {
        return $this->bitsoModel->where('book', $book)
            ->where('active', true)
            ->orderBy('created_at', 'desc')
            ->first();
    }

    public function delete(int $id) : void
    {
        $this->bitsoModel->where('id', $id)->update([
            'active' => false 
        ]);
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