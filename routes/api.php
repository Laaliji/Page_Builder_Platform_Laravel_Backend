<?php
use App\Http\Controllers\Auth\AuthController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::prefix('auth')->group(function () {
    Route::post('/signup', [AuthController::class, 'register'])->name('auth.signup');
    Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
   
    Route::get('/github', [AuthController::class, 'loginWithGitHub'])->name('github.login');
    Route::get('/github/callback', [AuthController::class, 'handleGitHubCallback'])->name('github.callback');
   
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/github/redirect', [AuthController::class, 'redirectToGitHub'])->name('github.redirect');
        Route::delete('/github/unlink', [AuthController::class, 'unlinkGitHub'])->name('github.unlink');
        Route::get('/github/status', [AuthController::class, 'getGitHubConnectionStatus'])->name('github.status');
    });
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
})->name('user.info');
