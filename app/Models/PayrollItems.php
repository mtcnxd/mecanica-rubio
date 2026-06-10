<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayrollItems extends Model
{
    use HasFactory;

    protected $table = 'salaries_details';

    protected $fillable = [
        'salary_id',
        'employee_id',
        'concept',
        'amount',
        'handed'
    ];

    public $timestamps = false;

    public function payroll()
    {
        return $this->belongsTo(Payroll::class, 'salary_id');
    }
}
