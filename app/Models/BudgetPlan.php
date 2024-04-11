<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BudgetPlan extends Model
{
    use HasFactory;
    protected $fillable = [
        'reference',
        'name',
        'amount',
        'start_date',
        'end_date',
        'description',
    ];

    protected $dates = [
        'start_date',
        'end_date',
        'created_at',
        'updated_at',
    ];
}
