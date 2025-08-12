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
        Schema::table('analisisdiarios', function (Blueprint $table) {
            $table->enum('estado', ['pre_dialisis', 'post_dialisis', 'completo'])->default('pre_dialisis')->after('observaciones');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('analisisdiarios', function (Blueprint $table) {
            $table->dropColumn('estado');
        });
    }
};
