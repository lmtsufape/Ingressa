<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Cota;
use Illuminate\Support\Facades\DB;

class AtualizarModalidades extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'modalidades:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Atualiza as modalidades das cotas de acordo com as novas modalidades do edital de 2025';

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
        $conversao = [
            // AC
            'Ampla concorrência' => 'Ampla concorrência',

            // LI_EP
            'Candidatos que, independentemente da renda, tenham cursado integralmente o ensino médio em escolas públicas (Lei nº 12.711/2012).'
                => 'Candidatos que, independentemente da renda, tenham cursado integralmente o ensino médio em escolas públicas ou em escolas comunitárias que atuam no âmbito da educação do campo conveniadas com o poder público',

            // LI_PCD
            'Candidatos com deficiência, independentemente da renda, que tenham cursado integralmente o ensino médio em escolas públicas (Lei nº 12.711/2012).'
                => 'Candidatos com deficiência, independentemente da renda, que tenham cursado integralmente o ensino médio em escolas públicas ou em escolas comunitárias que atuam no âmbito da educação do campo conveniadas com o poder público',

            // LI_Q
            'Candidatos autodeclarados quilombolas, independentemente da renda, tenham cursado integralmente o ensino médio em escolas públicas (Lei nº 12.711/2012).'
                => 'Candidatos autodeclarados quilombolas, independentemente da renda, que tenham cursado integralmente o ensino médio em escolas públicas ou em escolas comunitárias que atuam no âmbito da educação do campo conveniadas com o poder público',

            // LI_PPI
            'Candidatos autodeclarados pretos, pardos ou indígenas, independentemente da renda, que tenham cursado integralmente o ensino médio em escolas públicas (Lei nº 12.711/2012).'
                => 'Candidatos autodeclarados pretos, pardos ou indígenas, independentemente da renda, que tenham cursado integralmente o ensino médio em escolas públicas ou em escolas comunitárias que atuam no âmbito da educação do campo conveniadas com o poder público',

            // LB_EP
            'Candidatos com renda familiar bruta per capita igual ou inferior a 1 salário mínimo que tenham cursado integralmente o ensino médio em escolas públicas (Lei nº 12.711/2012).'
                => 'Candidatos com renda familiar bruta per capita igual ou inferior a 1 salário mínimo que tenham cursado integralmente o ensino médio em escolas públicas ou em escolas comunitárias que atuam no âmbito da educação do campo conveniadas com o poder público',

            // LB_PCD
            'Candidatos com deficiência, que tenham renda familiar bruta per capita igual ou inferior a 1 salário mínimo e que tenham cursado integralmente o ensino médio em escolas públicas (Lei nº 12.711/2012)'
                => 'Candidatos com deficiência, que tenham renda familiar bruta per capita igual ou inferior a 1 salário mínimo e que tenham cursado integralmente o ensino médio em escolas públicas ou em escolas comunitárias que atuam no âmbito da educação do campo conveniadas com o poder público',

            //LB_Q
            'Candidatos autodeclarados quilombolas, com renda familiar bruta per capita igual ou inferior a 1 salário mínimo e que tenham cursado integralmente o ensino médio em escolas públicas (Lei nº 12.711/2012).'
                => 'Candidatos autodeclarados quilombolas, com renda familiar bruta per capita igual ou inferior a 1 salário mínimo e que tenham cursado integralmente o ensino médio em escolas públicas ou em escolas comunitárias que atuam no âmbito da educação do campo conveniadas com o poder público',

            // LB_PPI
            'Candidatos autodeclarados pretos, pardos ou indígenas, com renda familiar bruta per capita igual ou inferior a 1 salário mínimo e que tenham cursado integralmente o ensino médio em escolas públicas (Lei nº 12.711/2012).'
                => 'Candidatos autodeclarados pretos, pardos ou indígenas, com renda familiar bruta per capita igual ou inferior a 1 salário mínimo e que tenham cursado integralmente o ensino médio em escolas públicas ou em escolas comunitárias que atuam no âmbito da educação do campo conveniadas com o poder público'
        ];

        DB::transaction(function () use ($conversao) {
            foreach (Cota::all() as $cota) {
                $modalidade = $conversao[$cota->nome];
    
                $cota->update([
                    'nome' => $modalidade,
                    'descricao' => $modalidade
                ]);
            }
        });
    }
}
