<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\ExpenseController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::get('/test', function () {
    $response = Http::get('http://127.0.0.1:8000/transactions');

    if ($response->successful()) {
        $data = $response->json();
        $message = $data['message'];
        return $message;
    } else {
        // Handle unsuccessful response
        return "Failed to retrieve data.";
    }
});


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/costApi', [ApiController::class, 'getAllCost']);


Route::get('/bato', [ApiController::class, 'fetch']);


