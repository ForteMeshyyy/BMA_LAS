<?php

use App\Http\Controllers\ScheduleController;
use App\Models\Schedule;
use Illuminate\Support\Facades\Route;

Route::get('/', [ScheduleController::class, 'index'])->name('index');
Route::get('/form', [ScheduleController::class, 'index'])->name('form');
Route::post('/form', [ScheduleController::class, 'store'])->name('form');
Route::patch('/form/{id}', [ScheduleController::class, 'update'])->name('form');
