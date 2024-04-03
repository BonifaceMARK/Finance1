<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use App\Models\CashManagement;
use App\Models\ExpenseCategory;

class ReportingController extends Controller
{
    public function index()
    {
        try {
            $costsResponse = Http::get('https://fms2-ecabf.fguardians-fms.com/api/costApi');
            $paymentResponse = Http::get('https://fms5-iasipgcc.fguardians-fms.com/payment');

            if ($costsResponse->successful() && $paymentResponse->successful()) {
                $costs = $costsResponse->json();
                $paymentData = $paymentResponse->json();

                foreach ($paymentData as $transaction) {
                    if (isset($transaction['transactionAmount']) && is_numeric($transaction['transactionAmount'])) {
                        $existingTransaction = CashManagement::where('inflow', $transaction['transactionAmount'])->first();
                        if (!$existingTransaction) {
                            CashManagement::create(['inflow' => $transaction['transactionAmount']]);
                        }
                    }
                }

                // Fetch all records from the CashManagement table
                $cashManagements = CashManagement::all();

                // Calculate total inflow and outflow
                $totalInflow = $cashManagements->sum('inflow');
                $totalOutflow = $cashManagements->sum('outflow');

                // Calculate net income
                $netIncome = $totalInflow - $totalOutflow;

                // Determining financial health status
                if ($netIncome > 0) {
                    $netIncomeStatus = 'status-good';
                } elseif ($netIncome >= -1000) { // Adjust threshold as needed
                    $netIncomeStatus = 'status-ok';
                } else {
                    $netIncomeStatus = 'status-bad';
                }

                $previousRevenue = CashManagement::sum('inflow');
                $currentRevenue = isset($paymentData[0]['transactionAmount']) ? $paymentData[0]['transactionAmount'] : 0;
                $percentageIncrease = $previousRevenue != 0 ? (($currentRevenue - $previousRevenue) / $previousRevenue) * 100 : 0;

                $expenseCategories = ExpenseCategory::all();

                return view('report', compact('totalInflow', 'netIncome', 'netIncomeStatus', 'totalOutflow', 'netIncome', 'costs', 'paymentData', 'previousRevenue', 'percentageIncrease', 'expenseCategories'));
            } else {
                $errorMessage = $costsResponse->failed() ? 'Failed to fetch costs data from the external API' : 'Failed to fetch payment data from the external API';
                return back()->withError($errorMessage);
            }
        } catch (\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }


}
