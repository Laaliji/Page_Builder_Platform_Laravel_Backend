<?php

namespace App\Http\Controllers\Auth;

use App\Enums\ApiResponse;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Register a new user
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'firstname' => 'required|string|max:255',
                'lastname' => 'required|string|max:255',
                'username' => 'required|string|unique:users|max:255',
                'email' => 'required|string|email|unique:users',
                'password' => 'required|string|min:8|confirmed',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'STATE' => ApiResponse::INVALID_DATA,
                    'errors' => $validator->errors()
                ], 422);
            }
            
            $validatedData = $validator->validated();
            
            $user = User::create([
                'firstname' => $validatedData['firstname'],
                'lastname' => $validatedData['lastname'],
                'username' => $validatedData['username'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
                'auth_provider' => null, // Traditional auth
            ]);

            // Create user profile
            UserProfile::create([
                'user_id' => $user->id
            ]);

            // Generate Sanctum token
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'STATE' => ApiResponse::OK,
                'user' => $user,
                'token' => $token,
            ], 201);
        } catch (\Exception $e) {
            Log::error('Registration Error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'STATE' => ApiResponse::ERROR,
                'message' => 'Registration failed'
            ], 500);
        }
    }

    /**
     * Login user with email and password
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required',
            ]);
    
            if ($validator->fails()) {
                return response()->json([
                    'STATE' => ApiResponse::INVALID_DATA,
                    'errors' => $validator->errors()
                ], 422);
            }
    
            $credentials = $validator->validated();
    
            if (!Auth::attempt($credentials)) {
                return response()->json([
                    'STATE' => ApiResponse::INVALID_DATA,
                    'message' => 'Invalid credentials'
                ], 401);
            }
    
            $user = $request->user();
            $token = $user->createToken('auth_token')->plainTextToken;
    
            return response()->json([
                'STATE' => ApiResponse::OK,
                'user' => $user,
                'token' => $token
            ]);
        } catch (\Exception $e) {
            Log::error('Login Error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'STATE' => ApiResponse::ERROR,
                'message' => 'Login failed'
            ], 500);
        }
    }

    /**
     * Logout user and revoke token
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        try {
            // Revoke the token that was used to authenticate the current request
            $request->user()->currentAccessToken()->delete();
            
            return response()->json([
                'STATE' => ApiResponse::OK,
                'message' => 'Successfully logged out'
            ]);
        } catch (\Exception $e) {
            Log::error('Logout Error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'STATE' => ApiResponse::ERROR, 
                'message' => 'Logout failed'
            ], 500);
        }
    }

    /**
     * Redirect to GitHub for OAuth authentication
     * 
     * @return \Illuminate\Http\JsonResponse|\Laravel\Socialite\Facades\Socialite
     */
    public function redirectToGitHub()
    {
        try {
            return Socialite::driver('github')
                ->scopes(['repo', 'user'])
                ->stateless()
                ->redirect();
        } catch (\Exception $e) {
            Log::error('GitHub Redirect Error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'STATE' => ApiResponse::ERROR,
                'message' => 'Failed to redirect to GitHub'
            ], 500);
        }
    }

    /**
     * Handle GitHub callback for direct login/registration
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function handleGitHubCallbackDirect(Request $request)
    {
        try {
            // Log incoming data to help debug
            Log::info('GitHub Callback received', [
                'code' => $request->code,
                'state' => $request->state
            ]);
            
            // Get GitHub user with error handling
            try {
                $githubUser = Socialite::driver('github')->stateless()->user();
                
                // Log GitHub user info
                Log::info('GitHub User Retrieved', [
                    'id' => $githubUser->getId(),
                    'nickname' => $githubUser->getNickname(),
                    'email' => $githubUser->getEmail(),
                    'token_exists' => !empty($githubUser->token)
                ]);
            } catch (\Exception $e) {
                Log::error('GitHub User Retrieval Error', [
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                
                return response()->json([
                    'STATE' => ApiResponse::ERROR,
                    'message' => 'Failed to retrieve GitHub user: ' . $e->getMessage()
                ], 500);
            }
            
            // Check if user already exists with this GitHub ID
            $user = User::where('github_id', $githubUser->getId())->first();
            
            // Check if user exists with the same email
            if (!$user && $githubUser->getEmail()) {
                $user = User::where('email', $githubUser->getEmail())->first();
                
                if ($user) {
                    // Link GitHub to existing account
                    $user->linkGitHubAccount($githubUser->getId(), $githubUser->token, $githubUser->refreshToken ?? null);
                    Log::info('Linked GitHub to existing user by email', ['user_id' => $user->id]);
                }
            }
            
            // Create new user if not exists
            if (!$user) {
                Log::info('Creating new user from GitHub data');
                $user = User::createFromGithub($githubUser);
                
                // Create user profile
                UserProfile::create([
                    'user_id' => $user->id
                ]);
                
                Log::info('Created new user from GitHub', ['user_id' => $user->id]);
            }
            
            // Generate token
            $token = $user->createToken('auth_token')->plainTextToken;
            
            return response()->json([
                'STATE' => ApiResponse::OK,
                'user' => $user,
                'token' => $token,
                'is_new_user' => $user->wasRecentlyCreated
            ]);
        } catch (\Exception $e) {
            Log::error('GitHub Authentication Error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'STATE' => ApiResponse::ERROR,
                'message' => 'GitHub authentication failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Handle GitHub callback for linking to existing account
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handleGitHubCallback(Request $request)
    {
        // Ensure user is authenticated
        if (!Auth::check()) {
            return response()->json([
                'STATE' => ApiResponse::INVALID_DATA,
                'message' => 'User must be logged in first'
            ], 403);
        }

        try {
            $githubUser = Socialite::driver('github')->stateless()->user();
            
            // Check if another user already uses this GitHub account
            $existingUser = User::where('github_id', $githubUser->getId())
                ->where('id', '!=', $request->user()->id)
                ->first();
                
            if ($existingUser) {
                return response()->json([
                    'STATE' => ApiResponse::INVALID_DATA,
                    'message' => 'This GitHub account is already linked to another user'
                ], 409);
            }
            
            // Get the currently authenticated user
            $user = $request->user();

            // Link GitHub account
            $user->linkGitHubAccount($githubUser->getId(), $githubUser->token, $githubUser->refreshToken);

            return response()->json([
                'STATE' => ApiResponse::OK,
                'message' => 'GitHub account successfully linked',
                'github_profile' => [
                    'id' => $githubUser->getId(),
                    'nickname' => $githubUser->getNickname(),
                    'name' => $githubUser->getName(),
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('GitHub Authentication Error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'STATE' => ApiResponse::ERROR,
                'message' => 'GitHub authentication failed'
            ], 500);
        }
    }

    /**
     * Unlink GitHub Account
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function unlinkGitHub(Request $request)
    {
        try {
            $user = $request->user();
            
            // Ensure user has a password if unlinking
            if ($user->auth_provider === 'github' && !$user->password) {
                return response()->json([
                    'STATE' => ApiResponse::INVALID_DATA,
                    'message' => 'Cannot unlink GitHub as it is your only login method. Please set a password first.'
                ], 400);
            }
            
            $user->update([
                'github_id' => null,
                'github_token' => null,
                'github_refresh_token' => null,
                'is_github_connected' => false,
                'auth_provider' => $user->auth_provider === 'github' ? null : $user->auth_provider
            ]);
    
            return response()->json([
                'STATE' => ApiResponse::OK,
                'message' => 'GitHub account unlinked'
            ]);
        } catch (\Exception $e) {
            Log::error('GitHub Unlink Error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'STATE' => ApiResponse::ERROR,
                'message' => 'Failed to unlink GitHub account'
            ], 500);
        }
    }

    /**
     * Get GitHub Connection Status
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getGitHubConnectionStatus(Request $request)
    {
        try {
            $user = $request->user();
            
            return response()->json([
                'STATE' => ApiResponse::OK,
                'is_github_connected' => $user->is_github_connected ?? false,
                'github_username' => $user->github_id ? User::where('github_id', $user->github_id)->first()?->username : null
            ]);
        } catch (\Exception $e) {
            Log::error('GitHub Status Error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'STATE' => ApiResponse::ERROR,
                'message' => 'Failed to get GitHub connection status'
            ], 500);
        }
    }
    
    /**
     * Update user password
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updatePassword(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'current_password' => 'sometimes|required_without:is_github_user',
                'is_github_user' => 'sometimes|boolean',
                'password' => 'required|string|min:8|confirmed',
            ]);
    
            if ($validator->fails()) {
                return response()->json([
                    'STATE' => ApiResponse::INVALID_DATA,
                    'errors' => $validator->errors()
                ], 422);
            }
    
            $user = $request->user();
            
            // If user has a password, verify current password
            if ($user->password && !$request->is_github_user) {
                if (!Hash::check($request->current_password, $user->password)) {
                    return response()->json([
                        'STATE' => ApiResponse::INVALID_DATA,
                        'message' => 'Current password is incorrect'
                    ], 400);
                }
            }
            
            $user->password = Hash::make($request->password);
            $user->save();
            
            return response()->json([
                'STATE' => ApiResponse::OK,
                'message' => 'Password updated successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Password Update Error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'STATE' => ApiResponse::ERROR,
                'message' => 'Failed to update password'
            ], 500);
        }
    }
}
