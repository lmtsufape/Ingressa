<div x-data="{}" @click.outside="if ($wire.texto != '') $wire.limpar()">
    <input type="text" class="form-control campoDeTexto" placeholder="Procurar Candidato..."
        wire:model.live.debounce.300ms="texto" wire:keydown.escape="limpar" wire:keydown.tab="limpar"
        wire:keydown.enter="selecionarCandidato"/>
    @if ($texto)
        <div class="dropdown-menu d-block overflow-auto" style="max-height: 75vh;">
            @forelse ($inscricoes ?? [] as $i => $inscricao)
                <a href="{{ $links->get($i) }}" class="dropdown-item">{{ $inscricao->candidato->no_inscrito }} -
                    {{ $inscricao->sisu->edicao }} - {{ $inscricao->candidato->nu_cpf_inscrito }}</a>
            @empty
                <div class="dropdown-item text-muted">Nenhum candidato!</div>
            @endforelse

        </div>
    @endif
</div>
