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
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'github_id')) {
                $table->string('github_id')->nullable();
            }
            if (!Schema::hasColumn('users', 'github_token')) {
                $table->text('github_token')->nullable();
            }
            if (!Schema::hasColumn('users', 'github_refresh_token')) {
                $table->text('github_refresh_token')->nullable();
            }
            if (!Schema::hasColumn('users', 'is_github_connected')) {
                $table->boolean('is_github_connected')->default(0);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'github_id')) {
                $table->dropColumn('github_id');
            }
            if (Schema::hasColumn('users', 'github_token')) {
                $table->dropColumn('github_token');
            }
            if (Schema::hasColumn('users', 'github_refresh_token')) {
                $table->dropColumn('github_refresh_token');
            }
            if (Schema::hasColumn('users', 'is_github_connected')) {
                $table->dropColumn('is_github_connected');
            }
        });
    }
};