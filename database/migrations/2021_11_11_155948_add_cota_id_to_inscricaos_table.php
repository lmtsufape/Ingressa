<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCotaIdToInscricaosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('inscricaos', function (Blueprint $table) {
            $table->unsignedBigInteger('cota_id')->after('chamada_id');
            $table->foreign('cota_id')->references('id')->on('cotas');
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
            $table->dropColumn('cota_id');
        });
    }
}
