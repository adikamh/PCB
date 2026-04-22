<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        $user = Auth::user();
        
        if (in_array($user->role, $roles)) {
            return $next($request);
        }

        if ($user->isUser()) {
            return redirect('/')->with('error', 'Anda tidak memiliki akses ke halaman admin!');
        }

        return redirect('/')->with('error', 'Akses ditolak!');
    }
}