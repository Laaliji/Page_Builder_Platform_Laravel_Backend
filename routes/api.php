<?php
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\GitHubAuthController;
use App\Http\Controllers\ProjectController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    // Normal Auth Routes
    Route::post('/signup', [AuthController::class, 'register'])->name('auth.signup');
    Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
   
    // GitHub OAuth Routes
    Route::get('/github', [GitHubAuthController::class, 'loginWithGitHub'])->name('github.login');
    Route::get('/github/callback', [GitHubAuthController::class, 'handleGitHubCallback'])->name('github.callback');
   
    // Protected GitHub Routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::delete('/github/unlink', [GitHubAuthController::class, 'unlinkGitHub'])->name('github.unlink');
        Route::get('/github/status', [GitHubAuthController::class, 'getGitHubConnectionStatus'])->name('github.status');
    });
});

Route::apiResource('/projects',ProjectController::class);
Route::get('/users/{id}/projects', [ProjectController::class, 'getProjectsByUser']);


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


