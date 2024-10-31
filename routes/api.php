<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\BookingController;
use App\Http\Controllers\API\OfferController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\UserCustomeRequestController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Orion\Facades\Orion;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


// Route to send OTP
Route::post('/send-otp', [AuthController::class, 'sendOtp']);

// Route to verify OTP
Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);


// User registration route
Route::post('/register', [UserController::class, 'userRegister']);

// User login route
Route::post('/login', [UserController::class, 'userLogin']);

// User logout route
Route::middleware('auth:sanctum')->post('/logout', [UserController::class, 'userLogout']);



Route::middleware('auth:sanctum')->group(function () {

    Route::post('/bookings', [BookingController::class, 'createBooking']); // Create a new booking
    Route::get('/bookings', [BookingController::class, 'getBookings']); // Retrieve bookings for the authenticated user
    Route::put('/bookings/{id}', [BookingController::class, 'updateBooking']); // Update a specific booking
    Route::delete('/bookings/{id}', [BookingController::class, 'deleteBooking']); // Delete a specific booking

    Route::get('/custom-requests', [UserCustomeRequestController::class, 'get']);
    Route::post('/custom-request', [UserCustomeRequestController::class, 'store']);
    Route::delete('/custom-request/{id}', [UserCustomeRequestController::class, 'cancel']);
});

Route::get('/offers', [OfferController::class, 'getOffers']); // Retrieve bookings for the authenticated user
