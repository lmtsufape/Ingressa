<div class="position-relative" x-data>
    <input 
        type="text" 
        class="form-control campoDeTexto" 
        placeholder="Procurar Candidato..." 
        wire:model="texto" 
        wire:keydown.escape="limpar" 
        wire:keydown.tab="limpar" 
        wire:keydown.arrow-up="decrementHighlight" 
        wire:keydown.arrow-down="incrementHighlight" 
        wire:keydown.enter="selecionarCandidato"
        @click.outside="$wire.limpar()"
    />
    @if(!empty($texto))
    <div class="position-fixed list-group">
        @if(!empty($inscricoes))
        @foreach($inscricoes as $i => $inscricao)
        <a href="{{ $links->get($i) }}" class="list-group-item">{{ $inscricao->candidato->no_inscrito }} - {{$inscricao->sisu->edicao}}</a>
        @endforeach
        @else
        <div class="">Nenhum candidato!</div>
        @endif
    </div>
    @endif
</div>