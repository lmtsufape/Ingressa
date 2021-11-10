<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCotaCursoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cota_curso', function (Blueprint $table) {
            $table->id();
            $table->integer('vagas_ocupadas')->nullable();
            $table->integer('quantidade_vagas')->nullable();
            $table->unsignedBigInteger('cota_id');
            $table->foreign('cota_id')->references('id')->on('cotas');
            $table->unsignedBigInteger('curso_id');
            $table->foreign('curso_id')->references('id')->on('cursos');
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
        Schema::dropIfExists('cota_curso');
    }
}
