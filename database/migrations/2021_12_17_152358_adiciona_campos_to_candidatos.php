<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AdicionaCamposToCandidatos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('candidatos', function (Blueprint $table) {
            $table->string('orgao_expedidor')->nullable();
            $table->string('uf_rg')->nullable();
            $table->date('data_expedicao')->nullable();
            $table->string('titulo')->nullable();
            $table->string('zona_eleitoral')->nullable();
            $table->string('secao_eleitoral')->nullable();
            $table->string('cidade_natal')->nullable();
            $table->string('uf_natural')->nullable();
            $table->string('pais_natural')->nullable();
            $table->string('estado_civil')->nullable();
            $table->string('reside')->nullable();
            $table->string('pai')->nullable();
            $table->string('localidade')->nullable();
            $table->string('escola_ens_med')->nullable();
            $table->string('uf_escola')->nullable();
            $table->string('ano_conclusao')->nullable();
            $table->string('modalidade')->nullable();
            $table->boolean('concluiu_publica')->nullable();
            $table->string('necessidades')->nullable();
            $table->string('cor_raca')->nullable();
            $table->string('etnia')->nullable();
            $table->boolean('trabalha')->nullable();
            $table->string('grupo_familiar')->nullable();
            $table->float('valor_renda')->nullable();
            $table->boolean('atualizar_dados')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('candidatos', function (Blueprint $table) {
            $table->dropColumn([
                'orgao_expedidor',
                'uf_rg',
                'data_expedicao',
                'titulo',
                'zona_eleitoral',
                'secao_eleitoral',
                'cidade_natal',
                'uf_natural',
                'pais_natural',
                'estado_civil',
                'reside',
                'pai',
                'localidade',
                'escola_ens_med',
                'uf_escola',
                'ano_conclusao',
                'modalidade',
                'concluiu_publica',
                'necessidades',
                'cor_raca',
                'etnia',
                'trabalha',
                'grupo_familiar',
                'valor_renda',
                'atualizar_dados',
            ]);
        });
    }
}
