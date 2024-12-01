<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{


    public function login(Request $request)
{
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if (!Auth::attempt($credentials)) {
        return response()->json(['error' => 'Invalid credentials'], 401);
    }

    $user = $request->user();
    $token = $user->createToken('auth_token')->plainTextToken;

    return response()->json([
        'user' => $user,
        'token' => $token
    ]);
}


public function register(Request $request)
{
    try {
        // Log the request data and headers
        \Log::info('Register Request Data:', $request->all());
        \Log::info('Register Request Headers:', $request->headers->all());

        $validatedData = $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'username' => 'required|string|unique:users|max:255',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);
        
        $user = User::create([
            'firstname' => $validatedData['firstname'],
            'lastname' => $validatedData['lastname'],
            'username' => $validatedData['username'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
        ]);

        // Generate Sanctum token
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
            'message' => 'User registered successfully'
        ], 201);
    } catch (\Illuminate\Validation\ValidationException $e) {
        \Log::error('Validation Error:', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        return response()->json(['errors' => $e->errors()], 422);
    } catch (\Exception $e) {
        \Log::error('Registration Error:', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        return response()->json(['error' => 'Registration failed'], 500);
    }
}

public function loginWithGitHub()
{
    return Socialite::driver('github')->stateless()->redirect();

}

public function handleGitHubCallback()
{
    try {
        $githubUser = Socialite::driver('github')->user();
        
        $user = User::where('github_id', $githubUser->getId())->first();
        
        if (!$user) {
            // Create a new user
            $user = User::create([
                'name' => $githubUser->getName() ?? $githubUser->getNickname(),
                'email' => $githubUser->getEmail(),
                'github_id' => $githubUser->getId(),
                'password' => Hash::make(Str::random(24)),
                'github_token' => $githubUser->token,
                'github_refresh_token' => $githubUser->refreshToken,
                'is_github_connected' => true,
            ]);
        } else {
            // Update existing user
            $user->update([
                'github_token' => $githubUser->token,
                'github_refresh_token' => $githubUser->refreshToken,
                'is_github_connected' => true,
            ]);
        }

        Auth::login($user);
        
        $token = $user->createToken('github-token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token
        ]);
    } catch (\Exception $e) {
        \Log::error('GitHub Authentication Error:', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        return response()->json(['error' => 'GitHub authentication failed'], 500);
    }
}

    // Redirect to GitHub OAuth
    public function redirectToGitHub()
    {
        return Socialite::driver('github')
            ->scopes(['repo', 'user'])
            ->redirect();
    }

    
    // Unlink GitHub Account
    public function unlinkGitHub(Request $request)
    {
        $user = $request->user();
        
        $user->update([
            'github_id' => null,
            'github_token' => null,
            'github_refresh_token' => null,
            'is_github_connected' => false
        ]);

        return response()->json(['message' => 'GitHub account unlinked']);
    }

    // Get GitHub Connection Status
    public function getGitHubConnectionStatus(Request $request)
    {
        $user = $request->user();
        
        return response()->json([
            'is_github_connected' => $user->is_github_connected ?? false,
            'github_username' => $user->github_id // Optional: return GitHub username if connected
        ]);
    }
}
