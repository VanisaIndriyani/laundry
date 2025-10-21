<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CustomerController;

// Customer routes
Route::get('/', function () {
    return redirect()->route('login');
})->name('home');

// Customer auth
Route::get('/register', [CustomerController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [CustomerController::class, 'register'])->name('register.post');
Route::get('/login', [CustomerController::class, 'showLoginForm'])->name('login');
Route::post('/login', [CustomerController::class, 'login'])->name('login.post');
Route::get('/logout', [CustomerController::class, 'logout'])->name('logout');

// Customer dashboard
Route::get('/dashboard', [CustomerController::class, 'dashboard'])->name('dashboard');
Route::get('/profile', [CustomerController::class, 'profile'])->name('profile');
Route::post('/profile', [CustomerController::class, 'updateProfile'])->name('profile.update');

// Order tracking
Route::get('/track', [CustomerController::class, 'showTrackForm'])->name('track');
Route::post('/track', [CustomerController::class, 'trackOrder'])->name('track.post');

// Admin auth
Route::get('/admin/login', [AdminController::class, 'loginForm'])->name('admin.login');
Route::post('/admin/login', [AdminController::class, 'login'])->name('admin.login.post');
Route::post('/admin/logout', [AdminController::class, 'logout'])->name('admin.logout');

// Admin dashboard and orders
Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
Route::get('/admin/orders', [AdminController::class, 'orders'])->name('admin.orders');
Route::get('/admin/orders/create', [AdminController::class, 'create'])->name('admin.orders.create');
Route::post('/admin/orders', [AdminController::class, 'store'])->name('admin.orders.store');
Route::post('/admin/orders/{order}/status', [AdminController::class, 'updateStatus'])->name('admin.orders.status');

// Admin customers
Route::get('/admin/customers', [AdminController::class, 'customers'])->name('admin.customers');

// Admin reports
Route::get('/admin/reports', [AdminController::class, 'reports'])->name('admin.reports');
