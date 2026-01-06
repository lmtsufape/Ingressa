<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            TipoAnalistaSeeder::class,
            CursoSeeder::class,
            SisuSeeder::class,
            ChamadaSeeder::class,
            CotaSeeder::class,
            UserSeeder::class,
            CandidatoSeeder::class,
            RemanejamentoSeeder::class,
        ]);
    }
}
