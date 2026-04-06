<?php

namespace App\UseCases\Listagem;

use App\Models\Listagem;
use App\Strategies\Listagens\StrategyResolver;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class CriarListagem
{

    public function __construct(private StrategyResolver $resolver) {}
    public function execute($request)
    {
        DB::transaction(function () use ($request) {
            $listagem = new Listagem();
            $listagem->setAtributes((array) $request);
            $listagem->caminho_listagem = 'caminho';
            $listagem->save();
            $strategy = $this->resolver->resolve($request->tipo);
            $listagem->caminho_listagem = $this->salvarListagem($listagem, $strategy->generate($request, $listagem));
            $listagem->update();
        });
    }

    private function salvarListagem(Listagem $listagem, $arquivo)
    {
        $caminho = 'listagem/' . $listagem->id . '/listagem.pdf';
        $salvou = Storage::disk('public')->put($caminho, $arquivo, ['visibility' => 'public']);

        if (! $salvou) {
            throw new RuntimeException("Falha ao salvar o arquivo em {$caminho}");
        }

        if (! Storage::disk('public')->exists($caminho)) {
            throw new RuntimeException("Arquivo não encontrado após salvar: {$caminho}");
        }

        return $caminho;
    }
}
