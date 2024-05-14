<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUniqueConstraintToRemanejamentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('remanejamentos', function (Blueprint $table) {
            $table->unique(['ordem', 'cota_id'], 'ordem_cota_unique');
            $table->unique(['id_prox_cota', 'cota_id'], 'prox_cota_cota_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('remanejamentos', function (Blueprint $table) {
            $table->dropUnique('ordem_cota_unique');
            $table->dropUnique('prox_cota_cota_unique');
        });
    }
}
