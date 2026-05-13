<?php

use App\Http\Controllers\Api\BitsoController;
use App\Http\Controllers\Api\BrandsController;
use App\Http\Controllers\Api\CalendarController;
use App\Http\Controllers\Api\CarsController;
use App\Http\Controllers\Api\ClientsController;
use App\Http\Controllers\Api\EmployeesController;
use App\Http\Controllers\Api\EmployeesVacationsController;
use App\Http\Controllers\Api\ExpensesController;
use App\Http\Controllers\Api\ExpensesItemsController;
use App\Http\Controllers\Api\FinanceController;
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
        Route::apiResource('brands', BrandsController::class)->only('index', 'store');
        Route::apiResource('models', ModelsController::class)->only('index', 'store');
    });

// Services
Route::name('api.')
    ->prefix('services')
    ->group(function () {
        Route::post('{service}/pdf', [ServicesController::class, 'createServicePDF'])->name('services.pdf');

        Route::apiResource('services', ServicesController::class)->only('update');

        Route::name('services')
            ->apiResource('items', ServicesItemsController::class)->only('index','store','destroy');
    });

// Finance
Route::prefix('finance')
    ->name('api.')
    ->group(function () {
    
        Route::controller(FinanceController::class)->group(function () {
            Route::post('montly-closing', 'montlyCloseing')->name('finance.monthly-closing');
        });

        Route::post('expenses-items/image', [ExpensesItemsController::class, 'getImageAttached'])->name('finance.expenses.image');
        Route::apiResource('expenses-items', ExpensesItemsController::class)->only('store','destroy');
        Route::apiResource('payrolls', PayrollController::class)->only('update');
        Route::apiResource('payrolls-items', PayrollItemsController::class)->only('store','update','destroy');
});

// Employees
Route::name('api.')
    ->prefix('employees')
    ->group(function () {
        Route::apiResource('vacations', EmployeesVacationsController::class)->only('store','destroy');
        Route::get('/{employee}', [EmployeesController::class, 'searchById'])->name('employees.search');
        Route::apiResource('employees', EmployeesController::class)->only('index');
    });

Route::name('api.')
    ->prefix('investments')
    ->group(function () {
        Route::apiResource('bitso', BitsoController::class)->only('store','destroy');

        /*
        Route::get('/', 'allInvestments')->name('investments.all');
        Route::get('/{id}', 'investmentDetails')->name('investments.details');

        Route::group(['prefix' => 'bitso'], function () {
            Route::get('trades', 'getActiveTrades')->name('investments.bitso.trades');
            Route::post('/', 'store')->name('bitso.store');
            Route::delete('/{id}', 'destroy')->name('bitso.destroy');
        });
        */
    });
