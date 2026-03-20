<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InvestmentData extends Model
{
    use HasFactory;

    protected $table = 'investments_data';

    protected $fillable = [
        'investment_id',
        'date',
        'amount',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function getAmountByDaysAgo($daysAgo, $id)
    {
        $result = DB::table('assets_increment')
            ->where('date', now()->subDays($daysAgo)->format('Y-m-d'))
            ->where('investment_id', $id)
            ->first();

        if ($result){
            return $result->amount;
        }

        return 0;
    }
}
