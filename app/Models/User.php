<?php
namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;
     

    protected $fillable = [
        'name',
        'email',
        'password',
        'github_id',
        'github_token',
        'github_refresh_token',
        'firstname',
        'lastname',
        'username',
        'is_github_connected'
    ];

    protected $hidden = [
        'password',
        'github_token',
        'github_refresh_token'
    ];

    protected $casts = [
        'is_github_connected' => 'boolean'
    ];

     // Relationship to store additional user metadata
     public function userProfile()
     {
         return $this->hasOne(UserProfile::class);
     }
 
     // Method to link GitHub account
     public function linkGitHubAccount($githubId, $githubToken, $githubRefreshToken = null)
     {
         $this->update([
             'github_id' => $githubId,
             'github_token' => $githubToken,
             'github_refresh_token' => $githubRefreshToken,
             'is_github_connected' => true
         ]);
     }
 
     // Check if GitHub is connected
     public function isGitHubConnected()
     {
         return $this->is_github_connected;
     }
}