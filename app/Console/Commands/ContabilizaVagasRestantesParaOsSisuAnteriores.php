<?php

namespace App\Console\Commands;

use App\Models\Inscricao;
use App\Models\Sisu;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ContabilizaVagasRestantesParaOsSisuAnteriores extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sisu:contavagasrestantes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Utilizado na atualização para permitir edição em mais de uma edição do SiSU ao mesmo tempo. Preenche as vagas ocupadas para todos os sisus, com exceção do ultimo.';

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
        foreach ($sisus as $sisu) {
            $vagas_ocupadas_por_cota_curso = DB::table('inscricaos')
                ->select(DB::raw('curso_id, cota_vaga_ocupada_id, count(*) as qtd'))
                ->where([['sisu_id', $sisu->id], ['cd_efetivado', 1]])
                ->whereNotNull('semestre_entrada')
                ->groupBy('curso_id', 'cota_vaga_ocupada_id')->get();
            foreach ($vagas_ocupadas_por_cota_curso as $value) {
                DB::table('cota_curso')
                    ->where([['sisu_id', $sisu->id], ['curso_id', $value->curso_id], ['cota_id', $value->cota_vaga_ocupada_id]])
                    ->update(['vagas_ocupadas' => $value->qtd]);
            }
        }
    }
}
