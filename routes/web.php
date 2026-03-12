<?php

use App\Http\Controllers\ScheduleController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ScheduleController::class, 'index']);
Route::get('/form', [ScheduleController::class, 'index'])->name('form.index');
Route::post('/form', [ScheduleController::class, 'store'])->name('form.store');
Route::patch('/form/{id}', [ScheduleController::class, 'update'])->name('form.update');
Route::patch('/form/{id}/edit', [ScheduleController::class, 'edit'])->name('form.edit');