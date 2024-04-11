<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransactionsReport;
use App\Models\ChatMessage;
use App\Models\PFRSChecklistItem;
use App\Models\Task;
use Illuminate\Support\Facades\Http;
use App\Models\Project;

class DashboardController extends Controller
{
    public function index()
    {
        // Fetch expenses data
        $expensesResponse = Http::get('https://fms2-ecabf.fguardians-fms.com/api/expensesApi');

        // Fetch costs data
        $costsResponse = Http::get('https://fms2-ecabf.fguardians-fms.com/api/costApi');

        // Fetch budget data
        $budgetResponse = Http::get('https://fms2-ecabf.fguardians-fms.com/api/budgetApi');

        // Fetch clients data
        $clientsResponse = Http::get('https://fms6-fabirm.fguardians-fms.com/riskApi');

        // Check if all requests were successful
        if ($expensesResponse->successful() && $costsResponse->successful() && $budgetResponse->successful() && $clientsResponse->successful()) {
            $expenses = $expensesResponse->json();
            $costs = $costsResponse->json();
            $budget = $budgetResponse->json();
            $clients = $clientsResponse->json()['clients']; // Extract 'clients' data from the response

            return view('dashboard', compact('expenses', 'costs', 'budget', 'clients'));
        } else {
            // Handle error if any API request fails
            return back()->withErrors(['api_error' => 'Failed to fetch data from API']);
        }
    }

}
