<?php

use Illuminate\Support\Facades\Route;

Route::name('public.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Public\PageController::class, 'home'])->name('home');
    Route::get('/doctors', [\App\Http\Controllers\Public\DoctorController::class, 'index'])->name('doctors.index');
    Route::get('/doctors/{doctor}', [\App\Http\Controllers\Public\DoctorController::class, 'show'])->name('doctors.show');
    Route::get('/services', [\App\Http\Controllers\Public\PageController::class, 'services'])->name('services.index');
    Route::get('/services/{medicalService}', [\App\Http\Controllers\Public\MedicalServiceController::class, 'show'])
        ->name('services.show');
    Route::get('/about', [\App\Http\Controllers\Public\PageController::class, 'about'])->name('about');
    Route::get('/contact', [\App\Http\Controllers\Public\PageController::class, 'contact'])->name('contact');
    Route::get('/login', [\App\Http\Controllers\Public\AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [\App\Http\Controllers\Public\AuthController::class, 'login'])->middleware('throttle:10,1')->name('login.store');
    Route::get('/register', [\App\Http\Controllers\Public\AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [\App\Http\Controllers\Public\AuthController::class, 'register'])->middleware('throttle:5,1')->name('register.store');

    Route::prefix('auth')->name('auth.')->group(function () {
        Route::get('/verify-code', [\App\Http\Controllers\Public\AuthController::class, 'showVerification'])->name('verify.show');
        Route::post('/verify-code', [\App\Http\Controllers\Public\AuthController::class, 'verifyCode'])->middleware('throttle:10,1')->name('verify.store');
        Route::post('/resend-code', [\App\Http\Controllers\Public\AuthController::class, 'resendCode'])->middleware('throttle:3,1')->name('code.resend');
        Route::post('/logout', [\App\Http\Controllers\Public\AuthController::class, 'logout'])->name('logout');
    });

    Route::prefix('password')->name('password.')->group(function () {
        Route::get('/forgot', [\App\Http\Controllers\Public\AuthController::class, 'showForgotPassword'])->name('forgot.show');
        Route::post('/forgot', [\App\Http\Controllers\Public\AuthController::class, 'sendPasswordResetCode'])->middleware('throttle:5,1')->name('forgot.store');
        Route::get('/reset', [\App\Http\Controllers\Public\AuthController::class, 'showResetPassword'])->name('reset.show');
        Route::post('/reset', [\App\Http\Controllers\Public\AuthController::class, 'resetPassword'])->middleware('throttle:5,1')->name('reset.store');
    });
});

Route::prefix('portal')->name('client.')->middleware('auth:client')->group(function () {
    Route::get('/', [\App\Http\Controllers\Client\DashboardController::class, 'index'])->name('dashboard');

    Route::prefix('appointments')->name('appointments.')->group(function () {
        Route::get('/create', [\App\Http\Controllers\Client\AppointmentController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\Client\AppointmentController::class, 'store'])
            ->middleware('throttle:10,1')
            ->name('store');
    });

    Route::prefix('doctors')->name('doctors.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Client\DoctorController::class, 'index'])->name('index');
        Route::get('/{doctor}', [\App\Http\Controllers\Client\DoctorController::class, 'show'])->name('show');
    });

    Route::prefix('services')->name('services.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Client\MedicalServiceController::class, 'index'])->name('index');
        Route::get('/{medicalService}', [\App\Http\Controllers\Client\MedicalServiceController::class, 'show'])->name('show');
    });
});
