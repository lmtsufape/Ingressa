<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddListaPersonalizadaToSisusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sisus', function (Blueprint $table) {
            $table->boolean('lista_personalizada')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sisus', function (Blueprint $table) {
            $table->dropColumn('lista_personalizada');
        });
    }
}
