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
                'nome' => 'Agronomia',
                'turno' => Curso::TURNO_ENUM['matutino'],
                'cod_curso' => 91555,
                'grau_academico' => Curso::GRAU_ENUM['bacharelado'],
                'cor_padrao' => "#7CEF90",
                'vagas' => 40,
                'icone' => 'img-seeder/agronomia.svg',
            ],
            [
                'nome' => 'Agronomia',
                'turno' => Curso::TURNO_ENUM['vespertino'],
                'cod_curso' => 91555,
                'grau_academico' => Curso::GRAU_ENUM['bacharelado'],
                'cor_padrao' => "#7CEF90",
                'vagas' => 40,
                'icone' => 'img-seeder/agronomia.svg',
            ],
            [
                'nome' => 'Ciência da Computação',
                'turno' => Curso::TURNO_ENUM['noturno'],
                'cod_curso' => 118468,
                'grau_academico' => Curso::GRAU_ENUM['bacharelado'],
                'cor_padrao' => "#9BE2FC",
                'vagas' => 80,
                'icone' => 'img-seeder/bcc.svg',
            ],
            [
                'nome' => 'Engenharia de Alimentos',
                'turno' => Curso::TURNO_ENUM['integral'],
                'cod_curso' => 118466,
                'grau_academico' => Curso::GRAU_ENUM['bacharelado'],
                'cor_padrao' => "#FFCA61",
                'vagas' => 80,
                'icone' => 'img-seeder/engalimentos.svg',
            ],
            [
                'nome' => 'Medicina Veterinária',
                'turno' => Curso::TURNO_ENUM['matutino'],
                'cod_curso' => 91561,
                'grau_academico' => Curso::GRAU_ENUM['bacharelado'],
                'cor_padrao' => "#F97171",
                'vagas' => 40,
                'icone' => 'img-seeder/veterinaria.svg',
            ],
            [
                'nome' => 'Medicina Veterinária',
                'turno' => Curso::TURNO_ENUM['vespertino'],
                'cod_curso' => 91561,
                'grau_academico' => Curso::GRAU_ENUM['bacharelado'],
                'cor_padrao' => "#F97171",
                'vagas' => 40,
                'icone' => 'img-seeder/veterinaria.svg',
            ],
            [
                'nome' => 'Zootecnia',
                'turno' => Curso::TURNO_ENUM['matutino'],
                'cod_curso' => 91738,
                'grau_academico' => Curso::GRAU_ENUM['bacharelado'],
                'cor_padrao' => "#D693F2",
                'vagas' => 40,
                'icone' => 'img-seeder/zootecnia.svg',
            ],
            [
                'nome' => 'Zootecnia',
                'turno' => Curso::TURNO_ENUM['vespertino'],
                'cod_curso' => 91738,
                'grau_academico' => Curso::GRAU_ENUM['bacharelado'],
                'cor_padrao' => "#D693F2",
                'vagas' => 40,
                'icone' => 'img-seeder/zootecnia.svg',
            ],
            [
                'nome' => 'Letras - Inglês e Português',
                'turno' => Curso::TURNO_ENUM['noturno'],
                'cod_curso' => 118470,
                'cor_padrao' => "#FC9BEF",
                'grau_academico' => Curso::GRAU_ENUM['licenciatura'],
                'vagas' => 80,
                'icone' => 'img-seeder/letras.svg',
            ],
            [
                'nome' => 'Pedagogia',
                'turno' => Curso::TURNO_ENUM['matutino'],
                'cod_curso' => 91969,
                'grau_academico' => Curso::GRAU_ENUM['licenciatura'],
                'cor_padrao' => "#FBEE3F",
                'vagas' => 40,
                'icone' => 'img-seeder/pedagogia.svg',
            ],
            [
                'nome' => 'Pedagogia',
                'turno' => Curso::TURNO_ENUM['vespertino'],
                'cod_curso' => 91969,
                'cor_padrao' => "#FBEE3F",
                'grau_academico' => Curso::GRAU_ENUM['licenciatura'],
                'vagas' => 40,
                'icone' => 'img-seeder/pedagogia.svg',
            ],
        ];

        Curso::insert($cursos);
    }
}
