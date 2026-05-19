<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Investment extends Model
{
    use HasFactory;

    protected $table = 'investments';

    protected $fillable = [
        'name',
        'last_amount',
        'current_amount',
        'active',
    ];

    public function investmentData()
    {
        return $this->hasMany(InvestmentData::class, 'investment_id');
    }

    /**
     * Accessors
     */

    public function getTotalInvestedAttribute()
    {
        return $this->sum('current_amount');
    }

    public function getProfitPercentageAttribute()
    {
        $totalInvested = $this->getTotalInvestedAttribute();
        $currentAmount = $this->current_amount;

        return ($currentAmount * 100) / $totalInvested;
    }

    public function getProfitAttribute()
    {
        $sumLastAmount = $this->sum('last_amount');
        $sumCurrentAmunt = $this->sum('current_amount');

        return $sumCurrentAmunt - $sumLastAmount;
    }

}
