<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\Auth\AuthTokenController;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::apiResource('task', TaskController::class);
    Route::apiResource('users', UserController::class);
    Route::apiResource('chat', ChatController::class);
    Route::apiResource('message', MessageController::class);
    Route::patch('task/toggledone/{task}', [TaskController::class, 'toggledone']);
});

Route::prefix('auth/token')->group(function () {
    Route::post('/login', [AuthTokenController::class, 'login']);
    Route::post('/register', [AuthTokenController::class, 'register']);
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::post('/logout', [AuthTokenController::class, 'logout']);
    });
});