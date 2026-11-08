<?php

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
    Route::get('/login', [PageController::class, 'login'])->name('login');
    Route::get('/register', [PageController::class, 'register'])->name('register');
});
