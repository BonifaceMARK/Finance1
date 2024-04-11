<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use App\Models\CostAllocationMethod;
use App\Models\ExpenseCategory;
use App\Models\CashManagement;
use App\Models\AllocatedBudget;
use App\Models\BudgetPlan;



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
    public function fetchAllocatedBudget()
    {
        $allocatedBudgets = AllocatedBudget::all();
        return response()->json($allocatedBudgets);
    }

    public function fetchCashManagement()
    {
        $cashManagement = CashManagement::all();

        return response()->json([
            'success' => true,
            'data' => $cashManagement,
        ]);
    }

    public function CategoryMethod()
    {
        $costAllocationMethods = CostAllocationMethod::all();
        $expenseCategories = ExpenseCategory::all();

        return response()->json([
            'success' => true,
            'cost_allocation_methods' => $costAllocationMethods,
            'expense_categories' => $expenseCategories,
        ]);
    }
    public function fetchAndUpdate()
    {
        // Fetch external data
        $response = Http::get('https://fms10-vaims.fguardians-fms.com/api/users/accounts?fbclid=IwAR26qcPYnsgXNri1zuZQSV0ocDRqlp2-1GwHlY3YJFjRMVuPxzZGbeWeyA8_aem_AZqL7tcboWq7yoMKXrTsIRfNpR2sv6ANyblc9zYHcVJmf8qJN_l7k5-Bd4GsVQnURNZA_kWam8s8trhaXwd83idJ');

        // Check if the request was successful
        if ($response->successful()) {
            // Parse JSON response
            $data = $response->json();

            // Iterate through each user and update CashManagement model
            foreach ($data['users'] as $user) {
                $amount = (float) $user['amount']; // Convert amount to float

                // Update CashManagement model with only inflow attribute
                CashManagement::create([
                    'inflow' => $amount
                ]);
            }

            return response()->json(['message' => 'Data fetched and inflow updated successfully'], 200);
        } else {
            return response()->json(['error' => 'Failed to fetch data from the external API'], $response->status());
        }
    }
    public function indexJson()
    {
        $budgetPlans = BudgetPlan::all();
        return response()->json($budgetPlans->toArray());
    }
}
