<?php

namespace App\Models;

use Carbon\Carbon;
use App\Http\Helpers;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payroll extends Model
{
    use HasFactory;

    protected $table = 'salaries';

    protected $fillable = [
        'user_id',
        'status',
        'type',
        'start_date',
        'end_date',
        'paid_date',
        'total',
        'updated_at',
        'created_at',
    ];

    protected $dates = [
        'paid_date',
        'start_date',
        'end_date',
    ];

    public function employee()
    {
        return $this->belongsTo(User::class, 'user_id');
    }    

    public function payrollItems()
    {
        return $this->hasMany(PayrollItems::class, 'salary_id');
    }

    public function getTotalCurrentMonth()
    {
        $lastCutDate = Helpers::getLastCutDate();

        $lastBalance = DB::table('salaries')
            ->select(DB::raw('SUM(total) as total'))
            ->where('paid_date','>', $lastCutDate)
            ->first()->total;

        if ($lastBalance){
            return $lastBalance;
        }

        return 0.0;
    }
}
