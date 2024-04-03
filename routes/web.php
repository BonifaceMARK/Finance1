<?php
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\CashController;
use App\Http\Controllers\ReportingController;
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

Route::get('/income', [ApiController::class, 'fetchPaymentData']);

Route::get('/cash/management', [CashController::class, 'index'])->name('cash.index');
Route::get('/financial/reporting', [ReportingController::class, 'index'])->name('report.index');

Route::get('/financialplan', [ApiController::class, 'getCashOutflows']);

Route::post('/add-expense-from-external', [CashController::class, 'addExpenseFromExternal'])->name('addExpenseFromExternal');

Route::get('/budgets/{id}', [CashController::class, 'showBudget'])->name('budget.show');

// Route for allocating budget
Route::post('/allocate/{id}/budget', [CashController::class, 'allocate'])->name('budget.allocate');

//API
Route::get('/Cost', [ReportingController::class, 'fetchCost']);
Route::get('/bato', [ApiController::class, 'fetch']);
Route::get('/allocatebud', [ApiController::class, 'fetchAllocatedBudget']);
Route::get('/cash', [ApiController::class, 'fetchCashManagement']);
Route::get('/method', [ApiController::class, 'CategoryMethod']);
