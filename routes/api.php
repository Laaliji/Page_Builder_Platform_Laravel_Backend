<?php
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TemplateController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;


Route::prefix('auth')->group(function () {
    // Public auth routes
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    
    // GitHub OAuth routes
    Route::get('/github/redirect', [AuthController::class, 'redirectToGitHub']);
    Route::get('/github/callback-direct', [AuthController::class, 'handleGitHubCallbackDirect']);
    
    // GitHub diagnostic route
    Route::get('/github/test-config', function() {
        return response()->json([
            'client_id' => config('services.github.client_id'),
            'redirect_configured' => !empty(config('services.github.redirect')),
            'scopes' => ['repo', 'user'], 
            'callback_url' => config('services.github.redirect')
        ]);
    });
    
    // Protected auth routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/update-password', [AuthController::class, 'updatePassword']);
        
        // GitHub account management for existing users
        Route::get('/github/link', [AuthController::class, 'redirectToGitHub']);
        Route::get('/github/callback', [AuthController::class, 'handleGitHubCallback']);
        Route::delete('/github/unlink', [AuthController::class, 'unlinkGitHub']);
        Route::get('/github/status', [AuthController::class, 'getGitHubConnectionStatus']);
    });
});

// Template routes (public)
Route::get('/templates', [TemplateController::class, 'index']);
Route::get('/templates/{id}', [TemplateController::class, 'show']);

// Protected routes

    // Template actions
    Route::post('/api/pages/create', [PageController::class, 'createPage']);
    Route::post('/api/pages/from-template', [PageController::class, 'createPageFromTemplate']); 
    // Existing routes
    Route::post('/projects/update/{id}', [ProjectController::class, 'update']);
    Route::post('/usersProfile/update/{id}', [ProfileController::class, 'update']);
    Route::apiResource('/usersProfile', ProfileController::class);
    Route::apiResource('/projects', ProjectController::class);
    Route::apiResource('/pages', PageController::class);
    Route::get('/users/{id}/projects', [ProjectController::class, 'getProjectsByUser']);
    Route::get('/user/checkGitHubConnection/{id}', [UserController::class, 'isConnectedWithGitHub']);
    Route::get('/pages/existePages/{id}', [PageController::class, 'ExistePages']);
    Route::get('/page/{id}', [PageController::class, 'showPage']);
    Route::post('/page/updateMetaData/{id}', [PageController::class, 'updatePageMetaData']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });


// Log viewing route for debugging
Route::get('/debug/recent-logs', function() {
    $logFile = storage_path('logs/laravel.log');
    $logs = [];
    
    if (file_exists($logFile)) {
        $logContent = file_get_contents($logFile);
        $logs = array_slice(explode("\n", $logContent), -50); // Get last 50 lines
    }
    
    return response()->json(['logs' => $logs]);
});

// Public routes
Route::get('pages/shared/{id}', [PageController::class, 'showPagesShared']);

Route::get('/hash', function () {
    return Hash::make('123456789');
});


