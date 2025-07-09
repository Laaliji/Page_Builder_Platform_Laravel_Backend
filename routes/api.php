<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\TemplateController;
use App\Http\Controllers\UserController;

// Public routes
Route::prefix('auth')->group(function () {
    Route::post('/signup', [AuthController::class, 'signup']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/github', [AuthController::class, 'githubAuth']);
});

// Public template routes
Route::get('/templates', [TemplateController::class, 'index']);
Route::get('/templates/{id}', [TemplateController::class, 'show']);

// Public shared page route
Route::get('/shared/{sharedLink}', [PageController::class, 'getSharedPage']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Auth routes
    Route::prefix('auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/user', [AuthController::class, 'user']);
        Route::put('/password', [AuthController::class, 'updatePassword']);
    });
    
    // User routes
    Route::prefix('users')->group(function () {
        Route::get('/{id}/projects', [ProjectController::class, 'getProjectsByUser']);
        Route::get('/{id}/github-status', [UserController::class, 'isConnectedWithGitHub']);
    });
    
    // Project routes
    Route::apiResource('projects', ProjectController::class);
    
    // Page routes
    Route::prefix('projects/{projectId}')->group(function () {
        Route::get('/pages', [PageController::class, 'index']);
        Route::get('/pages/exists', [PageController::class, 'ExistePages']);
    });
    
    Route::apiResource('pages', PageController::class)->except(['index']);
    Route::put('/pages/{id}/metadata', [PageController::class, 'updatePageMetaData']);
    
    // Template routes
    Route::post('/templates/apply', [TemplateController::class, 'applyTemplateToPage']);
});

// Debug route (remove in production)
if (config('app.debug')) {
    Route::get('/debug/logs', function () {
        return response()->json([
            'logs' => array_slice(file(storage_path('logs/laravel.log')), -50)
        ]);
    });
}

Route::get('/hash', function () {
    return Hash::make('123456789');
});


