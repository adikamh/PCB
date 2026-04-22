<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\CookieConsentController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/cetak-pcb', function () {return view('pages.cetak-pcb');})->name('cetak-pcb');
Route::get('/products', [HomeController::class, 'index'])->name('products.index');
Route::get('/product/{slug}', [App\Http\Controllers\ProductController::class, 'show'])->name('products.show');
Route::get('/api/products/category/{slug}', [App\Http\Controllers\ProductController::class, 'getByCategory'])->name('api.products.by-category');
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/check-session', [LoginController::class, 'checkSession'])->middleware('auth');
Route::post('/cookie/accept', [CookieConsentController::class, 'accept'])->name('cookie.accept');
Route::post('/cookie/reject', [CookieConsentController::class, 'reject'])->name('cookie.reject');

Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', function () {return view('admin.dashboard');})->name('admin.dashboard');
    Route::get('/users', function () { $users = \App\Models\User::all();return view('admin.users', compact('users'));})->name('admin.users');
    Route::resource('products', AdminProductController::class)->except(['show']);
    Route::get('/orders', function () {return view('admin.orders');})->name('admin.orders');
    Route::get('index', [AdminProductController::class, 'index'])->name('admin.products.index');
    Route::get('create', [AdminProductController::class, 'create'])->name('admin.products.create');
    Route::post('store', [AdminProductController::class, 'store'])->name('admin.products.store');
    Route::get('edit/{id}', [AdminProductController::class, 'edit'])->name('admin.products.edit');
    Route::put('update/{id}', [AdminProductController::class, 'update'])->name('admin.products.update');
    Route::delete('destroy/{id}', [AdminProductController::class, 'destroy'])->name('admin.products.destroy');

});