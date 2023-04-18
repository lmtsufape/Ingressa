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
                if ($curso->vagas == 40) {
                    switch ($i) {
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
                $cota->cursos()->attach($curso, ['vagas_ocupadas' => 0, 'quantidade_vagas' => $quantidade, 'sisu_id' => $sisu->id]);
            }
        }
    }
}
