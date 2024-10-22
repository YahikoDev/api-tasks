<?php

use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\PriorityController;
use App\Http\Controllers\api\StatusController;
use App\Http\Controllers\api\TaskController;
use App\Http\Middleware\JwtMiddleware;
use Illuminate\Support\Facades\Route;


Route::middleware([JwtMiddleware::class])->prefix('auth')->group(function () {
    Route::post('/signup', [AuthController::class, 'signup'])->withoutMiddleware(JwtMiddleware::class);
    Route::post('/login', [AuthController::class, 'login'])->withoutMiddleware(JwtMiddleware::class);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', function () {
        return auth()->user();
    });
});

Route::middleware([JwtMiddleware::class])->prefix('priority')->group(function () {
    Route::get('/', [PriorityController::class, 'index']);
});

Route::middleware([JwtMiddleware::class])->prefix('status')->group(function () {
    Route::get('/', [StatusController::class, 'index']);
});


Route::middleware([JwtMiddleware::class])->prefix('tasks')->group(function () {
    Route::get('/', [TaskController::class, 'index']);
    Route::post('/create', [TaskController::class, 'store']);
    Route::get('/{id}', [TaskController::class, 'show']);
    Route::put('/update/{id}', [TaskController::class, 'update']);
    Route::delete('/{id}', [TaskController::class, 'destroy']);
});

Route::middleware([JwtMiddleware::class])->prefix('tasks')->group(function () {
    Route::get('/', [TaskController::class, 'index']);
    Route::post('/create', [TaskController::class, 'store']);
    Route::get('/{id}', [TaskController::class, 'show']);
    Route::put('/update/{id}', [TaskController::class, 'update']);
    Route::delete('/{id}', [TaskController::class, 'destroy']);
});

