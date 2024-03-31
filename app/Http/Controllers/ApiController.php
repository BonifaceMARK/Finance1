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
            // Send a GET request to the given URL
            $response = Http::get('https://fms5-iasipgcc.fguardians-fms.com/payment');

            // Check if the request was successful (status code 200)
            if ($response->successful()) {
                // Get the response body as JSON
                $paymentData = $response->json();

                // Record cash inflow for each transaction
                foreach ($paymentData as $transaction) {
                    // Check if transaction amount is set and is numeric
                    if (isset($transaction['transactionAmount']) && is_numeric($transaction['transactionAmount'])) {
                        // Create cash inflow record for the transaction amount
                        CashManagement::create(['inflow' => $transaction['transactionAmount']]);
                    }
                }

                // Return the payment data
                return view('cash', compact('paymentData'));
            } else {
                // If the request was not successful, return an error message
                return response()->json(['error' => 'Failed to fetch payment data'], $response->status());
            }
        } catch (\Exception $e) {
            // Handle any exceptions that occur during the request
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getCashOutflows()
    {
        // Retrieve data from the models
        $costAllocationMethods = CostAllocationMethod::all();
        $expenseCategories = ExpenseCategory::all();

        // Organize the data into an array
        $data = [
            'cost_allocation_methods' => $costAllocationMethods,
            'expense_categories' => $expenseCategories,
        ];

        // Return the data as a JSON response
        return response()->json($data);
    }
    public function fetch()
    {
        // Make a GET request to the external API
        $response = Http::get('https://fms2-ecabf.fguardians-fms.com/api/costApi');

        // Check if the request was successful
        if ($response->successful()) {
            // Extract the JSON data from the response
            $transactions = $response->json();

            // Dump and die the fetched data
            dd($transactions);
        } else {
            // Handle the case where the request was not successful
            return response()->json(['error' => 'Failed to fetch data from the external API'], $response->status());
        }
    }
}
