<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMultiplicadorVagasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('multiplicador_vagas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('chamada_id');
            $table->foreign('chamada_id')->references('id')->on('chamadas');
            $table->unsignedBigInteger('cota_curso_id');
            $table->foreign('cota_curso_id')->references('id')->on('cota_curso');
            $table->integer('multiplicador');
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
        Schema::dropIfExists('multiplicador_vagas');
    }
}
