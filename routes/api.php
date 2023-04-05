<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\InsightsController;
use App\Http\Controllers\TeamMemberController;
use App\Http\Controllers\OrganizationController;

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

Route::group([ 'middleware' => 'api', 'prefix' => 'teams' ], function () {
    Route::get('/', [TeamMemberController::class, 'all'])->name('teams.all');
    Route::get('/{id}', [TeamMemberController::class, 'detail'])->name('teams.detail');
    Route::post('/update/{id}', [TeamMemberController::class, 'update'])->name('teams.update');
    Route::post('/add', [TeamMemberController::class, 'store'])->name('teams.store');
    Route::delete('/{id}', [TeamMemberController::class, 'delete'])->name('teams.delete');
});
Route::group([ 'middleware' => 'api', 'prefix' => 'organization' ], function () {
    Route::get('/', [OrganizationController::class, 'index'])->name('organization.index');
    Route::get('/{id}', [OrganizationController::class, 'show'])->name('organization.show');
    Route::post('/add', [OrganizationController::class, 'store'])->name('organization.store');
    Route::post('/update/{id}', [OrganizationController::class, 'update'])->name('organization.update');
    Route::delete('/{id}', [OrganizationController::class, 'destroy'])->name('organization.destroy');
});
