<?php
use App\Http\Controllers\Auth\AuthController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::prefix('auth/')->group(function () {
    
    Route::post('/signup', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

   
    Route::middleware('auth:sanctum')->group(function () {
        
        Route::get('/github/redirect', [AuthController::class, 'redirectToGitHub']);
        Route::get('/github/callback', [AuthController::class, 'handleGitHubCallback']);
        Route::delete('/github/unlink', [AuthController::class, 'unlinkGitHub']);
        Route::get('/github/status', [AuthController::class, 'getGitHubConnectionStatus']);
    });
});


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
