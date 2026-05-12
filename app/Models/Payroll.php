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
        'employee_id',
        'status',
        'type',
        'start_date',
        'end_date',
        'paid_date',
        'total',
        'updated_at',
        'created_at',
    ];

    protected $casts = [
        'paid_date' => 'date',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }    

    public function payrollItems()
    {
        return $this->hasMany(PayrollItems::class, 'salary_id');
    }
}
