<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        
        foreach ($users as $user) {
            // Create sample projects for each user
            for ($i = 1; $i <= 3; $i++) {
                Project::create([
                    'title' => "Sample Project {$i} - {$user->firstname}",
                    'description' => "This is a sample project {$i} created for demonstration purposes. It showcases the capabilities of our page builder platform with various features and templates.",
                    'domaineName' => "project{$i}-{$user->username}.example.com",
                    'repository' => "https://github.com/{$user->username}/project-{$i}",
                    'image_url' => '/placeholderImage.png',
                    'user_id' => $user->id,
                    'shared_link' => Str::random(32),
                ]);
            }
            
            // Update user profile project count
            if ($user->userProfile) {
                $user->userProfile->update([
                    'total_projects' => 3,
                    'last_project_created_at' => now()
                ]);
            }
        }
    }
}
