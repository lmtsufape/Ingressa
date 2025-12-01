<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCampos2025ToInscricaosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('inscricaos', function (Blueprint $table) {
            $table->string('st_rank_ensino_medio', 1)->nullable();
            $table->string('st_rank_raca', 1)->nullable();
            $table->string('st_rank_quilombola', 1)->nullable();
            $table->string('st_rank_pcd', 1)->nullable();
            $table->string('st_confirma_lgpd', 1)->nullable();
            $table->integer('total_membros_familiar')->nullable();
            $table->double('renda_familiar_bruta')->nullable();
            $table->double('salario_minimo')->nullable();
            $table->string('perfil_economico_lei_cotas', 2)->nullable();
            $table->date('dt_curso_inscricao')->nullable();
            $table->string('hr_curso_inscricao', 5)->nullable();
            $table->date('dt_mes_dia_inscricao')->nullable();
            $table->string('st_adesao_acao_afirmativa_curs', 3)->nullable();
            $table->string('st_aprovado', 3)->nullable();
            $table->date('dt_mes_dia_matricula')->nullable();
            $table->string('st_matricula_cancelada', 20)->nullable();
            $table->date('dt_matricula_cancelada')->nullable();
            $table->string('vaga_remanejada', 4000)->nullable();
            $table->string('no_acao_afirmativa_propria_ies', 4000)->nullable();
            $table->double('nu_nota_curso_l')->nullable();
            $table->double('nu_nota_curso_ch')->nullable();
            $table->double('nu_nota_curso_cn')->nullable();
            $table->double('nu_nota_curso_m')->nullable();
            $table->double('nu_nota_curso_r')->nullable();

            #campos removidos, adicionando nullable a eles.
            $table->string('st_lei_optante')->nullable()->change();
            $table->string('st_lei_renda')->nullable()->change();
            $table->string('st_lei_etnia_p')->nullable()->change();
            $table->string('st_lei_etnia_i')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('inscricaos', function (Blueprint $table) {
            $table->dropColumn('st_rank_ensino_medio');
            $table->dropColumn('st_rank_raca');
            $table->dropColumn('st_rank_quilombola');
            $table->dropColumn('st_rank_pcd');
            $table->dropColumn('st_confirma_lgpd');
            $table->dropColumn('total_membros_familiar');
            $table->dropColumn('renda_familiar_bruta');
            $table->dropColumn('salario_minimo');
            $table->dropColumn('perfil_economico_lei_cotas');
            $table->dropColumn('dt_curso_inscricao');
            $table->dropColumn('hr_curso_inscricao');
            $table->dropColumn('dt_mes_dia_inscricao');
            $table->dropColumn('st_adesao_acao_afirmativa_curs');
            $table->dropColumn('st_aprovado');
            $table->dropColumn('dt_mes_dia_matricula');
            $table->dropColumn('st_matricula_cancelada');
            $table->dropColumn('dt_matricula_cancelada');
            $table->dropColumn('no_acao_afirmativa_propria_ies');
            $table->dropColumn('nu_nota_curso_l');
            $table->dropColumn('nu_nota_curso_ch');
            $table->dropColumn('nu_nota_curso_cn');
            $table->dropColumn('nu_nota_curso_m');
            $table->dropColumn('nu_nota_curso_r');
            $table->dropColumn('vaga_remanejada');

            $table->string('st_lei_optante')->change();
            $table->string('st_lei_renda')->change();
            $table->string('st_lei_etnia_p')->change();
            $table->string('st_lei_etnia_i')->change();
        });
    }

}
