<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('templates', function (Blueprint $table) {
            // If the fields are not already in the table, add them
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
        });
    }

    public function down(): void
    {
        Schema::table('templates', function (Blueprint $table) {
            $table->dropColumn(['name', 'html_content', 'css_content', 'description']);
        });
    }
};