<?php

use App\Http\Controllers\ApiParameterController;
use App\Http\Controllers\ParameterController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ParameterController::class, 'index']);
Route::get('/parameters', [ApiParameterController::class, 'showParameters']);
Route::post('/upload/image', [ParameterController::class, 'uploadParameterImg'])->name('upload.image');
Route::post('/update/image', [ParameterController::class, 'updateParameterImg'])->name('update.image');
Route::post('/delete/image', [ParameterController::class, 'deleteParameterImg'])->name('delete.image');
