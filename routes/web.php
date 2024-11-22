<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ComponentController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', function () {
    return view('welcome');
});

// Web routes group
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Profile routes
    Route::controller(ProfileController::class)
        ->prefix('profile')
        ->name('profile.')
        ->group(function () {
            Route::get('/', 'edit')->name('edit');
            Route::patch('/', 'update')->name('update');
            Route::delete('/', 'destroy')->name('destroy');
        });
});

// API routes group
Route::middleware('auth:sanctum')
    ->prefix('api')  // Add prefix for API routes
    ->name('api.')   // Add name prefix for API routes
    ->group(function () {
        // Project routes
        Route::apiResource('projects', ProjectController::class);
        
        // Page routes
        Route::apiResource('pages', PageController::class);
        
        // Component routes
        Route::controller(PageController::class)
            ->prefix('pages/{page}')
            ->name('pages.')
            ->group(function () {
                Route::post('components', 'addComponent')->name('components.add');
                Route::delete('components/{component}', 'removeComponent')->name('components.remove');
                Route::put('components/{component}', 'updateComponent')->name('components.update');
            });
});

// Authentication routes
require __DIR__.'/auth.php';