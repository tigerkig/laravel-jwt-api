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

Route::get('/', [InsightsController::class, 'all'])->name('news.all');
Route::get('/{id}', [InsightsController::class, 'detail'])->name('news.detail');

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
    Route::post('/request/add', [VolunteerController::class, 'store'])->name('volunteer_request.store');
    Route::post('/request/{id}', [VolunteerController::class, 'update'])->name('volunteer_request.update');
    Route::delete('/request/{id}', [VolunteerController::class, 'delete'])->name('volunteer_request.delete');

    // Volunteer description apis
    Route::get('/description', [VolunteerController::class, 'allDescription'])->name('volunteer_description.all');
    Route::get('/description/{id}', [VolunteerController::class, 'detailDescription'])->name('volunteer_description.detail');
    Route::post('/description/add', [VolunteerController::class, 'storeDescription'])->name('volunteer_description.store');
    Route::post('/description/{id}', [VolunteerController::class, 'updateDescription'])->name('volunteer_description.update');
    Route::delete('/description/{id}', [VolunteerController::class, 'deleteDescription'])->name('volunteer_description.delete');
});

Route::group([ 'middleware' => 'api', 'prefix' => 'organization' ], function () {
    Route::get('/', [OrganizationController::class, 'index'])->name('organization.index');
    Route::get('/{id}', [OrganizationController::class, 'show'])->name('organization.show');
    Route::post('/add', [OrganizationController::class, 'store'])->name('organization.store');
    Route::post('/update/{id}', [OrganizationController::class, 'update'])->name('organization.update');
    Route::delete('/{id}', [OrganizationController::class, 'destroy'])->name('organization.destroy');
});

Route::group([ 'middleware' => 'api', 'prefix' => 'fundraiser' ], function () {
    Route::get('/', [FundraiserController::class, 'index'])->name('fundraiser.index');
    Route::get('/{id}', [FundraiserController::class, 'show'])->name('fundraiser.show');
    Route::post('/add', [FundraiserController::class, 'store'])->name('fundraiser.store');
    Route::post('/update/{id}', [FundraiserController::class, 'update'])->name('fundraiser.update');
    Route::delete('/{id}', [FundraiserController::class, 'destroy'])->name('fundraiser.destroy');
    Route::get('/{fundraiser_id}/supporters', [SupporterController::class, 'index'])->name('supporter.index');
    Route::post('/donate/{fundraiser_id}', [SupporterController::class, 'store'])->name('supporter.store');
    Route::post('/{id}/comment', [FundraiserCommentsController::class, 'store'])->name('fundraiserComment.store');
    Route::get('/{id}/comments', [FundraiserCommentsController::class, 'index'])->name('fundraiserComment.index');
    Route::put('/comment/{id}', [FundraiserCommentsController::class, 'update'])->name('fundraiserComment.update');
    Route::delete('/comment/{id}', [FundraiserCommentsController::class, 'destroy'])->name('fundraiserComment.destroy');
    Route::patch('/fundraiser', [FundraiserCommentsController::class, 'fundraiser'])->name('fundraiserComment.fundraiser');
});

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

Route::get('/', [FaqController::class, 'all'])->name('faq.all');
Route::get('/{id}', [FaqController::class, 'detail'])->name('faq.detail');

Route::group([ 'middleware' => 'api', 'prefix' => 'contact' ], function () {
    Route::get('/', [ContactController::class, 'all'])->name('contact.all');
    Route::post('/add', [ContactController::class, 'store'])->name('contact.store');
});

Route::group([ 'middleware' => 'api', 'prefix' => 'carousel' ], function () {
    Route::get('/', [CarouselController::class, 'all'])->name('carousel.all');
    Route::post('/add', [CarouselController::class, 'store'])->name('carousel.store');
    Route::post('/{id}', [CarouselController::class, 'update'])->name('carousel.update');
    Route::delete('/{id}', [CarouselController::class, 'delete'])->name('carousel.delete');
});

Route::group([ 'middleware' => 'api', 'prefix' => 'review' ], function () {
    Route::get('/', [ReviewController::class, 'all'])->name('review.all');
    Route::get('/{id}', [ReviewController::class, 'show'])->name('review.show');
    Route::post('/add', [ReviewController::class, 'store'])->name('review.store');
    Route::post('/{id}', [ReviewController::class, 'update'])->name('review.update');
    Route::delete('/{id}', [ReviewController::class, 'delete'])->name('review.delete');
});

Route::group([ 'middleware' => 'api', 'prefix' => 'payment' ], function () {
    Route::get('/success/{payment_id}', [PaymentController::class, 'successTransaction'])->name('payment.success');
    Route::get('/cancel/{payment_id}', [PaymentController::class, 'cancelTransaction'])->name('payment.cancelled');
});
