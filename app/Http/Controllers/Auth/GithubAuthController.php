<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GitHubAuthController extends Controller
{
    public function redirectToGitHub()
    {
        return Socialite::driver('github')
            ->stateless()
            ->redirect();
    }

    public function handleGitHubCallback(Request $request)
    {
        try {
            $githubUser = Socialite::driver('github')
                ->stateless()
                ->user();
           
            $user = User::updateOrCreate(
                ['email' => $githubUser->email],
                [
                    'name' => $githubUser->name ?? $githubUser->nickname,
                    'email' => $githubUser->email,
                    'github_id' => $githubUser->id,
                    'github_token' => $githubUser->token,
                    'github_refresh_token' => $githubUser->refreshToken,
                    'password' => bcrypt(Str::random(16)),
                    'firstname' => $githubUser->name ? explode(' ', $githubUser->name)[0] : null,
                    'lastname' => $githubUser->name ? (count(explode(' ', $githubUser->name)) > 1 ? explode(' ', $githubUser->name)[1] : null) : null,
                    'username' => $githubUser->nickname,
                ]
            );
           
          
            $token = $user->createToken('GitHub OAuth Token')->plainTextToken;
           
            $frontendUrl = config('app.frontend_url', 'http://localhost:5173');
            $redirectUrl = "{$frontendUrl}/stepper?token={$token}&github_id={$githubUser->id}&email={$githubUser->email}&firstname={$user->firstname}&lastname={$user->lastname}&username={$user->username}";
           
            return redirect($redirectUrl);
        } catch (\Exception $e) {
            Log::error('GitHub OAuth Error: ' . $e->getMessage());
           
            return redirect(config('app.frontend_url', 'http://localhost:5173') . '/stepper')
                ->with('error', 'GitHub authentication failed: ' . $e->getMessage());
        }
    }
}