<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class NotVerifiedScreen
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::guard('employee')->user();
        $user2 = Auth::user();

        if ($user?->is_phone_verified)
            return redirect()->to('/company');

        if ($user2?->is_phone_verified_for_web_registeration)
            return redirect()->to('/user');


        return $next($request);
    }
}
