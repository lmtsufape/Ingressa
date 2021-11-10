<?php

namespace Database\Seeders;

use App\Models\Candidato;
use Illuminate\Database\Seeder;

class CandidatoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $candidatos = [
            [
                'user_id'           => '3',
                'no_inscrito'       => 'Candidato',
                'nu_cpf_inscrito' => '34188522038',
                'dt_nascimento' => '2021-09-17',
            ],
        ];

        Candidato::insert($candidatos);
    }
}
