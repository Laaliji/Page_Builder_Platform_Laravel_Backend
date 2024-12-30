<?php
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;


Route::prefix('auth/')->group(function () {
    
    Route::post('/signup', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

   
    Route::middleware('auth:sanctum')->group(function () {
        
        Route::get('/github/redirect', [AuthController::class, 'redirectToGitHub']);
        Route::get('/github/callback', [AuthController::class, 'handleGitHubCallback']);
        Route::delete('/github/unlink',[AuthController::class, 'unlinkGitHub']);
        Route::get('/github/status', [AuthController::class, 'getGitHubConnectionStatus']);
    
        
    });
});

Route::post('/projects/update/{id}',[ProjectController::class,'update']);

Route::post('/usersProfile/update/{id}',[ProfileController::class,'update']);

Route::apiResource('/usersProfile',ProfileController::class);

Route::apiResource('/projects',ProjectController::class);

Route::apiResource('/pages',PageController::class);

Route::get('/users/{id}/projects', [ProjectController::class, 'getProjectsByUser']);

Route::get('/user/checkGitHubConnection/{id}', [UserController::class, 'isConnectedWithGitHub']);

Route::get('/pages/existePages/{id}', [PageController::class, 'ExistePages']);

Route::get('/hash', function () {
    return Hash::make('123456789');
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


