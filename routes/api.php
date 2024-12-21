<?php

use App\Events\MyEvent;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\BookingController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\ChatController;
use App\Http\Controllers\API\Company\RequestsController;
use App\Http\Controllers\API\Company\RegistrationController as CompanyRegistrationController;
use App\Http\Controllers\API\NotificationController;
use App\Http\Controllers\API\OfferController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\UserCustomeRequestController;
use App\Http\Controllers\API\UserSettingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Orion\Facades\Orion;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register-company', [CompanyRegistrationController::class, 'register']);
Route::post('/login-company', [CompanyRegistrationController::class, 'login']);
Route::post('/change-phone-company', [CompanyRegistrationController::class, 'updatePhone'])->middleware('auth:sanctum');
Route::get('/user-company', [CompanyRegistrationController::class, 'getUser'])->middleware('auth:sanctum');
Route::get('/send-otp-company', [CompanyRegistrationController::class, 'sendOtp']);
Route::post('/verify-otp-company', [CompanyRegistrationController::class, 'verifyOtp']);
Route::post('/reset-password-company', [CompanyRegistrationController::class, 'resetPassword'])->middleware('auth:sanctum');
Route::post('/store-offer', [OfferController::class, 'store'])->middleware('auth:sanctum');
Route::get('/company/get-requests', [RequestsController::class, 'get']);
Route::post('/test-msg-new', [RequestsController::class, 'test']);

Route::middleware('auth:sanctum')->group(function () {
    // Send message by employee
    Route::post('/chat/send-by-employee', [ChatController::class, 'sendMessageByEmployee']);

    // Send message by user
    Route::post('/chat/send-by-user', [ChatController::class, 'sendMessageByUser']);

    // Get chats for user or employee
    Route::get('/chat', [ChatController::class, 'getChats']);

    // Add an offer to a custom request
    Route::post('/chat/add-offer', [ChatController::class, 'addOffer']);

    // Get chat messages
    Route::get('/chat/{chatId}/messages', [ChatController::class, 'getChatMessages']);
});

// Route to send OTP
Route::post('/send-otp', [AuthController::class, 'sendOtp']);

// Route to verify OTP
Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);


// User registration route
Route::post('/register', [AuthController::class, 'register']);

// User login route
Route::post('/login', [AuthController::class, 'login']);

Route::get('/user', [AuthController::class, 'getUser'])->middleware('auth:sanctum');

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
Route::get('/categories', [CategoryController::class, 'getCategories']); // Retrieve bookings for the authenticated user
Route::get('/getNotification', [NotificationController::class, 'getNotification'])->middleware('auth:sanctum'); // Retrieve bookings for the authenticated user
Route::get('/readAll', [NotificationController::class, 'readAll'])->middleware('auth:sanctum'); // Retrieve bookings for the authenticated user
Route::get('/company-offers', [OfferController::class, 'getOffers'])->middleware('auth:sanctum'); // Retrieve bookings for the authenticated user
Route::get('/get-user-setting', [UserSettingController::class, 'getUserSettings']);
