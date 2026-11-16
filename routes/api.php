<?php

use App\Http\Controllers\Public\MedicalAreaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/medical-areas', [MedicalAreaController::class, 'getPublicList'])
    ->name('medical-areas.index');

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
