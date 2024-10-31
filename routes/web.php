<?php

use App\Filament\Company\Pages\EditPhone;
use App\Filament\Company\Pages\PendingAccount;
use App\Filament\Company\Pages\VerifyOtp;
use App\Filament\User\Pages\EditPhone as PagesEditPhone;
use App\Filament\User\Pages\VerifyOtp as PagesVerifyOtp;
use App\Http\Middleware\NotApprovedScreen;
use App\Http\Middleware\NotVerifiedScreen;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([NotVerifiedScreen::class])->group(function () {
    Route::get('/company/verify-phone', VerifyOtp::class)->name('verify-company-phone');
    Route::get('/company/edit-phone', EditPhone::class)->name('edit-company-phone');

    Route::get('/user/verify-phone', PagesVerifyOtp::class)->name('verify.user.phone');
    Route::get('/user/edit-phone', PagesEditPhone::class)->name('edit.user.phone');
});
Route::get('/pending-account', PendingAccount::class)->middleware(NotApprovedScreen::class)->name('pending.account');

Route::get('/unauthorized', function () {
    return response()->json(
    [
        "status" => false,
        "message" => "unauthenticated",
        "errors" => ["Your are not authenticated"],
        "data" => [],
        "notes" => []
    ]
    , 401);
});
