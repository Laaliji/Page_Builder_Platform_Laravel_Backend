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
        Schema::create('components', function (Blueprint $table) {
            $table->id('idComponent');
            $table->enum('typeComponent', ['header', 'footer', 'section', 'navbar']); // Add your component types
            $table->json('position');
            $table->json('size');
            $table->string('style');
            $table->foreignId('page_id')->constrained('pages', 'idPage')
                  ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('components');
    }
};
