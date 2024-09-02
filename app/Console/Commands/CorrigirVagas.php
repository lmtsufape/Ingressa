<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Curso;
use App\Models\Sisu;

class CorrigirVagas extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:vagasCotas';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $cursos = Curso::all();
        $ultimoSisu = Sisu::orderBy('created_at', 'desc')->first();
        $vagas20 = ['AC' => 7, 'LB_PPI' => 4, 'LB_Q' => 1, 'LB_PCD' => 1, 'LB_EP' => 1, 'LI_PPI' => 4, 'LI_Q' => 0, 'LI_PCD' => 1, 'LI_EP' => 1];
        $vagas60 = ['AC' => 30, 'LB_PPI' => 10, 'LB_Q' => 1, 'LB_PCD' => 2, 'LB_EP' => 2, 'LI_PPI' => 10, 'LI_Q' => 0, 'LI_PCD' => 2, 'LI_EP' => 3];

        foreach ($cursos as $curso) {
            if ($curso->vagas == 60) $curso->vagas = 20;
            else if ($curso->vagas == 20) $curso->vagas = 60;

            if ($curso->semestre == 1) $curso->semestre = 2;
            else if ($curso->semestre == 2) $curso->semestre = 1;

            $curso->update();
            $cotas = $curso->cotas()->wherePivot('sisu_id', $ultimoSisu->id)->get();

            if ($curso->vagas == 60) {
                foreach ($cotas as $cota) {
                    $cota->pivot->quantidade_vagas = $vagas60[$cota->cod_novo];
                    $cota->pivot->update();
                }
            } else if ($curso->vagas == 20) {
                foreach ($cotas as $cota) {
                    $cota->pivot->quantidade_vagas = $vagas20[$cota->cod_novo];
                    $cota->pivot->update();
                }
            }
        }

        return 0;
    }
}
