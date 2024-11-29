<?php

use App\Http\Controllers\Auth\GitHubAuthController;
use Illuminate\Support\Facades\Route;


Route::get('/auth/github/login', [GitHubAuthController::class, 'redirectToGitHub']);
Route::get('/auth/github/callback', [GitHubAuthController::class, 'handleGitHubCallback']);


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/github/login', [GitHubAuthController::class, 'redirectToGitHub']);
    Route::get('/github/callback', [GitHubAuthController::class, 'handleGitHubCallback']);
});

Route::post('/signup', [UserController::class, 'store']);
