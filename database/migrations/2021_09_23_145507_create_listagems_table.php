<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateListagemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('listagems', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->string('caminho_listagem');
            $table->unsignedBigInteger('data_chamada_id');
            $table->foreign('data_chamada_id')->references('id')->on('data_chamadas');
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
        Schema::dropIfExists('listagems');
    }
}
