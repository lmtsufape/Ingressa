<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSisuIdFieldInCotaCursoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cota_curso', function (Blueprint $table) {
            $table->foreignId('sisu_id')->nullable()->constrained();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cota_curso', function (Blueprint $table) {
            $table->dropForeign(['sisu_id']);
            $table->dropColumn(['sisu_id']);
        });
    }
}
