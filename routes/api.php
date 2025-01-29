<?php
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\StyleController;
use Illuminate\Http\Request;
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

Route::prefix('styles')->group(function () {
    Route::get('/', [StyleController::class, 'index']);
    Route::get('/{id}', [StyleController::class, 'show']);
    Route::post('/', [StyleController::class, 'create']);
    Route::put('/{id}', [StyleController::class, 'update']);
    Route::delete('/{id}', [StyleController::class, 'destroy']);
    Route::get('/{id}/projects', [StyleController::class, 'getProjectsByStyle']);
});

Route::put('/projects/update/{id}',[ProjectController::class,'update']);
Route::post('/projects/create',[ProjectController::class,'create']);


Route::apiResource('/projects',ProjectController::class);

Route::get('/users/{id}/projects', [ProjectController::class, 'getProjectsByUser']);


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


