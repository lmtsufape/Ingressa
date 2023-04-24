<?php

namespace App\Console\Commands;

use App\Models\Sisu;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AdicionarSisuACotaCurso extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sisu:addsisuid';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Utilizado na atualização para permitir edição em mais de uma edição do SiSU ao mesmo tempo. Preenche o campo sisu_id com o id do ultimo sisu na tabela cota_curso.';

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
        $id_ultimo_sisu = Sisu::latest()->first()->id;
        DB::table('cota_curso')->update(['sisu_id' => $id_ultimo_sisu]);
    }
}
