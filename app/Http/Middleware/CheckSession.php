<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CheckSession
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            Session::put('last_activity', now()->toDateTimeString());
        }
        
        return $next($request);
    }
}