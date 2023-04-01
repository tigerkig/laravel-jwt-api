<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\InsightsController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group([ 'middleware' => 'api', 'prefix' => 'auth' ], function ($router) {
    Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
    Route::post('/register', [AuthController::class, 'register'])->name('auth.register');
    Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');
    Route::post('/refresh', [AuthController::class, 'refresh'])->name('auth.refresh');
    Route::get('/user-profile', [AuthController::class, 'userProfile'])->name('auth.userProfile'); 
});

Route::group([ 'middleware' => 'api', 'prefix' => 'news' ], function () {
    Route::get('/', [InsightsController::class, 'all'])->name('news.all');
    Route::get('/{id}', [InsightsController::class, 'detail'])->name('news.detail');
    Route::post('/add', [InsightsController::class, 'store'])->name('news.store');

});
