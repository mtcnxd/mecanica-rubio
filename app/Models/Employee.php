<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Payroll;

class Employee extends Model
{
    use HasFactory;

    protected $table = 'employees';

    protected $fillable = [
        'name',
        'phone',
        'email',
        'salary',
        'extra',
        'depto',
        'rfc',
        'curp',
        'nss',
        'comments',
        'periodicity',
        'status',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'start_date' => 'date', 
        'end_date' => 'date',
        'salary' => 'decimal:2',
        'periodicity' => 'collection',
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'id');
    }

    public function payrolls()
    {
        return $this->hasMany(Payroll::class, 'employee_id');
    }

    public function payrollItems()
    {
        return $this->hasMany(PayrollItems::class, 'employee_id');
    }

    public function vacations()
    {
        return [];
    }

    public function vacationsDaysTaken()
    {
        return [];
    }
}
