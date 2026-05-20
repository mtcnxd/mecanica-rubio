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
        Route::apiResource('client', ClientsController::class)->only('index', 'show');

        Route::prefix('{client}/car')->group(function () {
            Route::get('/', [CarsController::class, 'show'])->name('client.car.show');
        });

        Route::prefix('{client}/service')->group(function () {
            Route::get('/', [ServicesController::class, 'show'])->name('client.service.show');
        });
    });

// Cars
Route::name('api.')
    ->prefix('car')
    ->group(function () {
        Route::name('car.')->group(function(){
            Route::apiResource('brand', BrandsController::class)->only('index', 'store');
            Route::apiResource('model', ModelsController::class)->only('index', 'store');
        });
    });

// Services
Route::name('api.')
    ->prefix('service')
    ->group(function () {
        Route::get('/{id}', [ServicesController::class, 'show'])->name('service.show');

        Route::put('/{id}', [ServicesController::class, 'update'])->name('service.update');
        
        Route::post('{service}/pdf', [ServicesController::class, 'createServicePDF'])->name('service.pdf');

        Route::prefix('service-item')->name('service.')->group(function(){
            Route::apiResource('service-item', ServicesItemsController::class)->only('index','store','destroy');
        });
    });

// Finance
Route::prefix('finance')
    ->name('api.')
    ->group(function () {
        Route::post('expense-item/image', [ExpensesItemsController::class, 'getImageAttached'])->name('finance.expense-item.image');
    
        Route::controller(FinanceController::class)->group(function () {
            Route::post('monthly-closing', 'monthlyClosing')->name('finance.monthly-closing');
        });
        
        Route::name('finance.')->group(function(){
            Route::apiResource('expense-item', ExpensesItemsController::class)->only('store','destroy');
            Route::apiResource('payroll', PayrollController::class)->only('update');
            Route::apiResource('payroll-item', PayrollItemsController::class)->only('store','update','destroy');
        });
    });


// Employees
Route::name('api.')
    ->prefix('employee')
    ->group(function () {
        Route::get('/', [EmployeesController::class, 'index'])->name('employee.index');
        Route::get('/{employee}', [EmployeesController::class, 'searchById'])->name('employee.search');
        Route::apiResource('{employee}/vacations', EmployeesVacationsController::class)->only('store','destroy');
    });


// Investments
Route::name('api.')
    ->prefix('investment')
    ->controller(InvestmentsController::class)
    ->group(function () {
        Route::get('trades', 'trades')->name('investment.trades');

        Route::delete('crypto', 'destroy')->name('investment.crypto');
        Route::post('crypto', 'store')->name('investment.crypto');
        Route::post('fiat', 'storeFiat')->name('investment.fiat');
    });
