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
                'cod_novo' => 'AC'
            ],
            [
                'nome' => 'Candidatos com renda familiar bruta per capita igual ou inferior a 1 salário mínimo que tenham cursado integralmente o ensino médio em escolas públicas ou em escolas comunitárias que atuam no âmbito da educação do campo conveniadas com o poder público',
                'descricao' => 'Candidatos com renda familiar bruta per capita igual ou inferior a 1 salário mínimo que tenham cursado integralmente o ensino médio em escolas públicas ou em escolas comunitárias que atuam no âmbito da educação do campo conveniadas com o poder público',
                'cod_cota' => 'L1',
                'cod_novo' => 'LB_EP'
            ],
            [
                'nome' => 'Candidatos autodeclarados pretos, pardos ou indígenas, com renda familiar bruta per capita igual ou inferior a 1 salário mínimo e que tenham cursado integralmente o ensino médio em escolas públicas ou em escolas comunitárias que atuam no âmbito da educação do campo conveniadas com o poder público',
                'descricao' => 'Candidatos autodeclarados pretos, pardos ou indígenas, com renda familiar bruta per capita igual ou inferior a 1 salário mínimo e que tenham cursado integralmente o ensino médio em escolas públicas ou em escolas comunitárias que atuam no âmbito da educação do campo conveniadas com o poder público',
                'cod_cota' => 'L2',
                'cod_novo' => 'LB_PPI'
            ],
            [
                'nome' => 'Candidatos que, independentemente da renda, tenham cursado integralmente o ensino médio em escolas públicas ou em escolas comunitárias que atuam no âmbito da educação do campo conveniadas com o poder público',
                'descricao' => 'Candidatos que, independentemente da renda, tenham cursado integralmente o ensino médio em escolas públicas ou em escolas comunitárias que atuam no âmbito da educação do campo conveniadas com o poder público',
                'cod_cota' => 'L5',
                'cod_novo' => 'LI_EP'
            ],
            [
                'nome' => 'Candidatos autodeclarados pretos, pardos ou indígenas, independentemente da renda, que tenham cursado integralmente o ensino médio em escolas públicas ou em escolas comunitárias que atuam no âmbito da educação do campo conveniadas com o poder público',
                'descricao' => 'Candidatos autodeclarados pretos, pardos ou indígenas, independentemente da renda, que tenham cursado integralmente o ensino médio em escolas públicas ou em escolas comunitárias que atuam no âmbito da educação do campo conveniadas com o poder público',
                'cod_cota' => 'L6',
                'cod_novo' => 'LI_PPI'
            ],
            [
                'nome' => 'Candidatos com deficiência, que tenham renda familiar bruta per capita igual ou inferior a 1 salário mínimo e que tenham cursado integralmente o ensino médio em escolas públicas ou em escolas comunitárias que atuam no âmbito da educação do campo conveniadas com o poder público',
                'descricao' => 'Candidatos com deficiência, que tenham renda familiar bruta per capita igual ou inferior a 1 salário mínimo e que tenham cursado integralmente o ensino médio em escolas públicas ou em escolas comunitárias que atuam no âmbito da educação do campo conveniadas com o poder público',
                'cod_cota' => 'L9',
                'cod_novo' => 'LB_PCD'
            ],
            // [
            //     'nome' => 'Candidatos com deficiência autodeclarados pretos, pardos ou indígenas, que tenham renda familiar bruta per capita igual ou inferior a 1,5 salário mínimo e que tenham cursado integralmente o ensino médio em escolas públicas (Lei nº 12.711/2012)',
            //     'descricao' => 'Candidatos com deficiência autodeclarados pretos, pardos ou indígenas, que tenham renda familiar bruta per capita igual ou inferior a 1,5 salário mínimo e que tenham cursado integralmente o ensino médio em escolas públicas (Lei nº 12.711/2012)',
            //     'cod_cota' => 'L10',
            //     'cod_novo' => 'L10'
            // ],
            [
                'nome' => 'Candidatos com deficiência, independentemente da renda, que tenham cursado integralmente o ensino médio em escolas públicas ou em escolas comunitárias que atuam no âmbito da educação do campo conveniadas com o poder público',
                'descricao' => 'Candidatos com deficiência, independentemente da renda, que tenham cursado integralmente o ensino médio em escolas públicas ou em escolas comunitárias que atuam no âmbito da educação do campo conveniadas com o poder público',
                'cod_cota' => 'L13',
                'cod_novo' => 'LI_PCD'
            ],
            // [
            //     'nome' => 'Candidatos com deficiência autodeclarados pretos, pardos ou indígenas que, independentemente da renda (art. 14, II, Portaria Normativa nº 18/2012), tenham cursado integralmente o ensino médio em escolas públicas (Lei nº 12.711/2012).',
            //     'descricao' => 'Candidatos com deficiência autodeclarados pretos, pardos ou indígenas que, independentemente da renda (art. 14, II, Portaria Normativa nº 18/2012), tenham cursado integralmente o ensino médio em escolas públicas (Lei nº 12.711/2012).',
            //     'cod_cota' => 'L14',
            //     'cod_novo' => 'L14'
            // ],
            /*[
                'nome' => 'Candidatos que tenham cursado integralmente o ensino médio em qualquer uma das escolas situadas nas microrregiões do Agreste ou do Sertão de Pernambuco.',
                'descricao' => 'que tenham cursado integralmente o ensino médio em qualquer uma das escolas situadas nas microrregiões do Agreste ou do Sertão de Pernambuco.',
                'cod_cota' => 'B4342',
            ],*/
            [
                'nome' => 'Candidatos autodeclarados quilombolas, com renda familiar bruta per capita igual ou inferior a 1 salário mínimo e que tenham cursado integralmente o ensino médio em escolas públicas ou em escolas comunitárias que atuam no âmbito da educação do campo conveniadas com o poder público',
                'descricao' => 'Candidatos autodeclarados quilombolas, com renda familiar bruta per capita igual ou inferior a 1 salário mínimo e que tenham cursado integralmente o ensino médio em escolas públicas ou em escolas comunitárias que atuam no âmbito da educação do campo conveniadas com o poder público',
                'cod_cota' => 'LB_Q',
                'cod_novo' => 'LB_Q',
            ],
            [
                'nome' => 'Candidatos autodeclarados quilombolas, independentemente da renda, que tenham cursado integralmente o ensino médio em escolas públicas ou em escolas comunitárias que atuam no âmbito da educação do campo conveniadas com o poder público',
                'descricao' => 'Candidatos autodeclarados quilombolas, independentemente da renda, que tenham cursado integralmente o ensino médio em escolas públicas ou em escolas comunitárias que atuam no âmbito da educação do campo conveniadas com o poder público',
                'cod_cota' => 'LI_Q',
                'cod_novo' => 'LI_Q',
            ]
        ];

        Cota::insert($cotas);
        $cotas = Cota::all();
        $cursos = Curso::all();
        foreach ($cursos as $curso) {
            foreach ($cotas as $i => $cota) {
                $quantidade = 0;
                if ($curso->vagas == 20) {
                    switch ($cota->cod_novo) {
                        case 'AC':
                            $quantidade = 7;
                            break;
                        case 'LB_PPI':
                            $quantidade = 4;
                            break;
                        case 'LB_Q':
                            $quantidade = 1;
                            break;
                        case 'LB_PCD':
                            $quantidade = 1;
                            break;
                        case 'LB_EP':
                            $quantidade = 1;
                            break;
                        case 'LI_PPI':
                            $quantidade = 4;
                            break;
                        case 'LI_Q':
                            $quantidade = 0;
                            break;
                        case 'LI_PCD':
                            $quantidade = 1;
                            break;
                        case 'LI_EP':
                            $quantidade = 1;
                            break;
                    }
                } else if ($curso->vagas == 60) {
                    switch ($cota->cod_novo) {
                        case 'AC':
                            $quantidade = 30;
                            break;
                        case 'LB_PPI':
                            $quantidade = 10;
                            break;
                        case 'LB_Q':
                            $quantidade = 1;
                            break;
                        case 'LB_PCD':
                            $quantidade = 2;
                            break;
                        case 'LB_EP':
                            $quantidade = 2;
                            break;
                        case 'LI_PPI':
                            $quantidade = 10;
                            break;
                        case 'LI_Q':
                            $quantidade = 0;
                            break;
                        case 'LI_PCD':
                            $quantidade = 2;
                            break;
                        case 'LI_EP':
                            $quantidade = 3;
                            break;
                    }
                } else if ($curso->vagas == 80) {
                    switch ($cota->cod_novo) {
                        case 'AC':
                            $quantidade = 40;
                            break;
                        case 'LB_PPI':
                            $quantidade = 14;
                            break;
                        case 'LB_Q':
                            $quantidade = 1;
                            break;
                        case 'LB_PCD':
                            $quantidade = 2;
                            break;
                        case 'LB_EP':
                            $quantidade = 3;
                            break;
                        case 'LI_PPI':
                            $quantidade = 14;
                            break;
                        case 'LI_Q':
                            $quantidade = 0;
                            break;
                        case 'LI_PCD':
                            $quantidade = 2;
                            break;
                        case 'LI_EP':
                            $quantidade = 4;
                            break;
                    }
                }
                $cota->cursos()->attach($curso, ['vagas_ocupadas' => 0, 'quantidade_vagas' => $quantidade, 'sisu_id' => 1]);
            }
        }
    }
}
