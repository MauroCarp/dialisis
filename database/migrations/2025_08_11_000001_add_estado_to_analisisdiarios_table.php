<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('analisisdiarios', function (Blueprint $table) {
            $table->enum('estado', ['pre_dialisis', 'completo'])->default('pre_dialisis')->after('id_tipofiltro');
        });
    }

    public function down()
    {
        Schema::table('analisisdiarios', function (Blueprint $table) {
            $table->dropColumn('estado');
        });
    }
};
