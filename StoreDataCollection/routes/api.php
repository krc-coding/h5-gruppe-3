<?php

use App\Http\Controllers\DataController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserAuthController;
use App\Http\Controllers\StatusController;

Route::prefix('/data')->group(function () {
    Route::post('/create', [DataController::class, 'createData']);
    Route::get('/get', [DataController::class, 'getAllData']);
    Route::put('/{data}', [DataController::class, 'updateData']);
    Route::delete('/{data}', [DataController::class], 'delete');
});
