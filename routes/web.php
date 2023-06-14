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

Route::get('/', function () {
    return view('welcome');
})->middleware('checkCryptocurrencyCount');

Route::get('/preferences',[App\Http\Controllers\PreferencesController::class,'index'])->name('preferences');
Route::post('/preferences/update', [App\Http\Controllers\PreferencesController::class, 'update'])->name('preferences.update');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->middleware('checkCryptocurrencyCount')->name('home');
Route::post('/home', [App\Http\Controllers\HomeController::class, 'index'])->middleware('checkCryptocurrencyCount')->name('home');

Auth::routes();

Route::get('/clear', function() {    
Artisan::call('cache:clear');    
Artisan::call('config:cache');    
Artisan::call('view:clear');  
Artisan::call('route:clear');     
return "Кэш очищен.";});