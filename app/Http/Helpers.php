<?php

namespace App\Http;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class Helpers
{
    public static function getLastCutDate()
    {
        $lastDate = DB::table('montly_balances')->orderBy('created_at','desc')->first();

        if ($lastDate){
            return Carbon::parse($lastDate->close_date);
        }

        return Carbon::now()->subMonth();
    }

    public static function convertToPercentage(float $first, float $second) : float
    {
        $difference = ($first - $second);
        if ($first != 0){
            return ($difference / $first) * 100;
        }
        return 0;
    }
}
