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
        'category',
        'total',
        'expense_date',
        'attach',
    ];

    protected $casts = [
        'expense_date' => 'date',
    ];

    public function employee()
    {
        return $this->belongsTo(User::class);
    }

    public function getAttachAttribute($value)
    {
        return $value ? $value : null;
    }

    public function getTotalAttribute()
    {
        return $this->amount * $this->price;
    }
}
