<?php

use App\Http\Controllers\ScheduleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/form', [ScheduleController::class, 'store']);
Route::get('/form', [ScheduleController::class, 'index']);
Route::get('/showData', [ScheduleController::class, 'showFormattedData']);
