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
        Schema::table('candidatos', function (Blueprint $table) {

            $table->json('dispositivos_moradia')->nullable();
            $table->json('filhos')->nullable();

            $table->string('nome_contato_emergencia')->nullable();;
            $table->string('parentesco_contato_emergencia')->nullable();;
            $table->string('gestante', 15)->nullable(); // sim/nao
            $table->string('cadunico', 15)->nullable(); // sim/nao

            $table->string('transgenero', 30)->nullable(); // sim/nao/outro/prefiro_nao_responder
            $table->string('lgbtqiap', 30)->nullable();    // sim/nao/outro/prefiro_nao_responder
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('candidatos', function (Blueprint $table) {
            $table->dropColumn([
                'no_social',
                'moradia',
                'nome_contato_emergencia',
                'parentesco_contato_emergencia',
                'filhos',
                'gestante',
                'cadunico',
                'transgenero',
                'lgbtqiap',
            ]);
        });
    }
};
