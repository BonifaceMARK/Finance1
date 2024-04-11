<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use App\Models\CashManagement;
use App\Models\ExpenseCategory;
use App\Models\AllocatedBudget;
use App\Models\BudgetPlan;
class ReportingController extends Controller
{
    public function index()
    {
        try {
            // Fetch all records from the CashManagement table
            $cashManagements = CashManagement::all();

            // Calculate and update net income for each CashManagement record
            foreach ($cashManagements as $cashManagement) {
                $cashManagement->calculateNetIncome();
            }

            // Fetch the latest record from the CashManagement table
            $latestCashManagement = CashManagement::latest()->first();

            if ($latestCashManagement) {
                // Define $netIncomeStatus based on the latest CashManagement record's net_income
                $netIncomeStatus = $latestCashManagement->net_income > 0 ? 'status-good' : ($latestCashManagement->net_income >= -1000 ? 'status-ok' : 'status-bad');
            } else {
                // If there's no latest record, set default value for $netIncomeStatus
                $netIncomeStatus = 'status-unknown';
            }

            // Calculate total inflow from cash management
            $totalInflow = $cashManagements->sum('inflow');

            // Calculate total outflow from cash management
            $totalOutflow = CashManagement::sum('outflow');

            // Fetch total allocated budget amount
            $totalAllocatedBudget = AllocatedBudget::sum('amount');

            // Calculate final outflow (including allocated budget)
            $finalOutflow = $totalOutflow + $totalAllocatedBudget;

            // Fetch all expense categories
            $expenseCategories = ExpenseCategory::all();

            // You might need to adjust this part based on your requirements
            $allocatedBudgetTotal = AllocatedBudget::sum('amount');
            $cashInflowTotal = $cashManagements->sum('inflow');
            $cashOutflowTotal = $cashManagements->sum('outflow');
            $cashNetIncomeTotal = $cashManagements->sum('net_income');

            $data = [
                'Allocated Budget' => $allocatedBudgetTotal,
                'Cash Inflow' => $cashInflowTotal,
                'Cash Outflow' => $cashOutflowTotal,
                'Net Income' => $cashNetIncomeTotal,
            ];

            return view('report', compact('data', 'finalOutflow', 'expenseCategories', 'cashManagements', 'latestCashManagement', 'netIncomeStatus'));
        } catch (\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }
    public function storeBudget(Request $request)
    {
        $request->validate([
            'reference' => 'required|unique:budget_plans',
            'name' => 'required',
            'amount' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'description' => 'nullable|string',
        ]);

        BudgetPlan::create($request->all());

        return redirect()->back()->with('success', 'Budget plan created successfully.');
    }

}
