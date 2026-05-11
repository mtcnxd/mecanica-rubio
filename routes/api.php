<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\QuotesController;
use App\Http\Controllers\Admin\FinanceController;
use App\Http\Controllers\Admin\CalendarController;

use App\Http\Controllers\Admin\{
    CarsController,
    ClientsController,
    PayrollController,
    ExpensesController,
    ServicesController,
    EmployeesController,
    InvestmentsController
};

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

// Clients
Route::group(['prefix' => 'clients', 'controller' => ClientsController::class], function(){    
    Route::get('/', 'getAll')->name('clients.all');
    Route::get('/postal-code', 'getPostalCodes')->name('clients.postal-code');
    Route::get('/{id}', 'clientDetails')->name('clients.detail')->whereNumber('id');
    Route::delete('/{id}', 'destroy')->name('clients.delete')->whereNumber('id');
});

// Cars
Route::group(['prefix' => 'cars', 'controller' => CarsController::class], function () {
    Route::get('client/{id}', 'getCarsByClient')->name('cars.getCarsByClient'); 
    Route::get('models/{brand}', 'getAllModels')->name('cars.getAllModels');
    
    Route::post('brand', 'createCarBrand')->name('cars.createCarBrand');
    Route::post('model', 'createCarModel')->name('cars.createCarModel');
});

// Services
Route::group(['prefix' => 'service', 'controller' => ServicesController::class], function(){
    Route::get('/', 'getAll')->name('services.all');

    Route::post('createServicePDF', 'createServicePDF')->name('services.createServicePDF');
    Route::get('fromQuoteToService', 'fromQuoteToService')->name('services.change.quote');
    
    Route::get('item/all', 'itemByCriteria')->name('services.itemByCriteria');

    // new methods
    Route::get('/', 'servicesThisMonth')->name('services.all');
    Route::get('summary', 'servicesSummary')->name('services.summary');
    Route::get('/{id}', 'serviceDetails')->name('services.details');
    
    Route::group(['prefix' => 'item'], function(){
        Route::post('/', 'createOrderItem')->name('service.createItem');
        Route::delete('/{id}', 'deleteOrderItem')->name('service.deleteItem');
    });
});

// Finance
Route::group(['prefix' => 'finance'], function(){
    Route::controller(FinanceController::class)->group(function() {
        Route::post('close', 'close')->name('finance.close');
        Route::post('createBalancePDF', 'createBalancePDF')->name('finance.createBalancePDF');
    });

    Route::controller(ExpensesController::class)->group(function(){
        Route::post('deleteItem', 'deleteItem')->name('expenses.deleteItem');
        Route::post('getImageAttached', 'getImageAttached')->name('getImageAttached');
    });
    
    Route::controller(PayrollController::class)->group(function(){
        Route::post('manageSalaries', 'manageSalaries')->name('manageSalaries');

        Route::post('item', 'createItem')->name('finance.payroll.item.create');
        Route::delete('item/{id}', 'destroyItem')->name('finance.payroll.item.destroy');
    });
});

// Employees
Route::group(['prefix' => 'employees', 'controller' => EmployeesController::class], function () {
    Route::post('vacations/create', 'createPendindVacationDay')->name('employees.vacations.create');
    Route::get('vacations/cancell', 'cancellPendingVacationDay')->name('employees.vacations.cancell');
    Route::delete('delete/{id}', 'destroy')->name('employees.delete');

    // new methods
    Route::get('/', 'getAll')->name('employees.all'); 
    Route::get('/{id}', 'getEmployeeById')->name('employees.getEmployeeById'); 
});

Route::group(['prefix' => 'investments', 'controller' => InvestmentsController::class], function(){
    Route::get('/','allInvestments')->name('investments.all');
    Route::get('/{id}','investmentDetails')->name('investments.details');
    
    Route::group(['prefix' => 'bitso'], function(){
        Route::get('trades', 'getActiveTrades')->name('investments.bitso.trades');
        Route::post('/','store')->name('bitso.store');
        Route::delete('/{id}','destroy')->name('bitso.destroy');
    });
});

Route::group(['prefix' => 'calendar', 'controller' => CalendarController::class], function(){
    Route::get('getAll','all')->name('calendar.all');
    Route::get('/','getEvent')->name('calendar.getEvent');
});

Route::group(['prefix' => 'sensors'], function(){
    Route::get('time', function () {
        return response()->json([
            'date' => now()->format('Y-m-d'),
            'time' => now()->format('H:i:s'),
        ]);
    });
});