<?php

namespace App\Library;

use App\Models\Cota;
use App\Models\Curso;
use App\Models\Sisu;

class Utilitario {


    public static function criarCotaCurso(Sisu $sisu)
    {
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
                $cota->cursos()->attach($curso, ['vagas_ocupadas' => 0, 'quantidade_vagas' => $quantidade, 'sisu_id' => $sisu->id]);
            }
        }
    }
}
