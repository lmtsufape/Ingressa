<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Curso;

class CursoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cursos = [
            [
                'nome' => 'Bacharelado em Agronomia (manhã)',
                'turno' => Curso::TURNO_ENUM['matutino'],
                'cod_curso' => '91555',
                'vagas' => 80,
            ],
            [
                'nome' => 'Bacharelado em Agronomia (tarde)',
                'turno' => Curso::TURNO_ENUM['vespertino'],
                'cod_curso' => '91555',
                'vagas' => 80,
            ],
            [
                'nome' => 'Bacharelado em Ciências da Computação',
                'turno' => Curso::TURNO_ENUM['noturno'],
                'cod_curso' => '118468​',
                'vagas' => 80,
            ],
            [
                'nome' => 'Engenharia de Alimentos',
                'turno' => Curso::TURNO_ENUM['integral'],
                'cod_curso' => '118766',
                'vagas' => 80,
            ],
            [
                'nome' => 'Bacharelado em Medicina Veterinária (manhã)',
                'turno' => Curso::TURNO_ENUM['matutino'],
                'cod_curso' => '91561',
                'vagas' => 40,
            ],
            [
                'nome' => 'Bacharelado em Medicina Veterinária (tarde)',
                'turno' => Curso::TURNO_ENUM['vespertino'],
                'cod_curso' => '91561',
                'vagas' => 40,
            ],
            [
                'nome' => 'Bacharelado em Zootecnia',
                'turno' => Curso::TURNO_ENUM['noturno'],
                'cod_curso' => '91738',
                'vagas' => 80,
            ],
            [
                'nome' => 'Licenciatura em Letras',
                'turno' => Curso::TURNO_ENUM['noturno'],
                'cod_curso' => '118470',
                'vagas' => 80,
            ],
            [
                'nome' => 'Licenciatura em Pedagogia (manhã)',
                'turno' => Curso::TURNO_ENUM['matutino'],
                'cod_curso' => '91969',
                'vagas' => 40,
            ],
            [
                'nome' => 'Licenciatura em Pedagogia (tarde)',
                'turno' => Curso::TURNO_ENUM['vespertino'],
                'cod_curso' => '91969',
                'vagas' => 40,
            ],
        ];

        Curso::insert($cursos);
    }
}
