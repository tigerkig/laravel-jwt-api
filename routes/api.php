<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FundraiserController;
use App\Http\Controllers\InsightsController;
use App\Http\Controllers\TeamMemberController;
use App\Http\Controllers\VolunteerController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\SupporterController;

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

Route::group([ 'middleware' => 'api', 'prefix' => 'volunteer' ], function () {
    // Volunteer Request apis
    Route::get('/request', [VolunteerController::class, 'all'])->name('volunteer_request.all');
    Route::get('/request/{id}', [VolunteerController::class, 'detail'])->name('volunteer_request.detail');
    Route::put('/request/add', [VolunteerController::class, 'store'])->name('volunteer_request.store');
    Route::patch('/request/{id}', [VolunteerController::class, 'update'])->name('volunteer_request.update');
    Route::delete('/request/{id}', [VolunteerController::class, 'delete'])->name('volunteer_request.delete');

    // Volunteer description apis
    Route::get('/description', [VolunteerController::class, 'allDescription'])->name('volunteer_description.all');
    Route::get('/description/{id}', [VolunteerController::class, 'detailDescription'])->name('volunteer_description.detail');
    Route::put('/description/add', [VolunteerController::class, 'storeDescription'])->name('volunteer_description.store');
    Route::patch('/description/{id}', [VolunteerController::class, 'updateDescription'])->name('volunteer_description.update');
    Route::delete('/description/{id}', [VolunteerController::class, 'deleteDescription'])->name('volunteer_description.delete');
});
Route::group([ 'middleware' => 'api', 'prefix' => 'organization' ], function () {
    Route::get('/', [OrganizationController::class, 'index'])->name('organization.index');
    Route::get('/{id}', [OrganizationController::class, 'show'])->name('organization.show');
    Route::post('/add', [OrganizationController::class, 'store'])->name('organization.store');
    Route::post('/update/{id}', [OrganizationController::class, 'update'])->name('organization.update');
    Route::delete('/{id}', [OrganizationController::class, 'destroy'])->name('organization.destroy');
    Route::post('/donate/{fundraiser_id}', [SupporterController::class, 'store'])->name('supporter.store');
});

Route::group([ 'middleware' => 'api', 'prefix' => 'fundraiser' ], function () {
    Route::get('/', [FundraiserController::class, 'index'])->name('fundraiser.index');
    Route::get('/{id}', [FundraiserController::class, 'show'])->name('fundraiser.show');
    Route::post('/add', [FundraiserController::class, 'store'])->name('fundraiser.store');
    Route::post('/update/{id}', [FundraiserController::class, 'update'])->name('fundraiser.update');
    Route::delete('/{id}', [FundraiserController::class, 'destroy'])->name('fundraiser.destroy');
    Route::get('/{fundraiser_id}/supporters', [SupporterController::class, 'index'])->name('supporter.index');
});

Route::group([ 'middleware' => 'api', 'prefix' => 'supporter' ], function () {
    Route::get('/{id}', [SupporterController::class, 'show'])->name('supporter.show');
    Route::post('/add', [SupporterController::class, 'store'])->name('supporter.store');
    Route::post('/update/{id}', [SupporterController::class, 'update'])->name('supporter.update');
    Route::delete('/{id}', [SupporterController::class, 'destroy'])->name('supporter.destroy');
});
