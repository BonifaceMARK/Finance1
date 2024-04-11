<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashManagement extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'inflow',
        'outflow',
        'net_income',
    ];

    /**
     * Calculate and set the net income based on inflow and outflow.
     */
    public function calculateNetIncome()
    {
        // Calculate net income
        $netIncome = $this->inflow - $this->outflow;

        // Update the net_income attribute
        $this->net_income = $netIncome;

        // Save the model
        $this->save();
    }
}

