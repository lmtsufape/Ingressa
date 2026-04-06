<?php

namespace App\Strategies\Listagens;

use App\Http\Requests\ListagemStoreRequest;
use App\Models\Chamada;
use App\Models\Cota;
use App\Models\Curso;
use App\Models\Inscricao;
use App\Models\Listagem;
use App\Queries\InscricaoIdsQuery;
use Barryvdh\DomPDF\Facade\Pdf;

final class ResultadoStrategy implements ListagemStrategy
{
    public function key()
    {
        return Listagem::TIPO_ENUM['resultado'];
    }

    public function generate($request, Listagem $listagem)
    {
        $chamada = Chamada::find($request->chamada);
        $cursos = Curso::whereIn('id', $request->cursos)->orderBy('nome')->get();
        $cotas = Cota::whereIn('id', $request->cotas)->orderBy('id')->get();

        $inscricoes = app(InscricaoIdsQuery::class)->byCurso($request->chamada, $cursos->pluck('id')->all(), $cotas->pluck('id')->all());

        $pdf = Pdf::loadView('listagem.resultado', ['collect_inscricoes' => $inscricoes, 'chamada' => $chamada])->setPaper('a4', 'landscape');

        return $pdf->output();
    }
}
