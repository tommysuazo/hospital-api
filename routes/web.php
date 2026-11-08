<?php

use App\Http\Controllers\Public\DoctorController;
use App\Http\Controllers\Public\AuthController;
use App\Http\Controllers\Public\MedicalServiceController;
use App\Http\Controllers\Public\PageController;
use Illuminate\Support\Facades\Route;

Route::name('public.')->group(function () {
    Route::get('/', [PageController::class, 'home'])->name('home');
    Route::get('/doctors', [DoctorController::class, 'index'])->name('doctors.index');
    Route::get('/doctors/{doctor}', [DoctorController::class, 'show'])->name('doctors.show');
    Route::get('/services', [PageController::class, 'services'])->name('services.index');
    Route::get('/services/{medicalService}', [MedicalServiceController::class, 'show'])
        ->name('services.show');
    Route::get('/about', [PageController::class, 'about'])->name('about');
    Route::get('/contact', [PageController::class, 'contact'])->name('contact');
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1')->name('login.store');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:5,1')->name('register.store');

    Route::prefix('auth')->name('auth.')->group(function () {
        Route::get('/verify-code', [AuthController::class, 'showVerification'])->name('verify.show');
        Route::post('/verify-code', [AuthController::class, 'verifyCode'])->middleware('throttle:10,1')->name('verify.store');
        Route::post('/resend-code', [AuthController::class, 'resendCode'])->middleware('throttle:3,1')->name('code.resend');
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    });

    Route::prefix('password')->name('password.')->group(function () {
        Route::get('/forgot', [AuthController::class, 'showForgotPassword'])->name('forgot.show');
        Route::post('/forgot', [AuthController::class, 'sendPasswordResetCode'])->middleware('throttle:5,1')->name('forgot.store');
        Route::get('/reset', [AuthController::class, 'showResetPassword'])->name('reset.show');
        Route::post('/reset', [AuthController::class, 'resetPassword'])->middleware('throttle:5,1')->name('reset.store');
    });
});
