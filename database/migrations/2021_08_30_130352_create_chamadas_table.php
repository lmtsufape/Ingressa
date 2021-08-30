<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChamadasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chamadas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sisu_id');
            $table->foreign('sisu_id')->references('id')->on('sisus');
            $table->string('nome');
            $table->string('descricao');
            $table->boolean('regular');
            $table->string('caminho_resultado');
            $table->string('caminho_import_sisu_gestao');
            $table->date('data_inicio');
            $table->date('data_fim');
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
        Schema::dropIfExists('chamadas');
    }
}
