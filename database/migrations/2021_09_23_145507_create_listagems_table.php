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
            $table->integer('tipo');
            $table->string('caminho_listagem');
            $table->unsignedBigInteger('chamada_id');
            $table->foreign('chamada_id')->references('id')->on('chamadas');
            $table->boolean('publicada')->default(false);
            $table->string('job_batch_id')->nullable();
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
