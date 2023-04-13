<?php

use Illuminate\Support\Facades\Route;
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

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('/news', [InsightsController::class, 'all'])->name('news.all');
Route::get('/news/{id}', [InsightsController::class, 'detail'])->name('news.detail');
Route::get('/teams', [TeamMemberController::class, 'all'])->name('teams.all');
Route::get('/teams/{id}', [TeamMemberController::class, 'detail'])->name('teams.detail');
Route::get('/volunteer/request', [VolunteerController::class, 'all'])->name('volunteer_request.all');
Route::get('/volunteer/request/{id}', [VolunteerController::class, 'detail'])->name('volunteer_request.detail');
Route::get('/volunteer/description', [VolunteerController::class, 'allDescription'])->name('volunteer_description.all');
Route::get('/volunteer/description/{id}', [VolunteerController::class, 'detailDescription'])->name('volunteer_description.detail');
Route::get('/organization', [OrganizationController::class, 'index'])->name('organization.index');
Route::get('/organization/{id}', [OrganizationController::class, 'show'])->name('organization.show');
Route::get('/fundraiser', [FundraiserController::class, 'index'])->name('fundraiser.index');
Route::get('/fundraiser/{id}', [FundraiserController::class, 'show'])->name('fundraiser.show');
Route::get('/fundraiser/{fundraiser_id}/supporters', [SupporterController::class, 'index'])->name('supporter.index');
Route::get('/fundraiser/{id}/comments', [FundraiserCommentsController::class, 'index'])->name('fundraiserComment.index');
Route::get('/faq', [FaqController::class, 'all'])->name('faq.all');
Route::get('/faq/{id}', [FaqController::class, 'detail'])->name('faq.detail');
Route::get('/contact', [ContactController::class, 'all'])->name('contact.all');
Route::get('/carousel', [CarouselController::class, 'all'])->name('carousel.all');
Route::get('/review', [ReviewController::class, 'all'])->name('review.all');
Route::get('/review/{id}', [ReviewController::class, 'show'])->name('review.show');