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
        Schema::table('pages', function (Blueprint $table) {
            $table->string('title')->nullable()->change();
            $table->string('html_page_title')->nullable()->change();
            $table->longText('html_content')->nullable()->change();
            $table->longText('css_content')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->string('title')->nullable(false)->change();
            $table->string('html_page_title')->nullable(false)->change();
            $table->longText('html_content')->nullable(false)->change();
            $table->longText('css_content')->nullable(false)->change();
        });
    }
}; 