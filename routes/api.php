<?php

use App\Http\Controllers\Api\BrandsController;
use App\Http\Controllers\Api\CalendarController;
use App\Http\Controllers\Api\CarsController;
use App\Http\Controllers\Api\ClientsController;
use App\Http\Controllers\Api\EmployeesController;
use App\Http\Controllers\Api\EmployeesVacationsController;
use App\Http\Controllers\Api\ExpensesController;
use App\Http\Controllers\Api\ExpensesItemsController;
use App\Http\Controllers\Api\FinanceController;
use App\Http\Controllers\Api\InvestmentsController;
use App\Http\Controllers\Api\ModelsController;
use App\Http\Controllers\Api\PayrollController;
use App\Http\Controllers\Api\PayrollItemsController;
use App\Http\Controllers\Api\ServicesController;
use App\Http\Controllers\Api\ServicesItemsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

/*
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
*/

// Collections
Route::get('postal-codes', [ClientsController::class, 'getPostalCodes'])->name('api.postal-codes');

// Clients
Route::name('api.')
    ->group(function () {
        Route::apiResource('clients', ClientsController::class)->only('index', 'show');

        Route::prefix('{client}/cars')->group(function () {
            Route::get('/', [CarsController::class, 'show'])->name('clients.cars.show');
        });

        Route::prefix('{client}/services')->group(function () {
            Route::get('/', [ServicesController::class, 'show'])->name('clients.services.show');
        });
    });

// Cars
Route::name('api.')
    ->prefix('cars')
    ->group(function () {
        Route::name('cars.')->group(function(){
            Route::apiResource('brands', BrandsController::class)->only('index', 'store');
            Route::apiResource('models', ModelsController::class)->only('index', 'store');
        });
    });

// Services
Route::name('api.')
    ->prefix('services')
    ->group(function () {
        Route::post('/', [ServicesController::class, 'update'])->name('services.update');
        
        Route::post('{service}/pdf', [ServicesController::class, 'createServicePDF'])->name('services.pdf');

        Route::name('services')->group(function(){
            Route::apiResource('items', ServicesItemsController::class)->only('index','store','destroy');
        });
    });

// Finance
Route::prefix('finance')
    ->name('api.')
    ->group(function () {
        Route::post('expenses-items/image', [ExpensesItemsController::class, 'getImageAttached'])->name('finance.expenses-items.image');
    
        Route::controller(FinanceController::class)->group(function () {
            Route::post('montly-closing', 'montlyCloseing')->name('finance.monthly-closing');
        });
        
        Route::name('finance.')->group(function(){
            Route::apiResource('expenses-items', ExpensesItemsController::class)->only('store','destroy');
            Route::apiResource('payrolls', PayrollController::class)->only('update');
            Route::apiResource('payrolls-items', PayrollItemsController::class)->only('store','update','destroy');
        });
    });


// Employees
Route::name('api.')
    ->prefix('employees')
    ->group(function () {
        Route::get('/', [EmployeesController::class, 'index'])->name('employees.index');
        Route::get('/{employee}', [EmployeesController::class, 'searchById'])->name('employees.search');
        Route::apiResource('vacations', EmployeesVacationsController::class)->only('store','destroy');
    });


// Investments
Route::name('api.')
    ->prefix('investments')
    ->controller(InvestmentsController::class)
    ->group(function () {
        Route::post('store-crypto', 'store')->name('investment.store-crypto');
        Route::delete('destroy-crypto', 'destroy')->name('investment.destroy-crypto');
        Route::post('store-fiat', 'storeFiat')->name('investment.store-fiat');
    });
