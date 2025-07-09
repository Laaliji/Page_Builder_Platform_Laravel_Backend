<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
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

            // Make password nullable to support OAuth logins
            if (Schema::hasColumn('users', 'password')) {
                $table->string('password')->nullable()->change();
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $dropColumns = [];

            if (Schema::hasColumn('users', 'github_id')) {
                $dropColumns[] = 'github_id';
            }

            if (Schema::hasColumn('users', 'github_token')) {
                $dropColumns[] = 'github_token';
            }

            if (Schema::hasColumn('users', 'github_refresh_token')) {
                $dropColumns[] = 'github_refresh_token';
            }

            if (Schema::hasColumn('users', 'is_github_connected')) {
                $dropColumns[] = 'is_github_connected';
            }

            if (!empty($dropColumns)) {
                $table->dropColumn($dropColumns);
            }

            // Revert password field to NOT nullable
            if (Schema::hasColumn('users', 'password')) {
                $table->string('password')->nullable(false)->change();
            }
        });
    }
};
