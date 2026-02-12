<?php

namespace App\Models;

use App\Http\Helpers;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Investment extends Model
{
    use HasFactory;

    protected $table = 'investments';

    protected $fillable = [
        'name',
        'amount',
        'active',
    ];

    protected $dates = [
        'date'
    ];

    public function investmentData()
    {
        return $this->hasMany(InvestmentData::class, 'investment_id');
    }

    public function investmentPercentage()
    {
        $total = $this->sum('current_amount');
        $value = $this->current_amount;

        if ($total == 0){
            return 0;
        }

        return ($value/$total) * 100;
    }

    public function differenceBetweenDeposits()
    {
        return ($this->current_amount - $this->last_amount);
    }

    protected static function getAmountByDaysAgo($daysAgo)
    {
        $amount = DB::table('chart_assets_increment')
            ->where('export_date', now()->subDays($daysAgo)->format('Y-m-d'))
            ->first();

        if ($amount){
            return $amount->amount;
        }

        return 0;
    }

    public static function getInvestmentAmountMonthAgo($days = 10)
    {
        $first = self::getAmountByDaysAgo(1);
        $last  = self::getAmountByDaysAgo($days);

        return  ($first - $last);
    }

    public static function getInvestmentPercentageMonthAgo($days = 10)
    {
        $first = self::getAmountByDaysAgo(1);
        $last  = self::getAmountByDaysAgo($days);

        return Helpers::convertToPercentage($first, $last);
    }
}
