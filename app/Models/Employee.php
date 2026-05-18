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
        'salary',
        'extra',
        'depto',
        'rfc',
        'curp',
        'nss',
        'email',
        'phone',
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

    public function salaries()
    {
        return $this->hasMany(Payroll::class, 'user_id');
    }

    public function vacations()
    {
        return DB::table('vacations_pendings')
            ->where('employee_id', $this->id)
            ->first();
    }

    public function vacationsDaysTaken()
    {
        return DB::table('vacations_history')
            ->where('employee_id', $this->id)
            ->orderBy('date')
            ->get();
    }
}
