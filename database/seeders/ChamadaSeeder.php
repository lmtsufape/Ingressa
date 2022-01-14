<?php

namespace Database\Seeders;

use App\Models\Chamada;
use App\Models\Sisu;
use Illuminate\Database\Seeder;

class ChamadaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $chamadas = [
            [
                'sisu_id' => Sisu::all()->first()->id,
                'nome'           => 'Chamada Regular',
                'descricao' => 'Chamada Regular',
                'regular' => true,
                'created_at'       => now(),
            ],
        ];

        Chamada::insert($chamadas);
    }
}
