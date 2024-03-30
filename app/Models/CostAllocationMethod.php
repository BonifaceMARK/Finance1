<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CostAllocationMethod extends Model
{
    use HasFactory;

    protected $fillable = ['financial_plan_name', 'method_name', 'method_description'];
}
