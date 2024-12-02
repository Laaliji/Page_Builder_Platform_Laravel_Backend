
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
            if (Schema::hasColumn('users', 'firstname')) {
                // Modify the `firstname` column if it already exists
                $table->string('firstname')->default('')->nullable()->change();
            } else {
                // Add the `firstname` column if it does not exist
                $table->string('firstname')->default('')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'firstname')) {
                // Revert changes to the `firstname` column
                $table->string('firstname')->default(null)->nullable(false)->change();
            }
        });
    }
};

