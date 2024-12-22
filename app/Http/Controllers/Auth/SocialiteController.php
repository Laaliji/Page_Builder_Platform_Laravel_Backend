<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class SocialiteController extends Controller
{
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback($provider)
    {
        try {
            $socialiteUser = Socialite::driver($provider)->user();
            
            // Extensive logging
            \Log::info('Socialite User Data', [
                'email' => $socialiteUser->getEmail(),
                'name' => $socialiteUser->getName(),
                'id' => $socialiteUser->getId(),
                'nickname' => $socialiteUser->getNickname(),
            ]);
           
            // Ensure we have all required data
            $email = $socialiteUser->getEmail();
            $name = $socialiteUser->getName() ?? $socialiteUser->getNickname() ?? 'GitHub User';
            
            if (empty($email)) {
                throw new \Exception('Email is required but not provided by GitHub');
            }

            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => $name,
                    'password' => bcrypt(Str::random(40)), // More secure random password
                    'auth_provider_id' => (string)$socialiteUser->getId(),
                    'auth_provider' => $provider
                ]
            );
            
            // Login the user
            Auth::login($user, true);
            
            return redirect()->route('dashboard');
        } catch (\Exception $e) {
            // Log the full error for debugging
            \Log::error('GitHub OAuth Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('login')->with('error', 'Authentication failed: ' . $e->getMessage());
        }
    }
}