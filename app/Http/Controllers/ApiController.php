<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use App\Models\CostAllocationMethod;
use App\Models\ExpenseCategory;
use App\Models\CashManagement;

class ApiController extends Controller
{
    public function fetchPaymentData()
    {
        try {

            $response = Http::get('https://fms5-iasipgcc.fguardians-fms.com/payment');


            if ($response->successful()) {

                $paymentData = $response->json();


                foreach ($paymentData as $transaction) {

                    if (isset($transaction['transactionAmount']) && is_numeric($transaction['transactionAmount'])) {

                        CashManagement::create(['inflow' => $transaction['transactionAmount']]);
                    }
                }


                return view('cash', compact('paymentData'));
            } else {

                return response()->json(['error' => 'Failed to fetch payment data'], $response->status());
            }
        } catch (\Exception $e) {

            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getCashOutflows()
    {

        $costAllocationMethods = CostAllocationMethod::all();
        $expenseCategories = ExpenseCategory::all();


        $data = [
            'cost_allocation_methods' => $costAllocationMethods,
            'expense_categories' => $expenseCategories,
        ];


        return response()->json($data);
    }
    public function fetch()
    {
        $response = Http::get('https://fms5-iasipgcc.fguardians-fms.com/payment');

        if ($response->successful()) {
            $transactions = $response->json();
             dd($transactions);
        } else {
            return response()->json(['error' => 'Failed to fetch data from the external API'], $response->status());
        }
    }

}
