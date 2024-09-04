<?php

namespace App\Console\Commands;

use App\Models\Cota;
use Illuminate\Console\Command;

class cotasRename extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cotas:rename';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Adiciona o novo nome das cotas';

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
        // Cota::where('cod_cota', 'B7104')->update(['codigo_novo' => 'B']);
        $nome = 'Ampla concorrência';
        Cota::where('cod_cota', 'A0')->update(['cod_novo' => 'AC'], ['nome' => $nome], ['descricao' => $nome]);

        $nome = 'Candidatos autodeclarados pretos, pardos ou indígenas, com renda familiar bruta per capita igual ou inferior a 1 salário mínimo e que tenham cursado integralmente o ensino médio em escolas públicas (Lei nº 12.711/2012).';
        Cota::where('cod_cota', 'L2')->update(['cod_novo' => 'LB_PPI'], ['nome' => $nome], ['descricao' => $nome]);
        
        $nome = 'Candidatos com deficiência, que tenham renda familiar bruta per capita igual ou inferior a 1 salário mínimo e que tenham cursado integralmente o ensino médio em escolas públicas (Lei nº 12.711/2012).';
        Cota::where('cod_cota', 'L9')->update(['cod_novo' => 'LB_PCD'], ['nome' => $nome], ['descricao' => $nome]);

        $nome = 'Candidatos com renda familiar bruta per capita igual ou inferior a 1 salário mínimo que tenham cursado integralmente o ensino médio em escolas públicas (Lei nº 12.711/2012).';
        Cota::where('cod_cota', 'L1')->update(['cod_novo' => 'LB_EP'], ['nome' => $nome], ['descricao' => $nome]);

        $nome = 'Candidatos autodeclarados pretos, pardos ou indígenas, independentemente da renda, que tenham cursado integralmente o ensino médio em escolas públicas (Lei nº 12.711/2012).';
        Cota::where('cod_cota', 'L6')->update(['cod_novo' => 'LI_PPI'], ['nome' => $nome], ['descricao' => $nome]);

        $nome = 'Candidatos com deficiência, independentemente da renda, que tenham cursado integralmente ensino médio em escolas públicas (Lei nº 12.711/2012).';
        Cota::where('cod_cota', 'L13')->update(['cod_novo' => 'LI_PCD'], ['nome' => $nome], ['descricao' => $nome]);

        $nome = 'Candidatos que, independentemente da renda, tenham cursado integralmente o ensino médio em escolas públicas (Lei nº 12.711/2012).';
        Cota::where('cod_cota', 'L5')->update(['cod_novo' => 'LI_EP'], ['nome' => $nome], ['descricao' => $nome]);

        Cota::where('cod_cota', 'L10')->update(['cod_novo' => 'L10']);

        Cota::where('cod_cota', 'L14')->update(['cod_novo' => 'L14']);

        Cota::create([
            'nome' => 'Candidatos autodeclarados quilombolas, com renda familiar bruta per capita igual ou inferior a 1 salário mínimo e que tenham cursado integralmente o ensino médio em escolas públicas (Lei nº 12.711/2012).',
            'descricao' => 'Candidatos autodeclarados quilombolas, com renda familiar bruta per capita igual ou inferior a 1 salário mínimo e que tenham cursado integralmente o ensino médio em escolas públicas (Lei nº 12.711/2012).',
            'cod_cota' => 'LB_Q',
            'cod_novo' => 'LB_Q',
        ]);

        Cota::create([
            'nome' => 'Candidatos autodeclarados quilombolas, independentemente da renda, tenham cursado integralmente o ensino médio em escolas públicas (Lei nº 12.711/2012).',
            'descricao' => 'Candidatos autodeclarados quilombolas, independentemente da renda, tenham cursado integralmente o ensino médio em escolas públicas (Lei nº 12.711/2012).',
            'cod_cota' => 'LI_Q',
            'cod_novo' => 'LI_Q',
        ]);

        return 0;
    }
}
