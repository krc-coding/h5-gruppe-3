<?php

use App\Http\Controllers\DataController;
use App\Http\Controllers\DeviceController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserAuthController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\UserController;

Route::post('/login', [UserController::class, 'login']);
Route::delete('/logout/{user}', [UserController::class, 'logout']);

Route::prefix('/data')->group(function () {
    Route::post('/create', [DataController::class, 'createData']);
    Route::get('/', [DataController::class, 'getAllData']);
    Route::get('/group/{group}', [DataController::class, 'getByGruop']);
    Route::get('/device/{device}', [DataController::class, 'getByDevice']);
});
Route::prefix('/device')->group(function () {
    Route::post('/create', [DeviceController::class, 'createDevice']);
    Route::get('/', [DeviceController::class, 'getAllDevices']);
    Route::get('/{device}', [DeviceController::class, 'getDevice']);
    Route::get('/group/{group}', [DeviceController::class, 'getByGroup']);
    Route::delete('/{device}', [DeviceController::class, 'delete']);
});
Route::prefix('/user')->group(function () {
    Route::post('/', [UserController::class, 'createUser']);
    Route::get('/', [UserController::class, 'getAllUsers']);
    Route::get('/{user}', [UserController::class, 'getUser']);
    Route::put('/{user}', [UserController::class, 'putUser']);
    Route::delete('/{user}', [UserController::class, 'delete']);
});
