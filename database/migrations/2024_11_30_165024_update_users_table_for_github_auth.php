<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Add GitHub-specific columns if not already exists
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

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Remove columns if needed
            $table->dropColumn([
                'github_id', 
                'github_token', 
                'github_refresh_token', 
                'is_github_connected'
            ]);
        });
    }
};