<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cota;
use App\Models\Curso;

class CotaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cotas = [
            [
                'nome' => 'Ampla concorrência',
                'descricao' => 'Ampla concorrência',
                'cod_cota' => 'A0',
            ],
            [
                'nome' => 'Candidatos com renda familiar bruta per capita igual ou inferior a 1,5 salário mínimo que tenham cursado integralmente o ensino médio em escolas públicas (Lei nº 12.711/2012).',
                'descricao' => 'Candidatos com renda familiar bruta per capita igual ou inferior a 1,5 salário mínimo que tenham cursado integralmente o ensino médio em escolas públicas (Lei nº 12.711/2012).',
                'cod_cota' => 'L1',
            ],
            [
                'nome' => 'Candidatos autodeclarados pretos, pardos ou indígenas, com renda familiar bruta per capita igual ou inferior a 1,5 salário mínimo e que tenham cursado integralmente o ensino médio em escolas públicas',
                'descricao' => 'Candidatos autodeclarados pretos, pardos ou indígenas, com renda familiar bruta per capita igual ou inferior a 1,5 salário mínimo e que tenham cursado integralmente o ensino médio em escolas públicas (Lei nº 12.711/2012).',
                'cod_cota' => 'L2',
            ],
            [
                'nome' => 'Candidatos que, independentemente da renda (art. 14, II, Portaria Normativa nº 18/2012), tenham cursado integralmente o ensino médio em escolas públicas (Lei nº 12.711/2012).',
                'descricao' => 'Candidatos que, independentemente da renda (art. 14, II, Portaria Normativa nº 18/2012), tenham cursado integralmente o ensino médio em escolas públicas (Lei nº 12.711/2012).',
                'cod_cota' => 'L5',
            ],
            [
                'nome' => 'Candidatos autodeclarados pretos, pardos ou indígenas que, independentemente da renda (art. 14, II, Portaria Normativa nº 18/2012), tenham cursado integralmente o ensino médio em escolas públicas (Lei nº 12.711/2012).',
                'descricao' => 'Candidatos autodeclarados pretos, pardos ou indígenas que, independentemente da renda (art. 14, II, Portaria Normativa nº 18/2012), tenham cursado integralmente o ensino médio em escolas públicas (Lei nº 12.711/2012).',
                'cod_cota' => 'L6',
            ],
            [
                'nome' => 'Candidatos com deficiência que tenham renda familiar bruta per capita igual ou inferior a 1,5 salário mínimo e que tenham cursado integralmente o ensino médio em escolas públicas (Lei nº 12.711/2012).',
                'descricao' => 'Candidatos com deficiência que tenham renda familiar bruta per capita igual ou inferior a 1,5 salário mínimo e que tenham cursado integralmente o ensino médio em escolas públicas (Lei nº 12.711/2012).',
                'cod_cota' => 'L9',
            ],
            [
                'nome' => 'Candidatos com deficiência autodeclarados pretos, pardos ou indígenas, que tenham renda familiar bruta per capita igual ou inferior a 1,5 salário mínimo e que tenham cursado integralmente o ensino médio em escolas públicas (Lei nº 12.711/2012)',
                'descricao' => 'Candidatos com deficiência autodeclarados pretos, pardos ou indígenas, que tenham renda familiar bruta per capita igual ou inferior a 1,5 salário mínimo e que tenham cursado integralmente o ensino médio em escolas públicas (Lei nº 12.711/2012)',
                'cod_cota' => 'L10',
            ],
            [
                'nome' => 'Candidatos com deficiência que, independentemente da renda (art. 14, II, Portaria Normativa nº 18/2012), tenham cursado integralmente o ensino médio em escolas públicas (Lei nº 12.711/2012).',
                'descricao' => 'Candidatos com deficiência que, independentemente da renda (art. 14, II, Portaria Normativa nº 18/2012), tenham cursado integralmente o ensino médio em escolas públicas (Lei nº 12.711/2012).',
                'cod_cota' => 'L13',
            ],
            [
                'nome' => 'Candidatos com deficiência autodeclarados pretos, pardos ou indígenas que, independentemente da renda (art. 14, II, Portaria Normativa nº 18/2012), tenham cursado integralmente o ensino médio em escolas públicas (Lei nº 12.711/2012).',
                'descricao' => 'Candidatos com deficiência autodeclarados pretos, pardos ou indígenas que, independentemente da renda (art. 14, II, Portaria Normativa nº 18/2012), tenham cursado integralmente o ensino médio em escolas públicas (Lei nº 12.711/2012).',
                'cod_cota' => 'L14',
            ],
            [
                'nome' => 'Candidatos que tenham cursado integralmente o ensino médio em qualquer uma das escolas situadas nas microrregiões do Agreste ou do Sertão de Pernambuco.',
                'descricao' => 'que tenham cursado integralmente o ensino médio em qualquer uma das escolas situadas nas microrregiões do Agreste ou do Sertão de Pernambuco.',
                'cod_cota' => 'B4342',
            ],
        ];

        Cota::insert($cotas);
        $cotas = Cota::all();
        $cursos = Curso::all();
        foreach($cursos as $curso){
            foreach($cotas as $i => $cota){
                if($curso->vagas == 40){
                    switch($i){
                        case 0:
                            $quantidade = 20;
                            break;
                        case 1:
                            $quantidade = 2;
                            break;
                        case 2:
                            $quantidade = 6;
                            break;
                        case 3:
                            $quantidade = 2;
                            break;
                        case 4:
                            $quantidade = 6;
                            break;
                        case 5:
                            $quantidade = 1;
                            break;
                        case 6:
                            $quantidade = 1;
                            break;
                        case 7:
                            $quantidade = 1;
                            break;
                        case 8:
                            $quantidade = 1;
                            break;
                        case 9:
                            $quantidade = 0;
                            break;
                    }
                }else{
                    switch($i){
                        case 0:
                            $quantidade = 40;
                            break;
                        case 1:
                            $quantidade = 6;
                            break;
                        case 2:
                            $quantidade = 12;
                            break;
                        case 3:
                            $quantidade = 6;
                            break;
                        case 4:
                            $quantidade = 12;
                            break;
                        case 5:
                            $quantidade = 1;
                            break;
                        case 6:
                            $quantidade = 1;
                            break;
                        case 7:
                            $quantidade = 1;
                            break;
                        case 8:
                            $quantidade = 1;
                            break;
                        case 9:
                            $quantidade = 0;
                            break;
                    }
                }
                $cota->cursos()->attach($curso, ['vagas_ocupadas' => 0, 'quantidade_vagas' => $quantidade]);
            }
        }
    }
}
