<?php
namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;
     
    protected $fillable = [
        'firstname',
        'lastname',
        'username',
        'email',
        'password',
        'auth_provider',
        'auth_provider_id',
        'github_id',
        'github_token',
        'github_refresh_token',
        'is_github_connected'
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'github_token',
        'github_refresh_token'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_github_connected' => 'boolean'
    ];

    // Relationship to store additional user metadata
    public function userProfile()
    {
        return $this->hasOne(UserProfile::class);
    }
 
    // Method to link GitHub account to existing user
    public function linkGitHubAccount($githubId, $githubToken, $githubRefreshToken = null)
    {
        $this->update([
            'github_id' => $githubId,
            'github_token' => $githubToken,
            'github_refresh_token' => $githubRefreshToken,
            'is_github_connected' => true,
            'auth_provider' => 'github',
            'auth_provider_id' => $githubId
        ]);
    }

    // Method to create user directly from GitHub data
    public static function createFromGithub($githubUser)
    {
        try {
            // Log GitHub data
            \Log::info('Creating user from GitHub data', [
                'github_id' => $githubUser->getId(),
                'nickname' => $githubUser->getNickname(),
                'email_exists' => !empty($githubUser->getEmail()),
                'name_exists' => !empty($githubUser->getName())
            ]);
            
            // Extract name parts if available, otherwise use nickname
            $name = $githubUser->getName() ?? $githubUser->getNickname();
            $nameParts = explode(' ', $name);
            $firstname = $nameParts[0] ?? $githubUser->getNickname();
            $lastname = count($nameParts) > 1 ? end($nameParts) : '';
            
            // Handle potentially missing email
            $email = $githubUser->getEmail();
            if (empty($email)) {
                // Generate a placeholder email using GitHub ID if email not provided
                $email = $githubUser->getId() . '@github.placeholder.com';
                \Log::warning('GitHub user has no email, using placeholder', ['email' => $email]);
            }
            
            // Generate a random password since the field cannot be null in the database
            $randomPassword = \Illuminate\Support\Str::random(32);
            
            return self::create([
                'firstname' => $firstname,
                'lastname' => $lastname,
                'username' => $githubUser->getNickname(),
                'email' => $email,
                'password' => \Illuminate\Support\Facades\Hash::make($randomPassword), // Use a random password
                'github_id' => $githubUser->getId(),
                'github_token' => $githubUser->token,
                'github_refresh_token' => $githubUser->refreshToken ?? null,
                'is_github_connected' => true,
                'auth_provider' => 'github',
                'auth_provider_id' => $githubUser->getId()
            ]);
        } catch (\Exception $e) {
            \Log::error('Error creating user from GitHub', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function projects(){
        return $this->hasMany(Project::class, 'user_id', 'id');
    }

    public function isGitHubConnected()
    {
        return $this->is_github_connected;
    }
}