<?php

use App\Http\Controllers\Client\AppointmentController;
use App\Http\Controllers\Client\DashboardController;
use App\Http\Controllers\Public\AuthController;
use App\Http\Controllers\Public\DoctorController;
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

Route::prefix('portal')->name('client.')->middleware('auth:client')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::prefix('appointments')->name('appointments.')->group(function () {
        Route::get('/', [AppointmentController::class, 'index'])->name('index');
        Route::get('/create', [AppointmentController::class, 'create'])->name('create');
        Route::post('/', [AppointmentController::class, 'store'])
            ->middleware('throttle:10,1')
            ->name('store');
        Route::get('/{appointment:uuid}/edit', [AppointmentController::class, 'edit'])->name('edit');
        Route::put('/{appointment:uuid}', [AppointmentController::class, 'update'])
            ->middleware('throttle:10,1')
            ->name('update');
    });

    Route::prefix('doctors')->name('doctors.')->group(function () {
        Route::get('/', [App\Http\Controllers\Client\DoctorController::class, 'index'])->name('index');
        Route::get('/{doctor}', [App\Http\Controllers\Client\DoctorController::class, 'show'])->name('show');
    });

    Route::prefix('services')->name('services.')->group(function () {
        Route::get('/', [App\Http\Controllers\Client\MedicalServiceController::class, 'index'])->name('index');
        Route::get('/{medicalService}', [App\Http\Controllers\Client\MedicalServiceController::class, 'show'])->name('show');
    });
});
