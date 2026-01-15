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
        Schema::table('inscricaos', function (Blueprint $table) {
            $table->double('media_simples_pdm_licenca')->nullable();
            $table->string('st_baixa_renda')->nullable();
            $table->string('st_rank_baixa_renda')->nullable();
            $table->string('st_adesao_acao_afirmativa_curso')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inscricaos', function (Blueprint $table) {
            $table->dropColumn('st_rank_ensino_medio');
            $table->dropColumn('st_baixa_renda');
            $table->dropColumn('st_rank_baixa_renda');
            $table->dropColumn('st_adesao_acao_afirmativa_curso');
        });
    }
};
