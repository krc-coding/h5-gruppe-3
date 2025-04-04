<?php

use App\Http\Controllers\DataController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\GroupController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserAuthController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\UserController;

// these route is only baseURL/api/
Route::post('/login', [UserController::class, 'login']);
Route::delete('/logout/{user}', [UserController::class, 'logout']);

// but there has the prefix after e.g. baseURL/api/data/
Route::prefix('/data')->group(function () {
    Route::post('/create', [DataController::class, 'createData']);
    Route::get('/', [DataController::class, 'getAllData']);
    Route::get('/group/{group}', [DataController::class, 'getByGruop']);
    Route::get('/device/{device}', [DataController::class, 'getByDevice']);
});
Route::prefix('/device')->group(function () {
    Route::post('/create', [DeviceController::class, 'createDevice']);
    Route::get('/', [DeviceController::class, 'getAllDevices']);
    Route::get('/exists', [DeviceController::class, 'getDeviceByUuid']);
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
Route::prefix('/group')->group(function () {
    Route::post('/create', [GroupController::class, 'create']);
    Route::get('/', [GroupController::class, 'getAllGroups']);
    Route::get('/uuid', [GroupController::class, 'getByGroupUuid']);
    Route::get('/user/{user}', [GroupController::class, 'getByUserId']);

    // you kan make prefix within a prefix.
    Route::prefix('/{group}')->group(function () {
        Route::post('/add', [GroupController::class, 'addDeviceToGroup']);
        Route::get('/', [GroupController::class, 'getByGroupId']);
        Route::put('/', [GroupController::class, 'update']);
        Route::patch('/remove/{device}', [GroupController::class, 'removeDeviceFromGroup']);
        Route::delete('/', [GroupController::class, 'delete']);
    });
});
Route::prefix('/search')->group(function () {
    Route::get('/', [GroupController::class, 'search']);
});
