<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Traditional Registration
    public function register(Request $request)
    {
        \Log::info('Signup Request Data:', $request->all());
        
        try {
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

            return response()->json([
                'user' => $user,
                'message' => 'User registered successfully'
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation Errors:', $e->errors());
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            \Log::error('Registration Error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Redirect to GitHub OAuth
    public function redirectToGitHub()
    {
        return Socialite::driver('github')
            ->scopes(['repo', 'user'])
            ->redirect();
    }

    // GitHub OAuth Callback
    public function handleGitHubCallback(Request $request)
    {
        // Ensure user is authenticated
        if (!Auth::check()) {
            return response()->json(['error' => 'User must be logged in first'], 403);
        }

        try {
            $githubUser = Socialite::driver('github')->user();
            
            // Get the currently authenticated user
            $user = $request->user();

            // Link GitHub account
            $user->update([
                'github_id' => $githubUser->getId(),
                'github_token' => $githubUser->token,
                'github_refresh_token' => $githubUser->refreshToken,
                'is_github_connected' => true,
            ]);

            return response()->json([
                'message' => 'GitHub account successfully linked',
                'github_profile' => [
                    'id' => $githubUser->getId(),
                    'nickname' => $githubUser->getNickname(),
                    'name' => $githubUser->getName(),
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('GitHub Authentication Error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'GitHub authentication failed'], 500);
        }
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
