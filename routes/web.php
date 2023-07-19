<?php

use Illuminate\Support\Facades\Route;

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
//test

Route::get('/', function () {
    return view('welcome');
})->middleware('checkCryptocurrencyCount');

Route::get('/preferences', [App\Http\Controllers\PreferencesController::class, 'index'])->name('preferences');
Route::post('/preferences/update', [App\Http\Controllers\PreferencesController::class, 'update'])->name('preferences.update');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->middleware('checkCryptocurrencyCount')->name('home');
Route::get('/test', [App\Http\Controllers\HomeController::class, 'test'])->name('test');
Route::post('/home', [App\Http\Controllers\HomeController::class, 'filtered'])->middleware('checkCryptocurrencyCount')->name('home.filtered');
Route::post('/home/sendDateRange', [App\Http\Controllers\HomeController::class, 'getDateRange'])->name('home.sendDateRange');
Route::post('/home/sendCurrency', [App\Http\Controllers\HomeController::class, 'getCurrencyId'])->name('home.sendCurrency');

Route::get('/telegram', function (\App\Helpers\Telegram $telegram) {
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

Auth::routes();

Route::get('/clear', function () {
    Artisan::call('cache:clear');
    Artisan::call('config:cache');
    Artisan::call('view:clear');
    Artisan::call('route:clear');
    return "Кэш очищен.";
});
