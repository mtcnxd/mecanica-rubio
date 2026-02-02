<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\QuotesController;
use App\Http\Controllers\Admin\FinanceController;
use App\Http\Controllers\Admin\CalendarController;

use App\Http\Controllers\Admin\{
    CarsController,ClientsController,PayrollController,ExpensesController,ServicesController,Employees,Investments
};

use App\Http\Controllers\Api\{
    Employee
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

// Employees
Route::group(['prefix' => 'employees_api', 'controller' => Employee::class], function(){
    Route::get('all', 'loadAll')->name('employees.load.all'); 
    Route::get('delete', 'delete')->name('employees.delete');
});

Route::group(['prefix' => 'employees', 'controller' => Employees::class], function () {
    Route::post('vacations/create', 'createPendindVacationDay')->name('employees.vacations.create');
    Route::get('vacations/cancell', 'cancellPendingVacationDay')->name('employees.vacations.cancell');
});

Route::group(['prefix' => 'cars', 'controller' => CarsController::class], function () {
    Route::post('createBrand', 'createBrand')->name('createBrand');
    Route::post('createModel', 'createModel')->name('createModel');
    Route::post('loadModels', 'loadModels')->name('loadModels');
    Route::post('searchByClient', 'searchByClient')->name('cars.searchByClient'); 
    Route::get('SearchCar', 'SearchCar')->name('cars.SearchCar');
});

// Payrolls
Route::group(['controller' => PayrollController::class], function() {
    Route::post('manageSalaries', 'manageSalaries')->name('manageSalaries');
    Route::post('addItem', 'addItem')->name('payroll.addItem');
    Route::post('removeItem', 'removeItem')->name('payroll.removeItem');
});

// Clients
Route::group(['prefix' => 'clients'], function(){
    Route::controller(ClientsController::class, )->group(function () {
        Route::get('delete', 'destroy')->name('client.delete');
        Route::get('search', 'search')->name('client.search');
        Route::get('searchPostalCode', 'searchPostalCode')->name('client.searchPostalCode');        
    });

    // TODO: change the name of this controller to ClientsController
    Route::controller(App\Http\Controllers\Api\ClientController::class)->group(function () {
        Route::get('all', 'getAll')->name('clients.getAll');
        Route::get('info/{clientId}','getInfo')->name('clients.getInfo');
        Route::get('services/{clientId}', 'getServices')->name('clients.getServices');
        Route::get('services/{clientId}/info/{serviceId}', 'getServiceInfo')->name('clients.getServiceInfo');
    });
});

Route::group(['prefix' => 'services', 'controller' => ServicesController::class], function(){
    Route::get('search', 'search')->name('services.search');

    Route::post('createServicePDF', 'createServicePDF')->name('services.createServicePDF');
    Route::post('getItemInformation', 'getItemInformation')->name('services.getItemInformation');
    
    Route::get('fromQuoteToService', 'fromQuoteToService')->name('services.change.quote');
    Route::get('getServiceItems', 'getServiceItems')->name('services.getServiceItems');
    Route::get('getDataTableServices', 'getDataTableServices')->name('getDataTableServices');

    Route::post('createItemInvoice', 'createItemInvoice')->name('createItemInvoice');
    Route::post('removeItemInvoice', 'removeItemInvoice')->name('removeItemInvoice');
});

Route::group(['prefix' => 'finance'], function(){
    Route::controller(FinanceController::class)->group(function() {
        Route::post('close', 'close')->name('finance.close');
        Route::post('createBalancePDF', 'createBalancePDF')->name('finance.createBalancePDF');
    });

    Route::controller(ExpensesController::class)->group(function(){
        Route::post('deleteItem', 'deleteItem')->name('expenses.deleteItem');
        Route::post('getImageAttached', 'getImageAttached')->name('getImageAttached');
    });
});

Route::get('calendar/event/all', [CalendarController::class, 'all'])->name('calendar.all');
Route::get('calendar/event/{id}', [CalendarController::class, 'getEvent'])->name('calendar.getEvent');

Route::get('/bitso/destroy', [Investments::class, 'destroy'])->name('bitso.destroy');