<?php

use Illuminate\Support\Facades\Route;

Route::get('/medical-areas', [\App\Http\Controllers\Public\MedicalAreaController::class, 'getPublicList'])
    ->name('medical-areas.index');

Route::get('/user', function (\Illuminate\Http\Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
