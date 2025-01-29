<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStylesTable extends Migration
{
    public function up()
    {
        Schema::create('styles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('primary_color')->nullable();
            $table->string('secondary_color')->nullable();
            $table->string('tertiary_color')->nullable();
            $table->string('quaternary_color')->nullable();
            $table->string('background_color')->nullable();
            $table->string('text_color')->nullable();
            $table->boolean('is_dark_mode')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('styles');
    }
}