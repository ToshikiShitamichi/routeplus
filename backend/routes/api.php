<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/user', [AuthController::class, 'user'])->middleware('auth:sanctum');
