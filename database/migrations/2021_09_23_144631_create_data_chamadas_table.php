<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDataChamadasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_chamadas', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->integer('tipo');
            $table->date('data_inicio');
            $table->date('data_fim');
            $table->unsignedBigInteger('chamada_id');
            $table->foreign('chamada_id')->references('id')->on('chamadas');
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
        Schema::dropIfExists('data_chamadas');
    }
}
