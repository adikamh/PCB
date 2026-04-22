<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class ViewComposerServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Share cookie consent ke semua view
        View::composer('*', function ($view) {
            $cookieConsentGiven = false;
            
            // Cek dari user yang login
            if (Auth::check() && method_exists(Auth::user(), 'hasAcceptedCookies')) {
                $cookieConsentGiven = Auth::user()->hasAcceptedCookies();
            } 
            // Cek dari cookie untuk guest
            elseif (Cookie::has('cookie_consent')) {
                $cookieConsentGiven = Cookie::get('cookie_consent') === 'true';
            }
            
            $view->with('cookieConsentGiven', $cookieConsentGiven);
        });
    }

    public function register()
    {
        //
    }
}