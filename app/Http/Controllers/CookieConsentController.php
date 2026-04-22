<?php
// app/Http/Controllers/CookieConsentController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class CookieConsentController extends Controller
{
    public function accept(Request $request)
    {
        // Simpan consent ke database jika user login
        if (Auth::check()) {
            $user = Auth::user();
            $user->cookie_consent = true;
            $user->cookie_consent_at = now();
            $user->save();
        }
        
        // Simpan consent ke cookie untuk guest (expired 1 tahun)
        Cookie::queue('cookie_consent', 'true', 60 * 24 * 365);
        
        // Set cookies yang diperlukan
        $this->setNecessaryCookies();
        
        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Cookie consent accepted']);
        }
        
        return back()->with('success', 'Terima kasih telah menerima cookie.');
    }
    
    public function reject(Request $request)
    {
        // Hapus consent
        if (Auth::check()) {
            $user = Auth::user();
            $user->cookie_consent = false;
            $user->cookie_consent_at = null;
            $user->save();
        }
        
        // Hapus cookie consent
        Cookie::queue(Cookie::forget('cookie_consent'));
        
        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Cookie consent rejected']);
        }
        
        return back()->with('info', 'Cookie consent ditolak. Beberapa fitur mungkin tidak berjalan optimal.');
    }
    
    private function setNecessaryCookies()
    {
        // Set cookies yang diperlukan untuk fungsi website
        $cookies = [
            'site_preferences' => json_encode([
                'theme' => 'auto',
                'language' => 'id'
            ]),
            'session_secure' => 'enabled'
        ];
        
        foreach ($cookies as $name => $value) {
            if (!Cookie::has($name)) {
                Cookie::queue($name, $value, 60 * 24 * 30); // 30 hari
            }
        }
    }
}