<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCandidatosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('candidatos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedBigInteger('co_termo_adesao');
            $table->unsignedBigInteger('co_etapa');
            $table->unsignedBigInteger('co_evento');
            $table->unsignedBigInteger('co_edicao');
            $table->unsignedBigInteger('co_oferta_mod_concorrencia');
            $table->unsignedBigInteger('co_oferta');
            $table->unsignedBigInteger('co_inscricao');
            $table->unsignedBigInteger('co_inscricao_enem');
            $table->string('ds_etapa');
            $table->double('nu_nota_candidato');
            $table->double('nu_notacorte_concorrida');
            $table->unsignedBigInteger('nu_classificacao');
            $table->integer('st_opcao');
            $table->string('ds_matricula');
            $table->date('dt_operacao');
            $table->string('no_campus');
            $table->unsignedBigInteger('co_ies_curso');
            $table->string('no_curso');
            $table->string('ds_turno');
            $table->string('ds_grau');
            $table->integer('qt_vagas_concorrencia');
            $table->string('no_modalidade_concorrencia');
            $table->string('st_bonus_perc');
            $table->integer('qt_bonus_perc')->nullable();
            $table->string('no_acao_afirmativa_bonus');
            $table->string('no_inscrito');
            $table->string('nu_cpf_inscrito');
            $table->date('dt_nascimento');
            $table->string('tp_sexo');
            $table->string('nu_rg');
            //$table->string('número do rg do inscrito')->nullable();
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
            $table->string('st_matricula');
            $table->integer('st_aprovado')->nullable();
            $table->string('no_social')->nullable();
            $table->unsignedBigInteger('co_ies');
            $table->string('sg_ies');
            $table->string('no_ies');
            $table->string('sg_uf_ies');
            $table->string('tp_nome');
            $table->string('in_treineiro');
            $table->string('in_atendimento_especializado');
            $table->string('in_ppl');
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
        Schema::dropIfExists('candidatos');
    }
}
