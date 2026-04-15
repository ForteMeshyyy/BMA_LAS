<?php

use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\VideoDownloadController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ScheduleController::class, 'index']);
Route::get('/form', [ScheduleController::class, 'index'])->name('form.index');
Route::post('/form', [ScheduleController::class, 'store'])->name('form.store');
Route::patch('/form/{id}', [ScheduleController::class, 'update'])->name('form.update');
Route::patch('/form/{id}/edit', [ScheduleController::class, 'edit'])->name('form.edit');

Route::post('/upload-video', [VideoDownloadController::class, 'upload'])->name('video.upload');
Route::get('/videos', [VideoDownloadController::class, 'index'])->name('video.index');
Route::delete('/videos/delete', [VideoDownloadController::class, 'destroy'])->name('video.destroy');