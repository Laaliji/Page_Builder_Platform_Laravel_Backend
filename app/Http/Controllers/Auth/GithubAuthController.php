<?php
namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class GitHubAuthController extends Controller
{
    public function loginWithGitHub()
    {
        return Socialite::driver('github')
            ->scopes(['user:email'])
            ->stateless()
            ->redirect();
    }

    public function handleGitHubCallback()
    {
        try {
            \Log::info('GitHub Callback Initiated', [
                'full_request' => request()->all(),
                'code' => request('code'),
            ]);
    
            
            $githubUser = Socialite::driver('github')->stateless()->user();
    
            
            $nameParts = explode(' ', $githubUser->getName(), 2);
            $firstname = $nameParts[0] ?? null;
            $lastname = $nameParts[1] ?? null;
    
            
            $username = $githubUser->getNickname() ?? strtolower(str_replace(' ', '_', $githubUser->getName()));
    
            
            $user = User::firstOrCreate(
                ['email' => $githubUser->getEmail()],
                [
                    'firstname' => $firstname,
                    'lastname' => $lastname,
                    'username' => $username,
                    'github_id' => $githubUser->getId(),
                    'password' => Hash::make(Str::random(24)),
                    'github_token' => $githubUser->token,
                    'github_refresh_token' => $githubUser->refreshToken,
                    'is_github_connected' => true,
                ]
            );
    
            
            if (!$user->wasRecentlyCreated) {
                $user->update([
                    'github_token' => $githubUser->token,
                    'github_refresh_token' => $githubUser->refreshToken,
                    'is_github_connected' => true,
                    'username' => $username, 
                ]);
            }
    
            
            $token = $user->createToken('github-token')->plainTextToken;
    
            
            $frontendRedirectUrl = env('FRONTEND_URL')
                                 . '/login?token='
                                 . $token 
                                 . '&github_id=' 
                                 . $githubUser->getId() 
                                 . '&email=' 
                                 . $githubUser->getEmail();
            return redirect()->away($frontendRedirectUrl);
    
        } catch (\Exception $e) {
            // Log any exceptions
            \Log::error('GitHub Authentication Detailed Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'exception_class' => get_class($e),
                'request_details' => request()->all()
            ]);
    
            // Return a JSON response with error details
            return response()->json([
                'error' => 'GitHub authentication failed',
                'details' => $e->getMessage()
            ], 500);
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

        return response()->json(['message' => 'GitHub account unlinked']);
    }

    public function getGitHubConnectionStatus(Request $request)
    {
        $user = $request->user();
        
        return response()->json([
            'is_github_connected' => $user->is_github_connected ?? false,
            'github_username' => $user->github_id 
        ]);
    }
}