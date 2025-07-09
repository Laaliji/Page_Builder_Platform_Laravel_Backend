<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Fix projects table
        Schema::table('projects', function (Blueprint $table) {
            // Rename idP to id if it exists
            if (Schema::hasColumn('projects', 'idP')) {
                DB::statement('ALTER TABLE projects CHANGE idP id BIGINT UNSIGNED AUTO_INCREMENT');
            }
            
            // Fix description column name if it's still wrong
            if (Schema::hasColumn('projects', 'desctiption')) {
                DB::statement('ALTER TABLE projects CHANGE desctiption description TEXT');
            }
        });
        
        // Fix pages table foreign key
        Schema::table('pages', function (Blueprint $table) {
            // Drop existing foreign key
            $table->dropForeign(['project_id']);
            
            // Add correct foreign key
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropForeign(['project_id']);
            $table->foreign('project_id')->references('idP')->on('projects')->onDelete('cascade');
        });
        
        Schema::table('projects', function (Blueprint $table) {
            DB::statement('ALTER TABLE projects CHANGE id idP BIGINT UNSIGNED AUTO_INCREMENT');
            DB::statement('ALTER TABLE projects CHANGE description desctiption TEXT');
        });
    }
};