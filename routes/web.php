<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Auth\AuthLoginController;
use App\Http\Controllers\Auth\AuthRegisterController;
use App\Http\Controllers\Auth\EmailController;
use App\Http\Controllers\LandingPageController;
use Illuminate\Support\Facades\Route;


Route::get('/', [LandingPageController::class, 'index'])->middleware('noAdminLanding')->name('landing');
Route::get('/logout', [AuthLoginController::class, 'destroy'])->name('logout');

Route::middleware('guest')->group(function() {
    Route::get('/login', [AuthLoginController::class, 'index'])->name('login');
    Route::post('/login', [AuthLoginController::class, 'store'])->name('login.store');
    Route::get('/register', [AuthRegisterController::class, 'index'])->name('register.index');
    Route::post('/register', [AuthRegisterController::class, 'store'])->name('register.store');
});

Route::middleware(['auth', 'unverified'])->group(function() {
    Route::get('/email/verify', [EmailController::class, 'create'])->name('verification.notice');
    Route::post('/email/update', [EmailController::class, 'update'])->name('verification.update');
});
Route::get('/email/verify/{id}/{hash}', [EmailController::class, 'verify'])->middleware(['signed'])->name('verification.verify');
Route::post('/email/verification-notification', [EmailController::class, 'resend'])->middleware(['auth', 'throttle:6,1'])->name('verification.send');

Route::prefix('panel-control')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('product', ProductController::class);
});
