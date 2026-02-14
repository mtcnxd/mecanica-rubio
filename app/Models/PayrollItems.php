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
        'concept',
        'amount',
    ];

    protected $hidden = [
        'number',
    ];

    public $timestamps = false;

    public function salary()
    {
        $this->belongsTo(Salary::class, 'salary_id');
    }
}
