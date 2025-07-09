<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        $adminUser = User::create([
            'firstname' => 'Admin',
            'lastname' => 'User',
            'username' => 'admin',
            'email' => 'admin@pagebuilder.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);

        // Create admin profile
        UserProfile::create([
            'user_id' => $adminUser->id,
            'bio' => 'Platform Administrator',
            'location' => 'Global',
            'website' => 'https://pagebuilder.com',
            'total_projects' => 0,
            'preferences' => json_encode([
                'theme' => 'light',
                'notifications' => true,
                'auto_save' => true
            ])
        ]);

        // Create test user
        $testUser = User::create([
            'firstname' => 'John',
            'lastname' => 'Doe',
            'username' => 'johndoe',
            'email' => 'john@example.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);

        // Create test user profile
        UserProfile::create([
            'user_id' => $testUser->id,
            'bio' => 'Web Developer and Designer',
            'location' => 'New York, USA',
            'website' => 'https://johndoe.dev',
            'total_projects' => 0,
            'preferences' => json_encode([
                'theme' => 'dark',
                'notifications' => true,
                'auto_save' => false
            ])
        ]);

        // Create another test user
        $testUser2 = User::create([
            'firstname' => 'Jane',
            'lastname' => 'Smith',
            'username' => 'janesmith',
            'email' => 'jane@example.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);

        // Create profile for Jane
        UserProfile::create([
            'user_id' => $testUser2->id,
            'bio' => 'UI/UX Designer',
            'location' => 'San Francisco, USA',
            'website' => 'https://janesmith.design',
            'total_projects' => 0,
            'preferences' => json_encode([
                'theme' => 'light',
                'notifications' => false,
                'auto_save' => true
            ])
        ]);
    }
}
