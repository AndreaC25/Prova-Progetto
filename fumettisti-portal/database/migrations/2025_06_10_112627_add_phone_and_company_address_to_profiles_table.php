<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('profiles', function (Blueprint $table) {
            // Aggiunge numero di telefono UNICO (requisito specifico)
            $table->string('phone')->unique()->nullable()->after('bio');

            // Aggiunge indirizzo società OPZIONALE (requisito specifico)
            $table->string('company_address')->nullable()->after('phone');
        });
    }

    public function down()
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->dropUnique(['phone']);
            $table->dropColumn(['phone', 'company_address']);
        });
    }
};
