<?php
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FinancialPlanningController;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/register',[AuthController::class,'loadRegister']);
Route::post('/register',[AuthController::class,'register'])->name('register');
Route::get('/loginload',[AuthController::class,'loadLogin'])->name('loadlogin');
Route::post('/login',[AuthController::class,'login'])->name('login');
Route::get('/logout',[AuthController::class,'logout']);

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/Financial/Planning', [FinancialPlanningController::class, 'index'])->name('Financial.Planning');
Route::post('/expensePlan/store', [FinancialPlanningController::class, 'storeExpense'])->name('expense_categories.store');
Route::post('cost_allocation_methods', [FinancialPlanningController::class, 'storeCost'])->name('cost_allocation_methods.store');
