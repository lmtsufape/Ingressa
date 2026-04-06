<?php

namespace App\Queries;

use App\Models\Inscricao;
use Illuminate\Support\Collection;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class InscricaoIdsQuery
{
    public function byCursoAndCota($chamada_id, $cursos_id, $cotas_id)
    {
        $inscricoes = Inscricao::query()->select(['inscricaos.*'])
            ->where('inscricaos.chamada_id', $chamada_id)
            ->whereIn('inscricaos.curso_id', $cursos_id)
            ->whereIn('inscricaos.cota_id', $cotas_id)
            ->join('candidatos', 'inscricaos.candidato_id', '=', 'candidatos.id')
            ->join('users', 'users.id', '=', 'candidatos.user_id');

        $inscricoes = QueryBuilder::for($inscricoes)->allowedSorts([
            AllowedSort::field('name', 'name'),
            AllowedSort::field('nota', 'inscricaos.nu_nota_candidato'),
        ])->get();

        return $inscricoes->groupBy('curso_id')
            ->map(
                fn($query) =>
                $query->groupBy('cota_id')
            );
    }

    public function byCurso(
        int $chamadaId,
        array $cursoIds,
        array $cotaIds,
    ): Collection {
        $inscricoes = $this->byCursoAndCota($chamadaId, $cursoIds, $cotaIds);

        if ($inscricoes->isNotEmpty()) {
            return $inscricoes->map(
                    fn ($grupoCurso) => $grupoCurso->map(
                        fn ($grupoCota) => $grupoCota->pluck('id')
                    )
                );
        }
        return $inscricoes;
    }
}
