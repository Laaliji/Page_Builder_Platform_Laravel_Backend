<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = User::create([
                'firstname' => $request->firstname,
                'lastname' => $request->lastname,
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'User registered successfully!',
                'user' => $user,
                'token' => $token
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating user',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid login credentials'
            ], 401);
        }

        $user = User::where('email', $request->email)->firstOrFail();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'User logged in successfully',
            'user' => $user,
            'token' => $token
        ]);
    }

    public function redirectToGitHub()
    {
        return Socialite::driver('github')
            ->scopes(['user', 'repo']) // Add scopes as needed
            ->redirect();
    }

    public function handleGitHubCallback(Request $request)
    {
        try {
            $githubUser = Socialite::driver('github')->user();
            $existingUser = User::where('email', $githubUser->getEmail())->first();

            if ($existingUser) {
                // Update GitHub info for existing user
                $existingUser->linkGitHubAccount(
                    $githubUser->getId(),
                    $githubUser->token,
                    $githubUser->refreshToken
                );
                
                $token = $existingUser->createToken('auth_token')->plainTextToken;
                
                return redirect()->away(
                    env('FRONTEND_URL') . '/login?token=' . $token . 
                    '&github_id=' . $githubUser->getId() . 
                    '&email=' . $githubUser->getEmail()
                );
            } else {
                // Create new user from GitHub data
                $user = User::create([
                    'firstname' => $githubUser->getName() ? explode(' ', $githubUser->getName())[0] : 'GitHub',
                    'lastname' => $githubUser->getName() ? (count(explode(' ', $githubUser->getName())) > 1 ? explode(' ', $githubUser->getName())[1] : 'User') : 'User',
                    'username' => $githubUser->getNickname() ?: Str::slug($githubUser->getEmail()),
                    'email' => $githubUser->getEmail(),
                    'password' => Hash::make(Str::random(16)),
                    'github_id' => $githubUser->getId(),
                    'github_token' => $githubUser->token,
                    'github_refresh_token' => $githubUser->refreshToken,
                    'is_github_connected' => true
                ]);
                
                $token = $user->createToken('auth_token')->plainTextToken;
                
                return redirect()->away(
                    env('FRONTEND_URL') . '/login?token=' . $token . 
                    '&github_id=' . $githubUser->getId() . 
                    '&email=' . $githubUser->getEmail()
                );
            }
        } catch (\Exception $e) {
            return redirect()->away(
                env('FRONTEND_URL') . '/login?error=' . urlencode('GitHub authentication failed: ' . $e->getMessage())
            );
        }
    }

    public function unlinkGitHub(Request $request)
    {
        $user = $request->user();
        $user->update([
            'github_id' => null,
            'github_token' => null,
            'github_refresh_token' => null,
            'is_github_connected' => false
        ]);

        return response()->json([
            'success' => true,
            'message' => 'GitHub account unlinked successfully'
        ]);
    }

    public function getGitHubConnectionStatus(Request $request)
    {
        $user = $request->user();
        return response()->json([
            'is_github_connected' => $user->isGitHubConnected(),
            'github_username' => $user->isGitHubConnected() ? $user->username : null
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json([
            'success' => true,
            'message' => 'User logged out successfully'
        ]);
    }
}