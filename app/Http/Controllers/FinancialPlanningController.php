<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CostAllocationMethod;
use App\Models\ExpenseCategory;

class FinancialPlanningController extends Controller
{
    public function index()
    {
        $expenseCategories = ExpenseCategory::all();

        return view('planning', compact('expenseCategories'));
    }
    public function storeExpense(Request $request)
    {
        $request->validate([
            'financial_planning_name' => 'required|string|max:255',
            'expense_category' => 'required|string|max:255',
            'expense_description' => 'required|string',
        ]);

        ExpenseCategory::create([
            'financial_planning_name' => $request->financial_planning_name,
            'expense_category' => $request->expense_category,
            'expense_description' => $request->expense_description,
        ]);

        return redirect()->route('Financial.Planning')->with('success', 'Expense category created successfully.');
    }
    public function storeCost(Request $request)
    {
        $request->validate([
            'financial_plan_name' => 'required',
            'method_name' => 'required',
            'method_description' => 'required',
        ]);

        CostAllocationMethod::create($request->all());

        return redirect()->route('Financial.Planning')
            ->with('success', 'Cost allocation method created successfully.');
    }
}
