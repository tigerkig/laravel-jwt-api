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
use App\Http\Controllers\FaqController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CarouselController;
use App\Http\Controllers\FundraiserCommentsController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\PaymentController;

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
    Route::get('/user-profile', [AuthController::class, 'userProfile'])->name('auth.userProfile');
    Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
    Route::post('/register', [AuthController::class, 'register'])->name('auth.register');
    Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');
    Route::post('/refresh', [AuthController::class, 'refresh'])->name('auth.refresh');
});

Route::group([ 'middleware' => 'api', 'prefix' => 'news' ], function () {
    Route::post('/add', [InsightsController::class, 'store'])->name('news.store');
    Route::post('/{id}', [InsightsController::class, 'update'])->name('news.update');
    Route::delete('/{id}', [InsightsController::class, 'delete'])->name('news.delete');
});

Route::get('/news', [InsightsController::class, 'all'])->name('news.all');
Route::get('/news/{id}', [InsightsController::class, 'detail'])->name('news.detail');

Route::group([ 'middleware' => 'api', 'prefix' => 'teams' ], function () {
    Route::post('/update/{id}', [TeamMemberController::class, 'update'])->name('teams.update');
    Route::post('/add', [TeamMemberController::class, 'store'])->name('teams.store');
    Route::delete('/{id}', [TeamMemberController::class, 'delete'])->name('teams.delete');
});

Route::get('/teams', [TeamMemberController::class, 'all'])->name('teams.all');
Route::get('/teams/{id}', [TeamMemberController::class, 'detail'])->name('teams.detail');

Route::group([ 'middleware' => 'api', 'prefix' => 'volunteer' ], function () {
    // Volunteer Request apis
    Route::post('/request/add', [VolunteerController::class, 'store'])->name('volunteer_request.store');
    Route::post('/request/{id}', [VolunteerController::class, 'update'])->name('volunteer_request.update');
    Route::delete('/request/{id}', [VolunteerController::class, 'delete'])->name('volunteer_request.delete');

    // Volunteer description apis
    Route::post('/description/add', [VolunteerController::class, 'storeDescription'])->name('volunteer_description.store');
    Route::post('/description/{id}', [VolunteerController::class, 'updateDescription'])->name('volunteer_description.update');
    Route::delete('/description/{id}', [VolunteerController::class, 'deleteDescription'])->name('volunteer_description.delete');
});

Route::get('/volunteer/request', [VolunteerController::class, 'all'])->name('volunteer_request.all');
Route::get('/volunteer/request/{id}', [VolunteerController::class, 'detail'])->name('volunteer_request.detail');
Route::get('/volunteer/description', [VolunteerController::class, 'allDescription'])->name('volunteer_description.all');
Route::get('/volunteer/description/{id}', [VolunteerController::class, 'detailDescription'])->name('volunteer_description.detail');

Route::group([ 'middleware' => 'api', 'prefix' => 'organization' ], function () {
    Route::post('/add', [OrganizationController::class, 'store'])->name('organization.store');
    Route::post('/update/{id}', [OrganizationController::class, 'update'])->name('organization.update');
    Route::delete('/{id}', [OrganizationController::class, 'destroy'])->name('organization.destroy');
});

Route::get('/organization', [OrganizationController::class, 'index'])->name('organization.index');
Route::get('/organization/{id}', [OrganizationController::class, 'show'])->name('organization.show');

Route::group([ 'middleware' => 'api', 'prefix' => 'fundraiser' ], function () {
    Route::post('/add', [FundraiserController::class, 'store'])->name('fundraiser.store');
    Route::post('/update/{id}', [FundraiserController::class, 'update'])->name('fundraiser.update');
    Route::delete('/{id}', [FundraiserController::class, 'destroy'])->name('fundraiser.destroy');
    Route::post('/donate/{fundraiser_id}', [SupporterController::class, 'store'])->name('supporter.store');
    Route::post('/{id}/comment', [FundraiserCommentsController::class, 'store'])->name('fundraiserComment.store');
    Route::put('/comment/{id}', [FundraiserCommentsController::class, 'update'])->name('fundraiserComment.update');
    Route::delete('/comment/{id}', [FundraiserCommentsController::class, 'destroy'])->name('fundraiserComment.destroy');
    Route::patch('/fundraiser', [FundraiserCommentsController::class, 'fundraiser'])->name('fundraiserComment.fundraiser');
});

Route::get('/fundraiser', [FundraiserController::class, 'index'])->name('fundraiser.index');
Route::get('/fundraiser/{id}', [FundraiserController::class, 'show'])->name('fundraiser.show');
Route::get('/fundraiser/{fundraiser_id}/supporters', [SupporterController::class, 'index'])->name('supporter.index');
Route::get('/fundraiser/{id}/comments', [FundraiserCommentsController::class, 'index'])->name('fundraiserComment.index');

Route::group([ 'middleware' => 'api', 'prefix' => 'supporter' ], function () {
    Route::get('/{id}', [SupporterController::class, 'show'])->name('supporter.show');
    Route::post('/add', [SupporterController::class, 'store'])->name('supporter.store');
    Route::post('/update/{id}', [SupporterController::class, 'update'])->name('supporter.update');
    Route::delete('/{id}', [SupporterController::class, 'destroy'])->name('supporter.destroy');
});

Route::group([ 'middleware' => 'api', 'prefix' => 'faq' ], function () {
    Route::post('/add', [FaqController::class, 'store'])->name('faq.store');
    Route::post('/{id}', [FaqController::class, 'update'])->name('faq.update');
    Route::delete('/{id}', [FaqController::class, 'delete'])->name('faq.delete');
});

Route::get('/faq', [FaqController::class, 'all'])->name('faq.all');
Route::get('/faq/{id}', [FaqController::class, 'detail'])->name('faq.detail');

Route::group([ 'middleware' => 'api', 'prefix' => 'contact' ], function () {
    Route::post('/add', [ContactController::class, 'store'])->name('contact.store');
});

Route::get('/contact', [ContactController::class, 'all'])->name('contact.all');

Route::group([ 'middleware' => 'api', 'prefix' => 'carousel' ], function () {
    Route::post('/add', [CarouselController::class, 'store'])->name('carousel.store');
    Route::post('/{id}', [CarouselController::class, 'update'])->name('carousel.update');
    Route::delete('/{id}', [CarouselController::class, 'delete'])->name('carousel.delete');
});

Route::get('/carousel', [CarouselController::class, 'all'])->name('carousel.all');

Route::group([ 'middleware' => 'api', 'prefix' => 'review' ], function () {
    Route::post('/add', [ReviewController::class, 'store'])->name('review.store');
    Route::post('/{id}', [ReviewController::class, 'update'])->name('review.update');
    Route::delete('/{id}', [ReviewController::class, 'delete'])->name('review.delete');
});

Route::get('/review', [ReviewController::class, 'all'])->name('review.all');
Route::get('/review/{id}', [ReviewController::class, 'show'])->name('review.show');

Route::group([ 'middleware' => 'api', 'prefix' => 'payment' ], function () {
    Route::get('/success/{payment_id}', [PaymentController::class, 'successTransaction'])->name('payment.success');
    Route::get('/cancel/{payment_id}', [PaymentController::class, 'cancelTransaction'])->name('payment.cancelled');
});