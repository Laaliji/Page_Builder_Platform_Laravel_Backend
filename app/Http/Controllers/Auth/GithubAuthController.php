<?php
namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class GitHubAuthController extends Controller
{
    public function redirectToGitHub()
    {
        return Socialite::driver('github')->redirect();
    }

    public function handleGitHubCallback()
    {
        try {
            $githubUser = Socialite::driver('github')->user();

            // Find or create user based on GitHub ID or email
            $user = User::where('github_id', $githubUser->getId())->first();

            if (!$user) {
                $user = User::where('email', $githubUser->getEmail())->first();
            }

            if (!$user) {
                $user = User::create([
                    'firstname' => $githubUser->getName() ? explode(' ', $githubUser->getName())[0] : '',
                    'lastname' => $githubUser->getName() ? (count(explode(' ', $githubUser->getName())) > 1 ? explode(' ', $githubUser->getName())[1] : '') : '',
                    'username' => $githubUser->getNickname() ?? Str::slug($githubUser->getEmail()),
                    'email' => $githubUser->getEmail(),
                    'github_id' => $githubUser->getId(),
                    'github_token' => $githubUser->token,
                    'github_refresh_token' => $githubUser->refreshToken,
                    'password' => bcrypt(Str::random(16)), // Random password for GitHub users
                ]);
            } else {
                // Update existing user's GitHub details
                $user->update([
                    'github_id' => $githubUser->getId(),
                    'github_token' => $githubUser->token,
                    'github_refresh_token' => $githubUser->refreshToken,
                ]);
            }

            // Generate a token for the user
            $token = $user->createToken('GitHub Auth Token')->plainTextToken;

            // Construct the redirect URL with query parameters
            $redirectUrl = "http://localhost:5173/signup?" . http_build_query([
                'token' => $token,
                'github_id' => $githubUser->getId(),
                'email' => $githubUser->getEmail(),
                'firstname' => $user->firstname,
                'lastname' => $user->lastname,
                'username' => $user->username,
            ]);

            return redirect($redirectUrl);

        } catch (\Exception $e) {
            // Handle any errors
            return redirect('http://localhost:5173/signup')->with('error', 'GitHub authentication failed');
        }
    }
}