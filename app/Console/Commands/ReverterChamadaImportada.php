<?php

namespace App\Console\Commands;

use App\Models\Chamada;
use App\Models\Inscricao;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ReverterChamadaImportada extends Command
{
    protected $signature = 'chamada:reverter
                            {chamada_id : ID da chamada criada/importada por engano}
                            {--force : Executa sem pedir confirmação}';

    protected $description = 'Remove as inscrições de uma chamada importada por engano e depois remove a própria chamada.';

    public function handle(): int
    {
        $chamadaId = (int) $this->argument('chamada_id');

        $chamada = Chamada::query()->find($chamadaId);

        if (!$chamada) {
            $this->error("Chamada {$chamadaId} não encontrada.");
            return self::FAILURE;
        }

        $totalInscricoes = Inscricao::query()
            ->where('chamada_id', $chamada->id)
            ->count();

        $this->warn("Atenção: esta operação vai remover:");
        $this->line("- Chamada ID: {$chamada->id}");
        $this->line("- Inscrições vinculadas: {$totalInscricoes}");
        $this->line("Nenhuma tabela pivot será alterada por este comando.");
        
        if (!$this->option('force')) {
            if (!$this->confirm('Deseja realmente continuar?')) {
                $this->info('Operação cancelada.');
                return self::SUCCESS;
            }
        }

        DB::transaction(function () use ($chamada) {
            Inscricao::query()
                ->where('chamada_id', $chamada->id)
                ->delete();

            $chamada->delete();
        });

        $this->info("Reversão concluída com sucesso.");
        $this->line("Inscrições removidas: {$totalInscricoes}");
        $this->line("Chamada removida: {$chamadaId}");

        return self::SUCCESS;
    }
}
