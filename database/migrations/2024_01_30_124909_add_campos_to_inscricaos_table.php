<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCamposToInscricaosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('inscricaos', function (Blueprint $table) {
            $table->char('de_acordo_lei_cota')->default('x');
            $table->char('ensino_medio')->default('x');
            $table->char('quilombola')->default('x');
            $table->char('deficiente')->default('x');
            $table->string('modalidade_escolhida', 4000)->default('x');
            $table->string('tipo_concorrencia', 6)->default('x');
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
            $table->dropColumn('de_acordo_lei_cota');
            $table->dropColumn('ensino_medio');
            $table->dropColumn('quilombola');
            $table->dropColumn('deficiente');
            $table->dropColumn('modalidade_escolhida');
            $table->dropColumn('tipo_concorrencia');
        });
    }
}
