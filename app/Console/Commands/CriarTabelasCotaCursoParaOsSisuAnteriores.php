<?php

namespace App\Console\Commands;

use App\Models\Cota;
use App\Models\Curso;
use App\Models\Sisu;
use Illuminate\Console\Command;

class CriarTabelasCotaCursoParaOsSisuAnteriores extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sisu:criatabelacotacurso';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Utilizado na atualização para permitir edição em mais de uma edição do SiSU ao mesmo tempo. Cria as tabelas cota_curso para todos os sisus, com exceção do ultimo.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $sisus = Sisu::latest()->skip(1)->get();
        $cotas = Cota::all();
        $cursos = Curso::all();
        foreach ($sisus as $sisu) {
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
}
