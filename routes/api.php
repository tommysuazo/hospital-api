<?php

use App\Http\Controllers\Public\MedicalSpecialtyController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/medical-specialties', [MedicalSpecialtyController::class, 'getPublicList'])
    ->name('medical-specialties.index');

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
