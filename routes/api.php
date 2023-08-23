<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CurrencyController;
use App\Http\Controllers\Api\HistoryController;
use App\Http\Controllers\Api\NewsController;
use App\Http\Controllers\Api\StripeController;

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
Route::get('/news', [NewsController::class, 'getNews'])->name('news');

Route::post('/webhook', [StripeController::class, 'webhook']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');
    Route::get('/user', function (Request $request) {

        return $request->user();
    });

    Route::get('/user/getSession', [StripeController::class, 'getSession']);
    Route::get('/user/unstripe', [StripeController::class, 'cancelSubscribe'])->name('auth.cancelSubscribe');

    Route::get('/user/preferencesData', [UserController::class, 'getPreferencesData'])->name('auth.getPreferencesData');
    Route::get('/user/preferences', [UserController::class, 'getPreferences'])->name('auth.getPreferences');
    Route::put('/user/preferences', [UserController::class, 'setPreferences'])->name('auth.setPreferences');

    Route::get('/user/history', [UserController::class, 'getUserCurrencyHistory'])->name('auth.getUserCurrencyHistory');
    Route::patch('/user/subscribe', [UserController::class, 'toggleSubscriptionStatus'])->name('auth.subscribe');
    Route::patch('/user/unpremium', [UserController::class, 'unTogglePremiumStatus'])->name('auth.unpremium');

    Route::apiResource('/currency', CurrencyController::class);
    Route::apiResource('/history', HistoryController::class);

    Route::get('/table', [HistoryController::class, 'preferencesDataEloquent'])->name('preferencesDataEloquent');
});
