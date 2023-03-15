<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\AuthWithGoogleController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\UserTestController;
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

Route::get('/test', function () {
    return view('test');
});

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/login/sign-google', [AuthWithGoogleController::class, 'handleProviderCallback']);
Route::get('/users', [UserTestController::class, 'index']);
Route::get('/users/{id}', [UserTestController::class, 'show']);
Route::get('/users/search/{name}', [UserTestController::class, 'search']);
Route::post('forgot-password', [ForgotPasswordController::class, 'forgotPassword']);
Route::post('reset-password', [ForgotPasswordController::class, 'reset']);
Route::post('email/verification-notification', [EmailVerificationController::class, 'sendVerificationEmail'])->middleware('auth:sanctum');
Route::get('verify-email/{id}/{hash}', [EmailVerificationController::class, 'verify'])->name('verification.verify')->middleware('auth:sanctum');


// Protected routes
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/users', [UserTestController::class, 'store']);
    Route::put('/users/{id}', [UserTestController::class, 'update']);
    Route::delete('/users/{id}', [UserTestController::class, 'destroy']);
    Route::post('/logout', [AuthController::class, 'logout']);
});


