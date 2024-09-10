<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;


Route::group(['middleware' => 'api', 'prefix' => 'auth'], function ($router) {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);

});

Route::middleware('auth:api')->group(function () {
    Route::get('users', [UserController::class, 'index'])->middleware('role:admin|manager');
    Route::get('users/{user}', [UserController::class, 'show'])->middleware('role:user|admin|manager');
    Route::put('users/{user}', [UserController::class, 'update'])->middleware('role:user|admin|manager');
    Route::delete('users/{user}', [UserController::class, 'destroy'])->middleware('role:user|admin|manager');
});

Route::post('users', [UserController::class, 'store']);

// Task routes
Route::middleware('auth:api')->group(function () {
    Route::get('tasks', [TaskController::class, 'index'])->middleware('role:admin|manager');
    Route::get('tasks/{task}', [TaskController::class, 'show'])->middleware('role:user|admin|manager');
    Route::post('tasks', [TaskController::class, 'store'])->middleware('role:admin|manager');
    Route::put('tasks/{task}', [TaskController::class, 'update'])->middleware('role:user|admin|manager');
    Route::delete('tasks/{task}', [TaskController::class, 'destroy'])->middleware('role:admin|manager');
});
