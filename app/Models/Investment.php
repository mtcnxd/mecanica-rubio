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
}
