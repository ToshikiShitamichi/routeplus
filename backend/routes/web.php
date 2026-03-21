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

// 認証不要
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/logout', [AuthController::class, 'logout']);

Route::middleware('auth')->group(function () {
    Route::get('/api/user', fn(Request $request) => response()->json($request->user()));
    Route::get('/api/tasks', [TaskController::class, 'index']);
    Route::get('/api/tasks/{id}', [TaskController::class, 'show']);
    Route::patch('/api/tasks/{id}/status', [TaskController::class, 'updateStatus']); // ← 追加
    Route::post('/api/tasks/{id}/submit', [TaskController::class, 'submit']);
    Route::get('/api/dashboard', [DashboardController::class, 'index']);
});

// Bladeルート（React SPAでは使わないが残しておいてOK）
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
