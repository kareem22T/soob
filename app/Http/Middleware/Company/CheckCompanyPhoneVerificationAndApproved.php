<?php

namespace App\Http\Middleware\Company;

use App\Filament\Company\Pages\VerifyOtp;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckCompanyPhoneVerificationAndApproved
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::guard('company')->user();

        if (!$user->is_phone_verified)
            return redirect()->route('verify.company.phone');

        if (!$user->is_approved)
            return redirect()->route('pending.account');


        return $next($request);
    }
}
