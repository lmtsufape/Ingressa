<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCotaClassificacaoIdToInscricaosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('inscricaos', function (Blueprint $table) {
            $table->unsignedBigInteger('cota_classificacao_id')->nullable();
            $table->foreign('cota_classificacao_id')->references('id')->on('cotas');
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
            $table->dropColumn('cota_classificacao_id');
        });
    }
}
