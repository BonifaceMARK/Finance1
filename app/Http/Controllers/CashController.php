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
        $costsResponse = Http::get('https://fms2-ecabf.fguardians-fms.com/api/costApi');
        $budgetsResponse = Http::get('https://fms2-ecabf.fguardians-fms.com/api/budgetApi');
        $paymentResponse = Http::get('https://fms5-iasipgcc.fguardians-fms.com/payment');

        if ($expensesResponse->successful() && $costsResponse->successful() && $budgetsResponse->successful() && $paymentResponse->successful()) {
            $expenses = $expensesResponse->json();
            $costs = $costsResponse->json();
            $budgets = $budgetsResponse->json();
            $paymentData = $paymentResponse->json();

            // Fetch CashManagement data from the database
            $cashManagements = CashManagement::all();

            // Fetch budget details for the first budget (you may adjust this logic as needed)
            $firstBudget = $budgets[0] ?? null;
            $budgetDetails = null;
            if ($firstBudget) {
                $budgetDetailsResponse = Http::get('https://fms2-ecabf.fguardians-fms.com/api/budgetApi/' . $firstBudget['id']);
                if ($budgetDetailsResponse->successful()) {
                    $budgetDetails = $budgetDetailsResponse->json();
                }
            }

            // Sum of all expenses amounts
            $totalOutflow = collect($expenses)->sum('amount');

            // Pass other data to the view
            return view('cash', compact('expenses', 'cashManagements', 'totalOutflow', 'budgets', 'budgetDetails', 'paymentData'));
        } else {
            // Handle failed API requests
            $errorMessage = $expensesResponse->failed() ? 'Failed to fetch expenses data from the external API' : ($costsResponse->failed() ? 'Failed to fetch costs data from the external API' : 'Failed to fetch payment data from the external API');
            return back()->withError($errorMessage);
        }
    } catch (\Exception $e) {
        // Handle other exceptions
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

            // Check if budget data exists
            if (!$budget) {
                throw ValidationException::withMessages(['error' => 'Budget data not found']);
            }

            // Perform the allocation logic
            $inflowAmount = $budget['amount']; // Assuming the full budget amount is allocated
            // You may adjust this based on your business logic

            // Get the total inflow amount
            $totalInflow = CashManagement::sum('inflow');

            // Check if the inflow amount exceeds the total inflow
            if ($inflowAmount > $totalInflow) {
                throw ValidationException::withMessages(['error' => 'Insufficient Funds']);
            }

            // Update the allocated budget in the database
            AllocatedBudget::create([
                'budget_id' => $budget['id'],
                'amount' => $inflowAmount,
            ]);

            // Deduct the inflow amount from the total inflow
            $remainingInflow = $totalInflow - $inflowAmount;

            // Update the CashManagement model to deduct the inflow amount and add it to outflow
            CashManagement::create([
                'inflow' => $remainingInflow, // Deduct inflow amount
                'outflow' => -$inflowAmount, // Add inflow amount to outflow
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
    // Fetch expense amount from external API
    $response = Http::get('https://fms2-ecabf.fguardians-fms.com/api/expensesApi');
    $expenses = $response->json();

    // Get the total expense amount
    $totalOutflow = collect($expenses)->sum('amount');

    // Get the total inflow
    $totalInflow = CashManagement::sum('inflow');

    // Check if inflow is less than outflow
    if ($totalInflow < $totalOutflow) {
        // If insufficient funds, return an error message
        return redirect()->back()->withErrors(['error' => 'Insufficient funds.']);
    }

    // Create a new CashManagement instance for the outflow
    CashManagement::create([
        'outflow' => $totalOutflow,
    ]);

    // Redirect back with success message
    return redirect()->back()->with('success', 'Expenses Allocated Successfully');
}

}
