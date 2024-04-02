<?php

namespace App\Http\Controllers;
use App\Models\CashManagement;
use App\Models\AllocatedBudget;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;


class CashController extends Controller
{
    public function index()
    {
        try {
            $expensesResponse = Http::get('https://fms2-ecabf.fguardians-fms.com/api/expensesApi');
            $budgetsResponse = Http::get('https://fms2-ecabf.fguardians-fms.com/api/budgetApi');
            $paymentResponse = Http::get('https://fms5-iasipgcc.fguardians-fms.com/payment');

            if ($expensesResponse->successful() && $budgetsResponse->successful() && $paymentResponse->successful()) {
                $expenses = $expensesResponse->json();
                $budgets = $budgetsResponse->json();
                $paymentData = $paymentResponse->json();
                $filteredPaymentData = collect($paymentData)->reject(function ($transaction) {
                    return $transaction['transactionStatus'] === 'Pending' || $transaction['transactionStatus'] === 'Rejected';
                });
                $cashManagements = CashManagement::all();
                $totalInflow = $cashManagements->sum('inflow');

                $totalOutflow = $cashManagements->sum('outflow');

                return view('cash', compact('totalInflow', 'expenses', 'cashManagements', 'budgets', 'filteredPaymentData', 'totalOutflow'));
            } else {
                $errorMessage = $expensesResponse->failed() ? 'Failed to fetch expenses data from the external API' : 'Failed to fetch payment data from the external API';
                return back()->withError($errorMessage);
            }
        } catch (\Exception $e) {

            return back()->withError($e->getMessage());
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
            // Retrieve the budget data from the hidden input field
            $budget = json_decode($request->input('budget'), true);

            $inflowAmount = $budget['amount']; // Assuming the full budget amount is allocated
            // You may adjust this based on your business logic

            // Get the total inflow amount
            $totalInflow = CashManagement::sum('inflow');

            // Calculate the total outflow amount
            $totalOutflow = CashManagement::sum('outflow');

            // Check if the allocated budget amount exceeds the total inflow
            if ($inflowAmount > $totalInflow) {
                throw ValidationException::withMessages(['error' => 'Insufficient Funds']);
            }

            // Check if inflow is less than outflow
            if ($totalInflow < $totalOutflow) {
                // If insufficient funds, return an error message
                return redirect()->back()->withErrors(['error' => 'Insufficient funds.']);
            }

            // Update the allocated budget in the database
            AllocatedBudget::create([
                'budget_id' => $budget['id'],
                'amount' => $totalOutflow,
            ]);

            // Update the CashManagement model to deduct the inflow amount and add it to outflow
            $updatedOutflow = $totalOutflow + $inflowAmount;
            CashManagement::create([
                'inflow' => $inflowAmount,
                'outflow' => $updatedOutflow,
                'net_income' => $totalInflow - $updatedOutflow,
            ]);

            // Redirect to the cash.index route with success message
            return redirect()->route('cash.index')->with('success', 'Budget allocated successfully.');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors());
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function addExpenseFromExternal(Request $request)
    {
        try {
            // Fetch expense amount from external API
            $response = Http::get('https://fms2-ecabf.fguardians-fms.com/api/expensesApi');
            $expenses = $response->json();

            // Get the total expense amount
            $totalOutflow = collect($expenses)->sum('amount');

            // Get the sum of all inflow values from CashManagement table
            $totalInflow = CashManagement::sum('inflow');

            // Calculate net income
            $netIncome = $totalInflow - $totalOutflow;

            // Check if inflow is less than outflow
            if ($netIncome < 0) {
                // If insufficient funds, return an error message
                return redirect()->back()->withErrors(['error' => 'Insufficient funds.']);
            }

            // Create a new CashManagement instance for the outflow
            CashManagement::create([
                'outflow' => $totalOutflow,
            ]);

            // Redirect back with success message
            return redirect()->back()->with('success', 'Expenses Allocated Successfully');
        } catch (\Exception $e) {
            // Handle exceptions
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

}
