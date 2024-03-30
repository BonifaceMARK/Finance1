<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransactionsReport;
use App\Models\ChatMessage;
use App\Models\PFRSChecklistItem;
use App\Models\Task;
use App\Models\Project;

class DashboardController extends Controller
{
    public function index()
    {

        return view('dashboard');
    }
}
