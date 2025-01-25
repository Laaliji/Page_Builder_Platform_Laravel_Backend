<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddResponseToContactsTable extends Migration
{
    /**
     * Ajouter le champ `response` dans la table `contacts`.
     */
    public function up()
    {
        Schema::table('contacts', function (Blueprint $table) {
            $table->text('response')->nullable()->after('message'); // Ajoute `response` après `message`
        });
    }

    /**
     * Supprimer le champ `response` si nécessaire.
     */
    public function down()
    {
        Schema::table('contacts', function (Blueprint $table) {
            $table->dropColumn('response'); // Supprime le champ `response`
        });
    }
}
