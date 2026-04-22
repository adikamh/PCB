<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        $rememberedUsername = Cookie::get('remember_username');
        $rememberedChecked = Cookie::has('remember_username');
        
        return view('auth.login', compact('rememberedUsername', 'rememberedChecked'));
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            
            Session::put('user_logged_in', true);
            Session::put('user_username', Auth::user()->username);
            Session::put('user_role', Auth::user()->role);
            Session::put('login_time', now()->toDateTimeString());
            
            if (!Auth::user()->hasAcceptedCookies()) {
                Session::put('show_cookie_banner', true);
                Log::info('Cookie consent belum diberikan', [
                    'username' => Auth::user()->username
                ]);
            }
            
            if ($remember) {
                Cookie::queue('remember_username', $request->username, 60 * 24 * 30);
            } else {
                Cookie::queue(Cookie::forget('remember_username'));
            }
            
            Log::info('User login successful', [
                'username' => Auth::user()->username,
                'role' => Auth::user()->role,
                'ip' => $request->ip(),
                'session_id' => Session::getId()
            ]);
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => true, 
                    'redirect' => Auth::user()->isAdmin() ? '/admin/dashboard' : '/',
                    'role' => Auth::user()->role
                ]);
            }
            
            if (Auth::user()->isAdmin()) {
                return redirect()->intended('/admin/dashboard')->with('success', 'Selamat datang Admin!');
            }
            
            return redirect()->intended('/')->with('success', 'Selamat datang ' . Auth::user()->name . '!');
        }

        Log::warning('Login failed', [
            'username' => $request->username,
            'ip' => $request->ip()
        ]);

        if ($request->ajax()) {
            return response()->json(['success' => false, 'message' => 'Username atau password salah'], 422);
        }

        return back()->withErrors([
            'username' => 'Username atau password salah.',
        ])->onlyInput('username');
    }

    public function logout(Request $request)
    {
        if (Auth::check()) {
            Log::info('User logout', [
                'username' => Auth::user()->username,
                'session_id' => Session::getId()
            ]);
        }
        
        Session::flush();
        
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        Cookie::queue(Cookie::forget('remember_username'));
        
        return redirect('/')->with('info', 'Anda telah logout.');
    }
    
    public function checkSession()
    {
        if (!Auth::check()) {
            return response()->json(['status' => 'not logged in']);
        }
        
        return response()->json([
            'status' => 'logged in',
            'user' => Auth::user()->username,
            'role' => Auth::user()->role,
            'session_id' => Session::getId(),
            'session_data' => Session::all(),
            'cookies' => [
                'remember_username' => Cookie::get('remember_username'),
                'laravel_session' => Cookie::get('laravel_session') ? 'exists' : 'not set',
                'xsrf_token' => Cookie::get('XSRF-TOKEN') ? 'exists' : 'not set'
            ]
        ]);
    }
}