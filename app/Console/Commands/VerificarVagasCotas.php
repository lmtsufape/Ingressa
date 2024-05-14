<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Curso;
use Illuminate\Support\Facades\Log;

class VerificarVagasCotas extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'verify:vagasCotas';

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
        $cursos = Curso::where('vagas', 60)->get();
        $limiteVagas = ['AC' => 7, 'LB_PPI' => 4, 'LB_Q' => 1, 'LB_PCD' => 1, 'LB_EP' => 1, 'LI_PPI' => 4, 'LI_Q' => 0, 'LI_PCD' => 1, 'LI_EP' => 1];
        $cotasEstouradas = collect();

        foreach ($cursos as $curso) {
            foreach ($curso->cotas as $cota) {
                if ($cota->pivot->vagas_ocupadas > $limiteVagas[$cota->cod_novo]) $cotasEstouradas->push($cota->pivot);
            }
        }

        if ($cotasEstouradas->count() > 0) {
            dd($cotasEstouradas);
        }

        dd('tudo ok');
    }
}
