<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Services\Investments\BitsoApi;

/**
 * @property string $book
 * @property string $amount
 * @property string $price
 */
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

    protected $casts = [
        'updated_at' => "datetime:Y-m-d",
        'created_at' => "datetime:Y-m-d",
    ];

    protected $hidden = [
        'active'
    ];

    /**
     * Accessors
     */

    public function getCryptoActiveAttribute()
    {
        return $this;
    }

    public function getPurchaseValueAttribute()
    {
        return ($this->amount * $this->price);
    }

    public function getCurrentValueAttribute($book)
    {
        $api = new BitsoApi();
        $currentBookPrice = $api->getBookPrice($book);

        if ($currentBookPrice){
            return ($currentBookPrice->last * $this->amount);
        }

        return 0.0;
    }

    public function getDiffPercentageAttribute($book)
    {
        $currentValue = $this->getCurrentValueAttribute($book);
        $purchaseValue = $this->getPurchaseValueAttribute();

        $result = ($currentValue - $purchaseValue) / $currentValue;

        return ($result * 100);
    }
}
