<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('projects', function (Blueprint $table) {
            // Rename idP to id if it exists
            if (Schema::hasColumn('projects', 'idP')) {
                $table->renameColumn('idP', 'id');
            }
            
            // Ensure proper indexes
            $table->index('user_id');
            $table->index('shared_link');
        });
    }

    public function down()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->renameColumn('id', 'idP');
            $table->dropIndex(['user_id']);
            $table->dropIndex(['shared_link']);
        });
    }
};