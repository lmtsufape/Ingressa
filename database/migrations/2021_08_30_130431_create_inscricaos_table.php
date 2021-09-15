<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInscricaosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inscricaos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('candidato_id');
            $table->foreign('candidato_id')->references('id')->on('candidatos');
            $table->unsignedBigInteger('chamada_id');
            $table->foreign('chamada_id')->references('id')->on('chamadas');
            $table->string('protocolo');
            $table->string('status');
            $table->unsignedBigInteger('co_ies_curso');
            $table->unsignedBigInteger('co_ies');
            $table->string('ds_turno');
            //$table->string('co_oferta_mod_concorrencia');
            $table->double('nu_nota_candidato');

            $table->string('nu_etapa');
            $table->string('ds_formacao');
            $table->string('co_curso_inscricao');
            //$table->unsignedBigInteger('co_termo_adesao');
            //$table->unsignedBigInteger('co_etapa');
            //$table->unsignedBigInteger('co_evento');
            //$table->unsignedBigInteger('co_edicao');

            //$table->unsignedBigInteger('co_oferta');
            //$table->unsignedBigInteger('co_inscricao');
            $table->unsignedBigInteger('co_inscricao_enem');
            //$table->string('ds_etapa');

            $table->double('nu_notacorte_concorrida');
            $table->unsignedBigInteger('nu_classificacao');
            $table->integer('st_opcao');
            $table->string('ds_matricula');
            $table->date('dt_operacao');
            $table->string('no_campus');
            $table->string('no_curso');

            //$table->string('ds_grau');
            $table->integer('qt_vagas_concorrencia');
            $table->string('no_modalidade_concorrencia');
            $table->string('st_bonus_perc');
            $table->string('qt_bonus_perc')->nullable();
            $table->string('no_acao_afirmativa_bonus')->nullable();


            $table->date('dt_nascimento');
            $table->string('tp_sexo');
            $table->string('nu_rg');
            $table->string('no_mae')->nullable();
            $table->string('ds_logradouro');
            $table->string('nu_endereco');
            $table->string('ds_complemento');
            $table->string('sg_uf_inscrito');
            $table->string('no_municipio');
            $table->string('no_bairro');
            $table->string('nu_cep');
            $table->string('nu_fone1')->nullable();
            $table->string('nu_fone2')->nullable();
            $table->string('ds_email');
            $table->double('nu_nota_l');
            $table->double('nu_nota_ch');
            $table->double('nu_nota_cn');
            $table->double('nu_nota_m');
            $table->double('nu_nota_r');
            //$table->string('st_matricula');
            //$table->integer('st_aprovado')->nullable();
            $table->string('no_social')->nullable();

            $table->string('sg_ies');
            $table->string('no_ies');
            $table->string('sg_uf_ies');
            //$table->string('tp_nome');
            //$table->string('in_treineiro');
            //$table->string('in_atendimento_especializado');
            //$table->string('in_ppl');
            $table->string('st_lei_optante');
            $table->string('st_lei_renda');
            $table->string('st_lei_etnia_p');
            $table->string('st_lei_etnia_i');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inscricaos');
    }
}
