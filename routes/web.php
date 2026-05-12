<?php

use App\Http\Controllers\Admin\CalendarController;
use App\Http\Controllers\Admin\CarsController;
use App\Http\Controllers\Admin\ClientsController;
use App\Http\Controllers\Admin\ChartsController;
use App\Http\Controllers\Admin\EmployeesController;
use App\Http\Controllers\Admin\ExpensesController;
use App\Http\Controllers\Admin\FinanceController;
use App\Http\Controllers\Admin\InvestmentsController;
use App\Http\Controllers\Admin\PayrollController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\QuotesController;
use App\Http\Controllers\Admin\ServicesController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Auth\LoginController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

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
Route::get('/', function () {
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
            'email' => $googleUser->email,
        ], [
            'name' => $googleUser->name,
            'avatar' => $googleUser->avatar,
            'google_token' => $googleUser->token,
            'google_userid' => $googleUser->id,
        ]);

        Auth::login($user);

        return to_route('services.index');
    } catch (Exception $err) {
        print_r(
            sprintf('Error: %s', $err->getMessage())
        );
    }
});

Route::group(['controller' => LoginController::class], function () {
    Route::get('/admin', 'index')->name('login');
    Route::get('/register', 'register')->name('user.register');

    Route::post('/register', 'store')->name('user.store');
    Route::post('/admin', 'login');
    Route::post('/admin/logout', 'logout')->name('logout');
});

Route::group(['prefix' => 'client'], function () {
    // Client resources
});

Route::group(['prefix' => 'admin', 'middleware' => 'isAdmin'], function () {
    // Main resources
    Route::resource('clients', ClientsController::class);
    Route::resource('cars', CarsController::class);
    Route::resource('services', ServicesController::class);
    Route::resource('employees', EmployeesController::class);
    Route::resource('quotes', QuotesController::class)->only('index', 'show');
    Route::resource('users', UsersController::class)->except('destroy');
    Route::resource('payroll', PayrollController::class)->except('edit', 'destroy');
    Route::resource('expenses', ExpensesController::class);

    Route::group(['prefix' => 'charts', 'controller' => ChartsController::class], function () {
        Route::get('dashboard', 'index')->name('charts.dashboard');
    });

    Route::get('calendar', [CalendarController::class, 'index'])->name('calendar.index');
    Route::get('emailInvoice/{service}', [ServicesController::class, 'sendEmailInvoice'])->name('sendEmailInvoice');

    Route::group(['prefix' => 'finance', 'controller' => FinanceController::class], function () {
        Route::get('incomes', 'index')->name('finance.incomes');
        Route::get('monthly-closing', 'montlyClosing')->name('finance.monthly-closing');
    });

    Route::group(['prefix' => 'reports'], function () {
        Route::get('employees/{userid}', [EmployeesController::class, 'report'])->name('reports.employees');
        Route::get('employees', [EmployeesController::class, 'report'])->name('reports.employees');
        Route::get('autos', [CarsController::class, 'report'])->name('reports.autos');
        Route::get('/client/{client}', [FinanceController::class, 'show'])->name('reports.client');
    });

    Route::group(['prefix' => 'investments', 'controller' => InvestmentsController::class], function () {
        Route::get('/', 'index')->name('investments.index');
        Route::post('update', 'update')->name('investments.update');
        Route::get('instrument/{investment_id}', 'show')->name('investments.show');
    });

    Route::group(['prefix' => 'profile', 'controller' => ProfileController::class], function () {
        Route::get('/', 'index')->name('profile.index');

        // Update profile it will be implemented through API routes
        // Route::post('update', [Profile::class, 'update'])->name('profile.update');
    });

    Route::group(['prefix' => 'settings', 'controller' => SettingsController::class], function () {
        Route::get('/', 'index')->name('setting.index');
        Route::post('update', 'update')->name('setting.update');
        Route::post('create', 'store')->name('setting.store');
    });
});
