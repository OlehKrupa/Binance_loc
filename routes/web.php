<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PreferencesController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;

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

/*
Route::middleware('checkCryptocurrencyCount')->group(function () {
    Route::get('/', function () {
        return view('welcome');
    })->name('home');

    Route::get('/home', [HomeController::class, 'index'])->name('home.index');
    Route::post('/home', [HomeController::class, 'filtered'])->name('home.filtered');
});

Route::get('/preferences', [PreferencesController::class, 'index'])->name('preferences');
Route::post('/preferences/update', [PreferencesController::class, 'update'])->name('preferences.update');

Route::get('/preferences/email', [PreferencesController::class, 'subscribeEmail'])->name('subscribeEmail');
//Route::post('/webhook/telegram', [TelegramController::class, 'subscribeTG']);

Route::get('/clear', function () {
    Artisan::call('cache:clear');
    Artisan::call('view:clear');
    Artisan::call('route:clear');
    return redirect('http://localhost/home');
});

Auth::routes();
*/