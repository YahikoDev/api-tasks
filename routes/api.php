<?php

use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\PriorityController;
use App\Http\Controllers\api\StatusController;
use App\Http\Controllers\api\TaskController;
use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function () {
    Route::post('/signup', [AuthController::class, 'signup']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'priority'
], function () {
    Route::get('/', [PriorityController::class, 'index'])->middleware('auth:api');
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'status'
], function () {
    Route::get('/', [StatusController::class, 'index'])->middleware('auth:api');
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'tasks'
], function () {
    Route::get('/', [TaskController::class, 'index'])->middleware('auth:api');
});