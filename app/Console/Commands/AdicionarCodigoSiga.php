<?php

namespace App\Console\Commands;

use App\Models\Curso;
use Illuminate\Console\Command;

class AdicionarCodigoSiga extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'adicionar:cod_siga';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Adiciona os códigos do SIGA na tabela dos cursos';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $cursos = Curso::all();
        $mapCods = [
            91555  => 44,
            118468 => 95,
            118466 => 93,
            118470 => 94,
            91969  => 47,
            91561  => 45,
            91738  => 46,
            1682932 => 201,
            1697875 => 0,
        ];

        foreach($cursos as $curso){
            $curso->cod_siga = $mapCods[$curso->cod_curso];
            $curso->update();
        }

    }
}
