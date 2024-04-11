<?php

namespace App\Http\Controllers;
use App\Models\CashManagement;
use App\Models\AllocatedBudget;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Cache;

class CashController extends Controller
{

    public function index()
    {
        try {
            // Fetch budgets data
            $budgetsResponse = Http::get('https://fms2-ecabf.fguardians-fms.com/api/budgetApi');

            if ($budgetsResponse->successful()) {
                $budgets = $budgetsResponse->json();

                // Fetch transaction data
                $transactionsResponse = Http::get('https://fms5-iasipgcc.fguardians-fms.com/payment');

                if ($transactionsResponse->successful()) {
                    $transactions = $transactionsResponse->json();

                    // Fetch local cash management data
                    $cashManagements = CashManagement::all();

                    // Fetch added to inflow transaction IDs from session
                    $addedToInflowTransactionIds = session('added_to_inflow', []);

                    // Filter pending and approved budgets
                    $pendingAndApprovedBudgets = array_filter($budgets, function ($budget) {
                        return isset($budget['status']) && in_array($budget['status'], ['pending', 'approved']);
                    });

                    // Calculate total inflow from cash management
                    $totalInflow = $cashManagements->sum('inflow');

                    // Calculate total outflow from cash management
                    $totalOutflow = CashManagement::sum('outflow');

                    // Fetch total allocated budget amount
                    $totalAllocatedBudget = AllocatedBudget::sum('amount');

                    // Fetch allocated budget IDs
                    $allocatedBudgetIds = AllocatedBudget::pluck('budget_id')->toArray();

                    // Calculate final outflow (including allocated budget)
                    $finalOutflow = $totalOutflow + $totalAllocatedBudget;

                    // Return the view with the data
                    return view('cash', compact('cashManagements', 'pendingAndApprovedBudgets', 'totalInflow', 'finalOutflow', 'transactions', 'addedToInflowTransactionIds', 'allocatedBudgetIds'));
                } else {
                    $errorMessage = 'Failed to fetch payment data from the external API';
                    return back()->withError($errorMessage);
                }
            } else {
                $errorMessage = 'Failed to fetch budgets data from the external API';
                return back()->withError($errorMessage);
            }
        } catch (\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }


    public function addToInflow(Request $request)
    {
        try {
            // Retrieve the transaction ID and amount from the request
            $transactionId = $request->input('transaction_id');
            $transactionAmount = $request->input('transaction_amount');

            // Check if the transaction status is approved
            $transactionStatus = 'approved'; // You might want to fetch the actual status from your database

            if ($transactionStatus === 'approved') {
                // Update the cash management table with the inflow amount
                $cashManagement = CashManagement::firstOrCreate([]);
                $cashManagement->inflow += $transactionAmount;
                $cashManagement->save();

                // Add the transaction ID to the list of transactions added to the inflow
                $addedToInflowTransactionIds = session('added_to_inflow', []);
                $addedToInflowTransactionIds[] = $transactionId;
                session(['added_to_inflow' => $addedToInflowTransactionIds]);

                return redirect()->back()->with('success', 'Successfully added to inflow');
            } else {
                return redirect()->back()->with('error', 'Cannot add to inflow. Transaction status is not approved.');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to add to inflow: ' . $e->getMessage());
        }
    }

    public function showBudget($id)
    {
        // Retrieve budget details from the external source
        $response = Http::get('https://fms2-ecabf.fguardians-fms.com/api/budgetApi/'.$id);

        // Check if the request was successful
        if ($response->successful()) {
            $budgetData = $response->json();
            $title = $budgetData['title'];
            $amount = $budgetData['amount'];
            $status = $budgetData['status'];

            return view('cash', compact('id', 'title', 'amount', 'status'));
        } else {
            abort(404, 'Budget not found');
        }
    }

    public function allocate(Request $request, $id)
    {
        try {
            // Fetch the budget by ID
            $budget = json_decode($request->input('budget'), true);

            // Check if $budget is null or empty
            if (!$budget) {
                return redirect()->back()->with('error', 'No Budget Found');
            }

            // Get the last CashManagement record
            $lastCashManagement = CashManagement::latest()->first();

            if (!$lastCashManagement) {
                return redirect()->back()->with('error', 'No Funds Found');
            }

            // Deduct the amount from the budget as the deduction amount
            $deductionAmount = $budget['amount'];

            // Check if inflow is enough
            if ($lastCashManagement->inflow < $deductionAmount) {
                return redirect()->back()->with('error', 'Insufficient funds');
            }

            // Deduct the amount from the inflow of the last CashManagement record
            $lastCashManagement->inflow -= $deductionAmount;

            // Save the updated record
            $lastCashManagement->save();

            // Create a new instance of AllocatedBudget and save it with the deducted amount
            $allocatedBudget = new AllocatedBudget();
            $allocatedBudget->amount = $deductionAmount;
            // Assuming $id represents the ID of the budget that the allocation is associated with
            $allocatedBudget->budget_id = $id; // Associate allocated budget with the budget ID
            $allocatedBudget->save();

            // Redirect back with a success message
            return redirect()->back()->with('success', 'Budget allocated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to allocate budget: ' . $e->getMessage());
        }
    }




}
