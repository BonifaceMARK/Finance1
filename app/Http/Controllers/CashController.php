<?php

namespace App\Http\Controllers;
use App\Models\CashManagement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;


class CashController extends Controller
{
    public function index()
    {
     return view('cash');
    }

}
