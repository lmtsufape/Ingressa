<div x-data>
    <input 
        type="text"
        class="form-control campoDeTexto"
        placeholder="Procurar Candidato..."
        wire:model="texto"
        wire:keydown.escape="limpar"
        wire:keydown.tab="limpar"
        wire:keydown.enter="selecionarCandidato"
        @click.outside="if ($wire.texto != '') $wire.limpar()"
    />
    @if(!empty($texto))
    <div class="dropdown-menu d-block overflow-auto" style="max-height: 75vh;">
        @if(!empty($inscricoes))
        @foreach($inscricoes as $i => $inscricao)
        <a href="{{ $links->get($i) }}" class="dropdown-item">{{ $inscricao->candidato->no_inscrito }} - {{$inscricao->sisu->edicao}}</a>
        @endforeach
        @else
        <div class="">Nenhum candidato!</div>
        @endif
    </div>
    @endif
</div>