<?php
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\GitHubAuthController;
use App\Http\Controllers\ProjectController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;

Route::prefix('auth')->group(function () {
    // Normal Auth Routes
    Route::post('/signup', [AuthController::class, 'register'])->name('auth.signup');
    Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
   
    // GitHub OAuth Routes
    Route::get('/github', [GitHubAuthController::class, 'loginWithGitHub'])->name('github.login');
    Route::get('/github/callback', [GitHubAuthController::class, 'handleGitHubCallback'])->name('github.callback');
   
    // Protected GitHub Routes
    Route::middleware('auth:sanctum')->group(function () {
        
        Route::get('/github/redirect', [AuthController::class, 'redirectToGitHub']);
        Route::get('/github/callback', [AuthController::class, 'handleGitHubCallback']);
        Route::delete('/github/unlink',[AuthController::class, 'unlinkGitHub']);
        Route::get('/github/status', [AuthController::class, 'getGitHubConnectionStatus']);
    
        
    });
});

Route::post('/projects/update/{id}',[ProjectController::class,'update']);

Route::apiResource('/projects',ProjectController::class);

Route::get('/users/{id}/projects', [ProjectController::class, 'getProjectsByUser']);


// Afficher tous les contacts +5 derniers.
Route::get('/contacts/latest', [ContactController::class, 'latest']);

// Afficher tous les contacts
Route::get('/contacts', [ContactController::class, 'index']);

// Afficher un contact par ID
Route::get('/contacts/{id}', [ContactController::class, 'show']);

// Ajouter un contact (POST)
Route::post('/contact', [ContactController::class, 'store']);

// Supprimer un contact par ID
Route::delete('/contacts/{id}', [ContactController::class, 'destroy']);


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


