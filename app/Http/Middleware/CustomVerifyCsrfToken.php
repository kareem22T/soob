<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CustomVerifyCsrfToken extends VerifyCsrfToken
{
    protected $except = [
        '/livewire/update', // Add URIs to exclude from CSRF verification
    ];
}
