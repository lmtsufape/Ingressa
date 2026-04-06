<?php

namespace App\Strategies\Listagens;

use App\Models\Chamada;
use App\Models\Cota;
use App\Models\Curso;
use App\Models\Listagem;
use App\Queries\InscricaoIdsQuery;
use Barryvdh\DomPDF\Facade\Pdf;

final class ConvocacaoStrategy implements ListagemStrategy
{
    public function key()
    {
        return Listagem::TIPO_ENUM['convocacao'];
    }

    public function generate($request, $listagem)
    {
        $cursos = Curso::whereIn('id', $request->cursos)->orderBy('nome')->get();
        $cotas = Cota::whereIn('id', $request->cotas)->orderBy('id')->get();
        $inscricoes_query = app(InscricaoIdsQuery::class)->byCursoAndCota($request->chamada, $cursos->pluck('id')->all(), $cotas->pluck('id')->all());
        $chamada_nome = Chamada::find($request->chamada)?->nome;
        $inscricoes = collect();

        foreach ($cursos as $i => $curso) {
            $inscricoes_curso = collect();

            foreach ($cotas as $j => $cota) {
                //Juntar todos aqueles que são da ampla concorrencia independente do bonus de 10%
                $ids = data_get($inscricoes_query, "{$curso->id}.{$cota->id}", collect());

                if ($ids->isEmpty()) continue;
                if ($cota->getCodCota() == Cota::COD_COTA_ENUM['A0']) {
                    $inscricoes_curso->prepend($ids);
                } else {
                    $inscricoes_curso->push($ids);
                }
            }

            if ($inscricoes_curso->isNotEmpty()) {
                $inscricoes->push($inscricoes_curso);
            }
        }
        $pdf = Pdf::loadView('listagem.inscricoes', ['collect_inscricoes' => $inscricoes, 'chamada_nome' => $chamada_nome]);
        return $pdf->output();

    }
}
