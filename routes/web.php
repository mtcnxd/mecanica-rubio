<?php

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\Auth\LoginController;

use App\Http\Controllers\Admin\{
    CarsController,
    CalendarController,
    ClientsController,
    Dashboard,
    Employees,
    ExpensesController,
    FinanceController,
    InvestmentsController,
    PayrollController,
    Profile,
    QuotesController,
    ServicesController,
    Settings,
    UsersController
};

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Index for client frontend
Route::get('/', function(){
    return view('content');
});

// Login and registration routes
Route::get('/auth/redirect', function () {
    return Socialite::driver('google')->redirect();
})->name('google-redirect');
 
Route::get('/auth/callback', function () {
    try {
        $googleUser = Socialite::driver('google')->user();

        $user = User::updateOrCreate([
            'email'         => $googleUser->email,
        ], [
            'name'          => $googleUser->name,
            'avatar'        => $googleUser->avatar,
            'google_token'  => $googleUser->token,
            'google_userid' => $googleUser->id,
        ]);
    
        Auth::login($user);

        return to_route('services.index');
    }

    catch (Exception $err) {
        print_r(
            sprintf("Error: %s", $err->getMessage())
        );
    }
});

Route::group(['controller' => LoginController::class], function() {
    Route::get('/admin','index')->name('login');
    Route::get('/register','register')->name('user.register');
    
    Route::post('/register','store')->name('user.store');
    Route::post('/admin','login');
    Route::post('/admin/logout','logout')->name('logout');
});

Route::group(['prefix' => 'client'], function(){
    // Client resources
});

Route::group(['prefix' => 'admin', 'middleware' => 'isAdmin'], function(){
    // Main resources
    Route::resource('clients', ClientsController::class);
    Route::resource('cars', CarsController::class);
    Route::resource('services', ServicesController::class);    
    Route::resource('employees', Employees::class);
    Route::resource('quotes', QuotesController::class)->only('index','show');
    Route::resource('users', UsersController::class)->except('destroy'); 
    Route::resource('payroll', PayrollController::class)->except('edit', 'destroy');
    Route::resource('expenses', ExpensesController::class);
    
    Route::get('calendar', [CalendarController::class, 'index'])->name('calendar.index');
    Route::get('emailInvoice/{service}', [ServicesController::class, 'sendEmailInvoice'])->name('sendEmailInvoice');

    Route::group(['prefix' => 'finance'], function(){
        Route::get('/', [FinanceController::class, 'index'])->name('finance.incomes');
    });

    Route::group(['prefix' => 'reports'], function(){
        Route::get('overview', [Dashboard::class, 'index'])->name('reports.overview');
        Route::get('employees/{userid}', [Employees::class, 'report'])->name('reports.employees');
        Route::get('employees', [Employees::class, 'report'])->name('reports.employees');
        Route::get('autos', [CarsController::class, 'report'])->name('reports.autos');
        Route::get('close-month', [ExpensesController::class, 'report'])->name('reports.balance');

        Route::get('/client/{client}', [FinanceController::class, 'show'])->name('reports.client');
    });

    Route::group(['prefix' => 'investments', 'controller' => InvestmentsController::class], function(){
        Route::get('/', 'index')->name('investments.index');
        Route::post('store', 'store')->name('investments.store');
        Route::post('update', 'update')->name('investments.update');

        Route::get('instrument/{investment_id}', 'show')->name('investments.show');
    });
    
    Route::group(['prefix' => 'profile'], function(){ 
        Route::get('/', [Profile::class, 'index'])->name('profile.index');
        
        // Update profile it will be implemented through API routes
        // Route::post('update', [Profile::class, 'update'])->name('profile.update');
    });

    Route::group(['prefix' => 'settings', 'controller' => Settings::class], function (){
        Route::get('/', 'index')->name('setting.index');
        Route::post('update', 'update')->name('setting.update');
        Route::post('create', 'store')->name('setting.store');
    });
});
