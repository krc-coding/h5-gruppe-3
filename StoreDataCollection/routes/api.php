<?php

use App\Http\Controllers\DataController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserAuthController;
use App\Http\Controllers\StatusController;

Route::prefix('/data')->group(function () {
    Route::post('/create', [DataController::class, 'createData']);
    Route::get('/', [DataController::class, 'getAllData']);
    Route::get('/group/{group}', [DataController::class, 'getByGruop']);
    Route::get('/device/{device}', [DataController::class, 'getByDevice']);
});
