<?php

namespace App\Console\Commands;

use App\Library\Utilitario;
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
        foreach ($sisus as $sisu) {
            Utilitario::criarCotaCurso($sisu);
        }
    }
}
