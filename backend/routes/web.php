<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\ProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/logout', [AuthController::class, 'logout']);

Route::middleware('auth')->group(function () {
    Route::get('/api/user', function (Request $request) {
        return response()->json($request->user());
    });
    Route::get('/api/tasks', [TaskController::class, 'index']);
    Route::get('/api/tasks/{id}', [TaskController::class, 'show']);
    Route::patch('/api/tasks/{id}/status', [TaskController::class, 'updateStatus']);
    Route::post('/api/tasks/{id}/submit', [TaskController::class, 'submit']);
    Route::get('/api/dashboard', [DashboardController::class, 'index']);

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';