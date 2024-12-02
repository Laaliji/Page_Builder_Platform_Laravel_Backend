<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;  // Make sure to import the User model
use Illuminate\Support\Facades\Auth;  // Import Auth facade
use Illuminate\Support\Str;  // Import Str facade

Route::get('/', function () {
    return view('welcome');
});

// Redirect to Google OAuth
Route::get('auth/google/redirect', function () {
    return Socialite::driver("google")->redirect();
});

// Handle the callback after Google OAuth
Route::get('auth/google/callback', function (Request $request) {
    // Get Google user information
    $googleUser = Socialite::driver("google")->user();

    // Find or create the user in your database
    $user = User::updateOrCreate(
        [
            'google_id' => $googleUser->getId() // Corrected: getId() instead of idn_to_ascii
        ],
        [
            'name' => $googleUser->getName(),
            'email' => $googleUser->getEmail(),
            'password' => bcrypt(Str::random(16)) // Generating a random password
        ]
    );

    // Log the user in
    Auth::login($user);

    // Redirect to the dashboard or any other page
    return redirect(config("app.frontend_url") . "/dashboard");
});
