<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSemestreEntradaToInscricaosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('inscricaos', function (Blueprint $table) {
            $table->tinyInteger('semestre_entrada')->nullable();
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
            $table->dropColumn('semestre_entrada');
        });
    }
}
