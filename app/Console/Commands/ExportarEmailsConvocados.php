<?php

namespace App\Console\Commands;

use App\Models\Chamada;
use App\Models\Cota;
use App\Models\Curso;
use App\Models\Inscricao;
use App\Queries\InscricaoIdsQuery;
use Illuminate\Console\Command;
use League\Csv\Writer;

class ExportarEmailsConvocados extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'convocados:exportar-emails {chamada_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gera um csv com o nome e e-mail dos candidatos convocados';

    /**
     * Execute the console command.
     */
    public function handle()
    {
     $chamadaId = (int) $this->argument('chamada_id');

        $chamada = Chamada::findOrFail($chamadaId);

        $inscricoesAgrupadas = app(InscricaoIdsQuery::class)->byCursoAndCota(
            $chamada->id,
            Curso::pluck('id')->all(),
            Cota::pluck('id')->all()
        );

        $inscricaoIds = $inscricoesAgrupadas
            ->flatten(2)
            ->pluck('id')
            ->filter()
            ->unique()
            ->values();

        if ($inscricaoIds->isEmpty()) {
            $this->warn('Nenhuma inscrição encontrada para exportação.');
            return self::SUCCESS;
        }

        $inscricoes = Inscricao::query()
            ->with(['candidato.user'])
            ->whereIn('id', $inscricaoIds->all())
            ->get();

        $caminhoArquivo = storage_path("app/lista_emails_convocados_chamada_{$chamada->id}.csv");

        $csv = Writer::from($caminhoArquivo, 'w+');
        $csv->setDelimiter(';');
        $csv->setOutputBOM(Writer::BOM_UTF8);

        $csv->insertOne(['nome', 'cpf', 'email']);

        $csv->insertAll(
            $inscricoes->map(fn ($inscrito) => [
                $inscrito->candidato->no_inscrito,
                $inscrito->candidato->nu_cpf_inscrito ?? '',
                $inscrito->ds_email ?? '',
            ])->all()
        );

        $this->info("Arquivo CSV gerado com sucesso: {$caminhoArquivo}");

        return self::SUCCESS;
    }
}
