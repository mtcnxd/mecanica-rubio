<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Services\Bitso\BitsoClient;

class BitsoData extends Model
{
    use HasFactory;

    protected $table = 'bitso_data';
    
    protected $ticker = [];
    protected $bitso;

    protected $fillable = [
        'book',
        'amount',
        'price',
        'active',
        'purchase_value'
    ];

    public function __construct()
    {
        $this->bitso = new BitsoClient();
    }

    public function currentGainOrLost(string $book)
    {
        $result        = 0.0;
        $purchasePrice = $this->price;
        $currentPrice  = $this->currentPrice($book); 
        $result        = ($currentPrice - $purchasePrice) / $currentPrice;

        return $result * 100;
    }

    public function getTickerByBook(string $book)
    {
        $this->ticker = $this->bitso->getTicker();

        foreach ($this->ticker as $item) {
            if (in_array($book, (array) $item)){
                return $item;
            }
        }
    }

    public function currentPrice(string $book)
    {
        return $this->getTickerByBook($book)->last;
    }

    public function currentPurchaseValue(string $book)
    {
        $currentPrice = $this->getTickerByBook($book)->last;
        return ($currentPrice * $this->amount);
    }
}
