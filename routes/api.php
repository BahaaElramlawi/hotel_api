<?php

use App\Http\Controllers\Admin\AcceptOrRejectHost;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\HostManagementController;
use App\Http\Controllers\Admin\HotelFacilityController;
use App\Http\Controllers\Admin\HotelManagementController;
use App\Http\Controllers\Admin\HotelRegionController;
use App\Http\Controllers\Admin\HotelTagController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Host\Auth\HostAuthController;
use App\Http\Controllers\Host\Auth\HostEmailVerificationController;
use App\Http\Controllers\Host\Auth\HostForgotPasswordController;
use App\Http\Controllers\Host\HostProfileController;
use App\Http\Controllers\Host\HotelController;
use App\Http\Controllers\Host\HotelLocationController;
use App\Http\Controllers\User\Auth\AuthController;
use App\Http\Controllers\User\Auth\EmailVerificationController;
use App\Http\Controllers\User\Auth\ForgotPasswordController;
use App\Http\Controllers\User\Booking\BookingController;
use App\Http\Controllers\User\Home\HomeController;
use App\Http\Controllers\User\Profile\UserProfileController;
use App\Http\Controllers\User\Review\HotelReviewController;
use App\Http\Controllers\User\Search\SearchController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum', 'verified')->get('/user', function (Request $request) {
    return $request->user();
});

//public admins routes
Route::post('/admin/login', [AdminAuthController::class, 'login']);

//protected admins routes
Route::group(['middleware' => ['auth:sanctum']], function () {
    //users control
    Route::get('/admin/get-all-users', [UserManagementController::class, 'index']);
    Route::get('/admin/get-show-user/{id}', [UserManagementController::class, 'show']);
    Route::delete('/admin/delete-user/{id}', [UserManagementController::class, 'destroy']);

    //hosts control
    Route::get('/admin/get-all-hosts', [HostManagementController::class, 'index']);
    Route::get('/admin/get-show-hosts/{id}', [HostManagementController::class, 'show']);
    Route::delete('/admin/destroy-hosts/{id}', [HostManagementController::class, 'destroy']);
    Route::post('/admin/accept-or-Reject/host/{id}', [AcceptOrRejectHost::class, 'acceptOrReject']);

    //regions control
    Route::get('/admin/index-regions', [HotelRegionController::class, 'index']);
    Route::get('/admin/show-region/{id}', [HotelRegionController::class, 'show']);
    Route::post('/admin/store-region', [HotelRegionController::class, 'store']);
    Route::post('/admin/update-region/{id}', [HotelRegionController::class, 'update']);
    Route::delete('/admin/destroy-region/{id}', [HotelRegionController::class, 'destroy']);

    //tags control
    Route::get('/admin/index-tags', [HotelTagController::class, 'index']);
    Route::get('/admin/show-tag/{id}', [HotelTagController::class, 'show']);
    Route::post('/admin/store-tag', [HotelTagController::class, 'store']);
    Route::post('/admin/update-tag/{id}', [HotelTagController::class, 'update']);
    Route::delete('/admin/destroy-tag/{id}', [HotelTagController::class, 'destroy']);

    //facility control
    Route::get('/admin/index-facility', [HotelFacilityController::class, 'index']);
    Route::get('/admin/show-facility/{id}', [HotelFacilityController::class, 'show']);
    Route::post('/admin/store-facility', [HotelFacilityController::class, 'store']);
    Route::post('/admin/update-facility/{id}', [HotelFacilityController::class, 'update']);
    Route::delete('/admin/destroy-facility/{id}', [HotelFacilityController::class, 'destroy']);

    //hotels control
    Route::get('/admin/index-hotels', [HotelManagementController::class, 'index']);
    Route::get('/admin/show-hotel/{id}', [HotelManagementController::class, 'show']);
    Route::delete('/admin/destroy-hotel/{id}', [HotelManagementController::class, 'destroy']);

    //admin logout
    Route::post('/admin/logout', [AdminAuthController::class, 'logout']);
});

//================================================================================================

//public hosts routes
Route::post('/host/register', [HostAuthController::class, 'register']);
Route::post('/host/login', [HostAuthController::class, 'login']);
Route::post('/host/email/verification-notification', [HostEmailVerificationController::class, 'sendVerificationEmail'])->middleware('auth:sanctum');
Route::get('/host/verify-email/{id}/{hash}', [HostEmailVerificationController::class, 'verify'])->name('verification.verify')->middleware('auth:sanctum');
Route::post('/host/forgot-password', [HostForgotPasswordController::class, 'forgotPassword']);
Route::post('/host/reset-password', [HostForgotPasswordController::class, 'reset']);

//protected hosts routes
Route::group(['middleware' => ['auth:sanctum']], function () {
    //host profile control
    Route::get('/host/profile/{id}', [HostProfileController::class, 'index']);
    Route::post('/host/profile-update/{id}', [HostProfileController::class, 'update']);

    //host hotel control
    Route::get('/host/show-hotel/{id}', [HotelController::class, 'show']);
    Route::post('/host/hotels-store', [HotelController::class, 'store']);
    Route::post('/host/hotels-update/{id}', [HotelController::class, 'update']);
    Route::delete('/host/destroy-hotel/{id}', [HotelController::class, 'destroy']);

    //host location control
    Route::post('/host/hotel-location', [HotelLocationController::class, 'store']);
    Route::post('/host/hotel-update-location/{id}', [HotelLocationController::class, 'update']);
    Route::delete('/host/hotel-delete-location/{id}', [HotelLocationController::class, 'destroy']);

    //host logout
    Route::post('/host/logout', [HostAuthController::class, 'logout']);
});

//================================================================================================

//public users routes
Route::post('/user/register', [AuthController::class, 'register']);
Route::post('/user/upload-image', [AuthController::class, 'uploadImage']);
Route::post('/user/login', [AuthController::class, 'login']);
Route::post('/user/forgot-password', [ForgotPasswordController::class, 'forgotPassword']);
Route::post('/user/reset-password', [ForgotPasswordController::class, 'reset']);
Route::post('/user/verify-code', [ForgotPasswordController::class, 'verifyCode']);
Route::post('/user/email/verification-notification', [EmailVerificationController::class, 'sendVerificationEmail'])->middleware('auth:sanctum');
Route::get('/user/verify-email/{id}/{hash}', [EmailVerificationController::class, 'verify'])->name('verification.verify')->middleware('auth:sanctum');

//protected users routes
Route::group(['middleware' => ['auth:sanctum']], function () {

    //user home control
    Route::get('/user/get-hotel-tags', [HomeController::class, 'index']);
    Route::get('/user/hotels/tags/{tagID}', [HomeController::class, 'getHotelsByTagId']);
    Route::get('/user/hotel-detail/{id}', [HomeController::class, 'getHotelDetailByID']);
    Route::post('/user/hotel-detail/{id}', [HomeController::class, 'getHotelDetailByID']);

    //user review control
    Route::post('/user/reviews', [HotelReviewController::class, 'createReview']);
    Route::post('/user/reviews/{id}', [HotelReviewController::class, 'updateReview']);
    Route::get('/user/hotels/{hotelId}/reviews', [HotelReviewController::class, 'getHotelReviews']);

    //user profile control
    Route::get('/user/profile/{id}', [UserProfileController::class, 'index']);
    Route::post('/user/image', [UserProfileController::class, 'updateImage']);
    Route::post('/user/update-profile/{id}', [UserProfileController::class, 'update']);

    //user search control
    Route::get('/hotels/search/{keyword}', [SearchController::class, 'searchHotels']);

    //user booking control
    Route::post('/user/booking', [BookingController::class, 'book']);
    Route::get('/user/booking/ticket/{id}', [BookingController::class, 'getTicket']);
    Route::get('hotels/ongoing', [BookingController::class, 'getHotelsByStatus'])->name('hotels.ongoing')->defaults('status', 'ongoing');
    Route::get('hotels/completed', [BookingController::class, 'getHotelsByStatus'])->name('hotels.completed')->defaults('status', 'completed');
    Route::get('hotels/cancelled', [BookingController::class, 'getHotelsByStatus'])->name('hotels.cancelled')->defaults('status', 'cancelled');

    //user logout
    Route::post('/user/logout', [AuthController::class, 'logout']);
});


