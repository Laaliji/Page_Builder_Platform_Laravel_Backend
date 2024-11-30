<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Check if users table doesn't exist before creating
        if (!Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->id();
               
                // New fields from signup page
                $table->string('firstname');
                $table->string('lastname');
                $table->string('username')->unique();
               
                // Existing fields
                $table->string('email')->unique();
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password');
                $table->rememberToken();
                $table->timestamps();

                // OAuth and provider-related fields
                $table->string('auth_provider')->nullable();
                $table->string('auth_provider_id')->nullable();

                // GitHub-specific fields
                // Use a method that prevents duplicate column creation
                if (!Schema::hasColumn('users', 'github_id')) {
                    $table->string('github_id')->nullable()->unique();
                }
                if (!Schema::hasColumn('users', 'github_token')) {
                    $table->text('github_token')->nullable();
                }
                if (!Schema::hasColumn('users', 'github_refresh_token')) {
                    $table->text('github_refresh_token')->nullable();
                }
                if (!Schema::hasColumn('users', 'is_github_connected')) {
                    $table->boolean('is_github_connected')->default(false);
                }
            });
        }

        // Similar approach for other tables
        if (!Schema::hasTable('password_reset_tokens')) {
            Schema::create('password_reset_tokens', function (Blueprint $table) {
                $table->string('email')->primary();
                $table->string('token');
                $table->timestamp('created_at')->nullable();
            });
        }

        if (!Schema::hasTable('sessions')) {
            Schema::create('sessions', function (Blueprint $table) {
                $table->string('id')->primary();
                $table->foreignId('user_id')->nullable()->index();
                $table->string('ip_address', 45)->nullable();
                $table->text('user_agent')->nullable();
                $table->longText('payload');
                $table->integer('last_activity')->index();
            });
        }

        // Create user profiles table
        if (!Schema::hasTable('user_profiles')) {
            Schema::create('user_profiles', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')
                      ->constrained()
                      ->onDelete('cascade');
               
                // Additional profile fields
                $table->text('bio')->nullable();
                $table->string('location')->nullable();
                $table->string('website')->nullable();
               
                // Tracking fields for code generation
                $table->integer('total_projects')->default(0);
                $table->timestamp('last_project_created_at')->nullable();
               
                // Optional fields for tracking user preferences
                $table->json('preferences')->nullable();
               
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('user_profiles');
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};