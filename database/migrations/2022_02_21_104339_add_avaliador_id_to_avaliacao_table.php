<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAvaliadorIdToAvaliacaoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('avaliacaos', function (Blueprint $table) {
            $table->unsignedBigInteger('avaliador_id')->nullable();
            $table->foreign('avaliador_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('avaliacaos', function (Blueprint $table) {
            $table->dropColumn('avaliador_id');
        });
    }
}
