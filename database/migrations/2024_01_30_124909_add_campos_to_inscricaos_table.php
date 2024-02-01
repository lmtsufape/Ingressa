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
            $table->string('de_acordo_lei_cota', 1)->nullable();
            $table->string('ensino_medio', 1)->nullable();
            $table->string('quilombola', 1)->nullable();
            $table->string('deficiente', 1)->nullable();
            $table->string('modalidade_escolhida', 4000)->nullable();
            $table->string('tipo_concorrencia', 6)->nullable();
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
