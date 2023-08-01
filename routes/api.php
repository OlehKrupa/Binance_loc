<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CurrencyController;
use App\Http\Controllers\Api\HistoryController;

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

Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
Route::post('/register', [AuthController::class, 'register'])->name('auth.register');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');

    Route::get('/user', function (Request $request) {

        return $request->user();
    });

    Route::get('/user/preferences', [UserController::class, 'getPreferences'])->name('auth.getPreferences');
    Route::put('/user/preferences', [UserController::class, 'setPreferences'])->name('auth.setPreferences');

    Route::get('/user/history', [UserController::class, 'getUserCurrencyHistory'])->name('auth.getUserCurrencyHistory');

    Route::apiResource('/currency', CurrencyController::class);
    Route::apiResource('/history', HistoryController::class);

    Route::get('/history/analyze-trend', [HistoryController::class, 'analyzeCurrencyTrend'])->name('history.analyzeTrend');
    Route::get('/history/last-currencies', [HistoryController::class, 'getLastCurrencies'])->name('history.lastCurrencies');
});