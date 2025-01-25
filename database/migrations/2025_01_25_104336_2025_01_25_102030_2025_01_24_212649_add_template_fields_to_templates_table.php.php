<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('templates', function (Blueprint $table) {
            // Ensure fields exist, add if not present
            if (!Schema::hasColumn('templates', 'name')) {
                $table->string('name')->nullable();
            }
            if (!Schema::hasColumn('templates', 'html_content')) {
                $table->text('html_content')->nullable();
            }
            if (!Schema::hasColumn('templates', 'css_content')) {
                $table->text('css_content')->nullable();
            }
            if (!Schema::hasColumn('templates', 'description')) {
                $table->text('description')->nullable();
            }
            
            // Remove project_id if it exists
            if (Schema::hasColumn('templates', 'project_id')) {
                $table->dropForeign(['project_id']);
                $table->dropColumn('project_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('templates', function (Blueprint $table) {
            // Restore project_id if it was removed
            if (!Schema::hasColumn('templates', 'project_id')) {
                $table->unsignedBigInteger('project_id')->nullable();
                $table->foreign('project_id')->references('idP')->on('projects')->onDelete('cascade');
            }
        });
    }
};