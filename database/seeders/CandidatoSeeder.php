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
                'nu_cpf_inscrito' => '341.885.220-38',
                'dt_nascimento' => '2021-09-17',
            ],
        ];

        Candidato::insert($candidatos);
    }
}
