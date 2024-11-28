<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

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

            $user = User::firstOrCreate([
                'email' => $githubUser->email,
            ], [
                'name' => $githubUser->name ?? $githubUser->nickname,
                'email' => $githubUser->email,
                'github_id' => $githubUser->id,
                'password' => bcrypt(Str::random(16))
            ]);

            
            $token = $user->createToken('GitHub OAuth Token')->plainTextToken;

            $frontendUrl = config('app.frontend_url', 'http://localhost:5173');
            $redirectUrl = "{$frontendUrl}/stepper?token={$token}&github_id={$githubUser->id}&email={$githubUser->email}";

            \Log::info('GitHub OAuth Redirect', [
                'redirect_url' => $redirectUrl,
                'user_email' => $githubUser->email,
                'github_id' => $githubUser->id
            ]);

           
            return redirect($redirectUrl);

        } catch (\Exception $e) {
           
            \Log::error('GitHub OAuth Error: ' . $e->getMessage());

            
            return redirect(config('app.frontend_url', 'http://localhost:5173') . '/stepper')
                ->with('error', 'GitHub authentication failed: ' . $e->getMessage());
        }
    }
}