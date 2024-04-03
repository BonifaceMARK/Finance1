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
            $budgetsResponse = Http::get('https://fms2-ecabf.fguardians-fms.com/api/budgetApi');
            $paymentResponse = Http::get('https://fms5-iasipgcc.fguardians-fms.com/payment');

            if ($budgetsResponse->successful() && $paymentResponse->successful()) {
                $budgets = $budgetsResponse->json();
                $paymentData = $paymentResponse->json();
                $cashManagements = CashManagement::all();

                // Sum all inflow amounts
                $totalInflow = $cashManagements->sum('inflow');

                // Retrieve the latest inflow and outflow transactions
                $latestInflow = $cashManagements->where('inflow', '>', 0)->last();
                $latestOutflow = $cashManagements->where('outflow', '>', 0)->last();

                // If there are no inflow transactions, set totalInflow to 0
                if (!$latestInflow) {
                    $totalInflow = 0;
                }

                // Sum the outflow amounts
                $totalOutflow = $latestOutflow ? $latestOutflow->outflow : 0;

                return view('cash', compact('totalInflow', 'cashManagements', 'budgets', 'totalOutflow'));
            } else {
                $errorMessage = $budgetsResponse->failed() ? 'Failed to fetch budgets data from the external API' : 'Failed to fetch payment data from the external API';
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

    public function allocate(Request $request)
    {
        // Decode the JSON data from the request
        $budget = json_decode($request->input('budget'), true);

        // Get the last CashManagement record
        $lastCashManagement = CashManagement::latest()->first();

        if (!$lastCashManagement) {
            return redirect()->back()->with('error', 'No records found');
        }

        // Deduct the amount from the budget as the deduction amount
        $deductionAmount = $budget['amount'];

        // Deduct the amount from the inflow of the last CashManagement record
        $lastCashManagement->inflow -= $deductionAmount;

        // Add the deducted amount to the outflow of the last CashManagement record
        $lastCashManagement->outflow += $deductionAmount;

        // Save the updated record
        $lastCashManagement->save();

        // Create a new instance of AllocatedBudget and save it with the deducted amount
        $allocatedBudget = new AllocatedBudget();
        $allocatedBudget->amount = $deductionAmount;
        $allocatedBudget->save();

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Inflow deducted from the last record, added to outflow, and saved to AllocatedBudget successfully');
    }

}
