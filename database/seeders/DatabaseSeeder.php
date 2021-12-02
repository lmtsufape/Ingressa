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
            UserSeeder::class,
            CursoSeeder::class,
            SisuSeeder::class,
            ChamadaSeeder::class,
            CandidatoSeeder::class,
            CotaSeeder::class,
            RemanejamentoSeeder::class,
        ]);
    }
}
