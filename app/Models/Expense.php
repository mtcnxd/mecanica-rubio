<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $table = 'expenses';

    protected $fillable = [
        'name',
        'description',
        'amount',
        'price',
        'status',
        'responsible',
        'total',
        'expense_date',
        'attach',
    ];

    protected $casts = [
        'expense_date' => 'date',
    ];

    public function getAttachAttribute($value)
    {
        return asset('storage/' . $value);
    }
}
