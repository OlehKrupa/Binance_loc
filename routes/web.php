<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PreferencesController;
use App\Http\Controllers\HomeController;
use App\Helpers\Telegram;
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

Route::middleware('checkCryptocurrencyCount')->group(function () {
    Route::get('/', function () {
        return view('welcome');
    })->name('home');

    Route::get('/home', [HomeController::class, 'index'])->name('home.index');
    Route::post('/home', [HomeController::class, 'filtered'])->name('home.filtered');
});

Route::middleware('checkCryptocurrencyCount')->name('preferences.')->group(function () {
    Route::get('/preferences', [PreferencesController::class, 'index'])->name('index');
    Route::post('/preferences/update', [PreferencesController::class, 'update'])->name('update');
});

Route::get('/test', [HomeController::class, 'test'])->name('test');

Route::get('/telegram', function (Telegram $telegram) {
    $buttons = [
        'inline_keyboard' => [
            [
                [
                    'text' => 'subscribe',
                    'callback_data' => '1'
                ],
                [
                    'text' => 'unsubscribe',
                    'callback_data' => '2'
                ],
            ]
        ]
    ];
    $sendMessage = $telegram->sendButtons(337612279, 'Cryptocurrencies distribution in telegram', json_encode($buttons));
});

Route::get('/clear', function () {
    Artisan::call('cache:clear');
    Artisan::call('config:cache');
    Artisan::call('view:clear');
    Artisan::call('route:clear');
    return redirect('http://localhost/home');
});

Auth::routes();