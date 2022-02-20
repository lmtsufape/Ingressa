<label for="doc-{{$documento}}"
    title="Enviar documento"
    style="cursor: pointer;">
    <input wire:model="arquivos.{{$documento}}"
        id="doc-{{$documento}}"
        type="file"
        class="d-none">
    <img src="{{ asset('img/upload2.svg') }}" width="30">
</label>
