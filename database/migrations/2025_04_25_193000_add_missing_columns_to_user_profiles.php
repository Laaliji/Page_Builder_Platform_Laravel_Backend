<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('user_profiles', function (Blueprint $table) {
            if (!Schema::hasColumn('user_profiles', 'total_projects')) {
                $table->integer('total_projects')->default(0);
            }
            
            if (!Schema::hasColumn('user_profiles', 'last_project_created_at')) {
                $table->timestamp('last_project_created_at')->nullable();
            }
            
            if (!Schema::hasColumn('user_profiles', 'bio')) {
                $table->text('bio')->nullable();
            }
            
            if (!Schema::hasColumn('user_profiles', 'location')) {
                $table->string('location')->nullable();
            }
            
            if (!Schema::hasColumn('user_profiles', 'website')) {
                $table->string('website')->nullable();
            }
            
            if (!Schema::hasColumn('user_profiles', 'preferences')) {
                $table->json('preferences')->nullable();
            }
            
            // Make the image column nullable
            $table->string('image')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_profiles', function (Blueprint $table) {
            $table->dropColumn([
                'total_projects',
                'last_project_created_at',
                'bio',
                'location',
                'website',
                'preferences'
            ]);
            
            // Make image required again
            $table->string('image')->nullable(false)->change();
        });
    }
}; 