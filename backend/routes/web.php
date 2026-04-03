<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Api\GroupController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TaskPackController;

Route::get('/', function () {
    return view('welcome');
});

// 認証不要
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/logout', [AuthController::class, 'logout']);
Route::post('/auth/register', [AuthController::class, 'register']);
Route::get('/api/invitations/verify', [AdminController::class, 'verifyInvitation']);
Route::get('/api/packs/public', [TaskPackController::class, 'publicPacks']);
Route::post('/auth/register/public', [AuthController::class, 'registerPublic']);
Route::get('/api/packs/public', [TaskPackController::class, 'publicPacks']);
Route::get('/api/portfolio/{userId}', [TaskController::class, 'portfolio']);
Route::post('/auth/register/admin', [AuthController::class, 'registerAdmin']);

// 認証が必要
Route::middleware('auth')->group(function () {
    Route::get('/api/user', fn(Request $request) => response()->json($request->user()));
    Route::get('/api/tasks', [TaskController::class, 'index']);
    Route::get('/api/tasks/{id}', [TaskController::class, 'show']);
    Route::patch('/api/tasks/{id}/status', [TaskController::class, 'updateStatus']);
    Route::post('/api/tasks/{id}/submit', [TaskController::class, 'submit']);
    Route::get('/api/submissions', [TaskController::class, 'submissions']);
    Route::get('/api/dashboard', [DashboardController::class, 'index']);
    Route::post('/api/user/packs', [AuthController::class, 'addPack']);
    Route::post('/api/invitations/join', [AdminController::class, 'joinWithInvitation']);
    Route::get('/api/user/packs', [AuthController::class, 'myPacks']);

    // 管理者のみ
    Route::prefix('api/admin')->group(function () {
        Route::get('/students', [AdminController::class, 'students']);
        Route::get('/students/{id}/progress', [AdminController::class, 'studentProgress']);
        Route::post('/invitations', [AdminController::class, 'createInvitation']);
        Route::get('/invitations', [AdminController::class, 'invitations']);
        Route::get('/groups', [AdminController::class, 'groups']);
        Route::post('/groups', [AdminController::class, 'createGroup']);
        Route::get('/packs', [TaskPackController::class, 'index']);
        Route::post('/packs', [TaskPackController::class, 'store']);
        Route::get('/packs/{id}', [TaskPackController::class, 'show']);
        Route::patch('/packs/{id}', [TaskPackController::class, 'update']);
        Route::delete('/packs/{id}', [TaskPackController::class, 'destroy']);
        Route::post('/packs/{id}/assign', [TaskPackController::class, 'assignToGroup']);
        Route::delete('/packs/{id}/remove', [TaskPackController::class, 'removeFromGroup']);
        Route::get('/tasks', [App\Http\Controllers\Api\Admin\TaskMasterController::class, 'index']);
        Route::post('/tasks', [App\Http\Controllers\Api\Admin\TaskMasterController::class, 'store']);
        Route::patch('/tasks/{taskMaster}', [App\Http\Controllers\Api\Admin\TaskMasterController::class, 'update']);
        Route::delete('/tasks/{taskMaster}', [App\Http\Controllers\Api\Admin\TaskMasterController::class, 'destroy']);
        Route::patch('/packs/{id}/reorder', [TaskPackController::class, 'reorder']);
        Route::get('/all-tasks', [TaskController::class, 'allTasksForAdmin']);
    });

    // 運営者のみ
    Route::prefix('api/operator')->group(function () {
        Route::get('/organizations', [App\Http\Controllers\Api\OperatorController::class, 'organizations']);
        Route::post('/organizations', [App\Http\Controllers\Api\OperatorController::class, 'createOrganization']);
        Route::post('/invitations', [App\Http\Controllers\Api\OperatorController::class, 'createAdminInvitation']);
        Route::get('/admins', [App\Http\Controllers\Api\OperatorController::class, 'admins']);
        Route::get('/tasks', [App\Http\Controllers\Api\OperatorController::class, 'officialTasks']);
        Route::post('/tasks', [App\Http\Controllers\Api\OperatorController::class, 'createOfficialTask']);
        Route::patch('/tasks/{taskMaster}', [App\Http\Controllers\Api\OperatorController::class, 'updateOfficialTask']);
        Route::delete('/tasks/{taskMaster}', [App\Http\Controllers\Api\OperatorController::class, 'deleteOfficialTask']);
    });

    // グループ関連
    Route::get('/api/groups/public', [GroupController::class, 'publicGroups']);
    Route::get('/api/groups/my', [GroupController::class, 'myGroups']);
    Route::post('/api/groups/{id}/join', [GroupController::class, 'join']);
});
