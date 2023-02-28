<?php

namespace App\Http\Livewire;

use App\Models\Inscricao;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;

class CandidatoSearchBar extends Component
{
    public $texto;
    public $inscricoes;
    public $highlightIndex;
    public $links;

    public function mount()
    {
        $this->limpar();
    }

    public function limpar()
    {
        $this->texto = '';
        $this->inscricoes = null;
        $this->highlightIndex = 0;
    }

    public function selecionarCandidato()
    {
        $inscricao = $this->inscricoes[$this->highlightIndex] ?? null;
        if ($inscricao) {
            $dados = ['sisu_id' => $inscricao->sisu->id, 'chamada_id' => $inscricao->chamada->id, 'curso_id' => $inscricao->curso->id, 'inscricao_id' => $inscricao->id];
            $this->redirect(route('inscricao.show.analisar.documentos', $dados));
        }
    }

    public function updatedTexto()
    {
        $texto = $this->texto;
        $this->inscricoes = Inscricao::whereHas('candidato', function (Builder $query) use ($texto) {
            $query->where('no_inscrito', 'ilike', '%'.$texto.'%')
                ->orWhere('nu_cpf_inscrito', 'like', '%'.$texto.'%');
            })
            ->get();
        $this->links = $this->inscricoes->map(function ($item) {
            $dados = ['sisu_id' => $item->sisu->id, 'chamada_id' => $item->chamada->id, 'curso_id' => $item->curso->id, 'inscricao_id' => $item->id];
            return route('inscricao.show.analisar.documentos', $dados);
        });
    }

    public function render()
    {
        return view('livewire.candidato-search-bar');
    }
}
