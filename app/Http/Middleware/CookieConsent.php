<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class CookieConsent
{
    public function handle(Request $request, Closure $next)
    {
        $consentGiven = false;
        
        if (Auth::check()) {
            $consentGiven = Auth::user()->hasAcceptedCookies();
        } else {
            $consentGiven = Cookie::get('cookie_consent') === 'true';
        }
        
        view()->share('cookieConsentGiven', $consentGiven);
        
        return $next($request);
    }
}