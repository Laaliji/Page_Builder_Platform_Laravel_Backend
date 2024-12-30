<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void{
        Schema::create('pages', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('title');
            $table->string('html_page_title')->default('title')->nullable();
            $table->text('html_content')->nullable();
            $table->text('css_content')->nullable();
            $table->unsignedBigInteger('project_id');
            $table->timestamps();

            $table->foreign('project_id')->references('idP')->on('projects')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void{
        Schema::dropIfExists('pages');
    }
};
