<?php

namespace App\Livewire;

use App\Models\Cota;
use App\Models\Inscricao;
use App\Policies\UserPolicy;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;

class CandidatoSearchBar extends Component
{
    public string $texto = '';
    public $inscricoes;
    public $highlightIndex;
    public $links;
    public $cotas;

    public function mount()
    {
        $userPolicy = new UserPolicy();
        if ($userPolicy->isAdminOrAnalistaGeral(auth()->user()))
            $this->cotas = Cota::pluck('id')->all();
        elseif ($userPolicy->soEhAnalistaHeteroidentificacao(auth()->user()))
            $this->cotas = Cota::whereIn('cod_cota', ['L2', 'L6', 'LB_Q', 'LI_Q'])->pluck('id')->all();
        elseif ($userPolicy->soEhAnalistaMedico(auth()->user()))
            $this->cotas = Cota::whereIn('cod_cota', ['L9', 'L13'])->pluck('id')->all();
        elseif ($userPolicy->ehAnalistaHeteroidentificacaoEMedico(auth()->user()))
            $this->cotas = Cota::whereIn('cod_cota', ['L2','L6','L9', 'L13', 'LB_Q', 'LI_Q'])->pluck('id')->all();
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
            $this->redirectRoute('inscricao.show.analisar.documentos', $dados);
        }
    }

    public function updatedTexto($value)
    {
        $texto = trim($value);
        $cotas = $this->cotas;
        $this->inscricoes = Inscricao::whereHas('candidato', function (Builder $query) use ($texto, $cotas) {
            $query->where('no_inscrito', 'ilike', "%$texto%")
                    ->orWhere('nu_cpf_inscrito', 'like', "%$texto%");
            })
        ->whereIn('cota_id', $cotas)->get();

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
