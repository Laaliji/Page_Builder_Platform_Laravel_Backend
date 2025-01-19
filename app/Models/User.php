<?php
namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens;
     

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


     public function projects(){
         return $this->hasMany(Project::class, 'user_id', 'id');
     }


     public function isGitHubConnected()
     {
         return $this->is_github_connected;
     }
}