<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use App\Models\CashManagement;

class ReportingController extends Controller
{
    public function index()
    {
        try {
            // Make a GET request to the external API to fetch expenses
            $expensesResponse = Http::get('https://fms2-ecabf.fguardians-fms.com/api/expensesApi');
            
            // Make a GET request to the external API to fetch costs
            $costsResponse = Http::get('https://fms2-ecabf.fguardians-fms.com/api/costApi');
    
            // Make a GET request to fetch payment data
            $paymentResponse = Http::get('https://fms5-iasipgcc.fguardians-fms.com/payment');
    
            // Check if all requests were successful
            if ($expensesResponse->successful() && $costsResponse->successful() && $paymentResponse->successful()) {
                // Extract the JSON data from the responses
                $expenses = $expensesResponse->json();
                $costs = $costsResponse->json();
                $paymentData = $paymentResponse->json();
    
                // Calculate the percentage increase for each expense
                foreach ($expenses as &$expense) {
                    // Check if the previous amount key exists
                    if (isset($expense['previous_amount'])) {
                        // Assuming you have previous and current amounts for each expense
                        $previousAmount = $expense['previous_amount']; // Replace with the appropriate key for the previous amount
                        $currentAmount = $expense['amount']; // Replace with the appropriate key for the current amount
    
                        // Calculate the percentage increase
                        $percentageIncrease = ($currentAmount - $previousAmount) / $previousAmount * 100;
    
                        // Add the percentage increase to the expense data
                        $expense['percentageIncrease'] = $percentageIncrease;
                    } else {
                        // Set the percentage increase to null or some default value
                        $expense['percentageIncrease'] = null;
                    }
                }
    
                // Record cash inflow for each transaction
                foreach ($paymentData as $transaction) {
                    // Check if transaction amount is set and is numeric
                    if (isset($transaction['transactionAmount']) && is_numeric($transaction['transactionAmount'])) {
                        // Check if the transaction amount already exists in the database
                        $existingTransaction = CashManagement::where('inflow', $transaction['transactionAmount'])->first();
    
                        // If the transaction amount doesn't exist in the database, create a new record
                        if (!$existingTransaction) {
                            CashManagement::create(['inflow' => $transaction['transactionAmount']]);
                        }
                    }
                }
    
                // Get previous revenue from cash inflow records
                $previousRevenue = CashManagement::sum('inflow');
    
                // Calculate percentage increase
                $currentRevenue = isset($paymentData[0]['transactionAmount']) ? $paymentData[0]['transactionAmount'] : 0;
                $percentageIncrease = $previousRevenue != 0 ? (($currentRevenue - $previousRevenue) / $previousRevenue) * 100 : 0;
    
                // Return the fetched expenses, costs, payment data, previous revenue, and percentage increase to the Blade view
                return view('report', compact('expenses', 'costs', 'paymentData', 'previousRevenue', 'percentageIncrease'));
            } else {
                // Handle the case where the request was not successful for expenses, costs, or payment data
                $errorMessage = $expensesResponse->failed() ? 'Failed to fetch expenses data from the external API' : ($costsResponse->failed() ? 'Failed to fetch costs data from the external API' : 'Failed to fetch payment data from the external API');
                return back()->withError($errorMessage);
            }
        } catch (\Exception $e) {
            // Handle any exceptions that may occur during the requests
            return back()->withError($e->getMessage());
        }
    }
    
}
