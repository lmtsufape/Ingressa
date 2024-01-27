<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar cota') }}
        </h2>
    </x-slot>

    <div class="container" style="padding-top: 5rem; padding-bottom: 8rem;">
        <div class="form-row justify-content-center">
            <div class="col-md-12">
                <div class="card" style="width: 100%;">
                    <div class="card-body">
                        <div class="form-row">
                            <div class="col-md-12">
                                <h5 class="card-title">Ordem de remanejamento para a cota {{ $cota->cod_cota }} -
                                    {{ $cota->nome }}</h5>
                                <h6 class="card-subtitle mb-2 text-muted">Cotas > Remanejamento de cota</h6>
                                <h6 class="card-subtitle mb-2" style="color: #2A5EDC">*Os cards podem ser reordenados
                                    arrastando com o mouse.</h6>
                            </div>
                        </div>
                        @error('error')
                            <div class="alert alert-danger" role="alert">
                                <span>
                                    <strong>{{ $message }}</strong>
                                </span>
                            </div>
                        @enderror
                        <form id="criar-curso-form" method="POST"
                            action="{{ route('cotas.remanejamento.update', ['cota' => $cota]) }}">
                            @csrf
                            <input type="hidden" name="modo"
                                value="@if ($cota->remanejamentos->count() > 0) edit @else create @endif">
                            <div id="sortable-list">
                                @foreach ($cotas as $i => $prox_cota)
                                    <div class="form-row drag-handle" data-id="{{ $prox_cota->id }}"
                                        style="border: 1px solid rgb(156, 156, 156); border-radius: 5px; margin-top: 10px; margin-bottom: 10px; padding:10px; cursor: grab;">
                                        <div class="col-md-12 form-group drag-handle">
                                            <input id="cota-input-{{ $prox_cota->id }}" type="hidden" name="cotas[]"
                                                value="{{ $cota->remanejamentos->contains('id_prox_cota', $prox_cota->id) ? old('cotas.' . $i, $prox_cota->id) : old('cotas.' . $i) }}">
                                            <input id="cota-{{ $prox_cota->id }}" type="checkbox"
                                                onclick="alocarValue(this, {{ $prox_cota->id }})"
                                                @if (old('cotas.' . $i, $cota->remanejamentos->contains('id_prox_cota', $prox_cota->id))) checked @endif>
                                            <label for="cota-{{ $prox_cota->id }}">{{ $prox_cota->cod_cota }} -
                                                {{ $prox_cota->nome }}</label>
                                        </div>
                                        <br>
                                        <div class="row justify-content-between row align-items-end">
                                            <div class="col-md-2 form-group">
                                                <label
                                                    for="ordem-{{ $prox_cota->id }}">{{ __('Ordem na fila') }}</label>
                                                <input type="text" name="ordem[]" id="ordem-{{ $prox_cota->id }}"
                                                    class="form-control-plaintext @error('ordem.' . $i) is-invalid @enderror"
                                                    value="{{ $cota->remanejamentos->contains('id_prox_cota', $prox_cota->id)? old('ordem.' . $i,$cota->remanejamentos()->where('id_prox_cota', $prox_cota->id)->first()->ordem): old('ordem.' . $i) }}"
                                                    disabled readonly>

                                                @error('ordem.' . $i)
                                                    <div id="validationServer03Feedback" class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                            <div class="col-md-auto">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    fill="currentColor" class="bi bi-arrow-down-up" viewBox="0 0 16 16"
                                                    class="align-self-bottom" style="color: #2A5EDC">
                                                    <path fill-rule="evenodd"
                                                        d="M11.5 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L11 2.707V14.5a.5.5 0 0 0 .5.5m-7-14a.5.5 0 0 1 .5.5v11.793l3.146-3.147a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 .708-.708L4 13.293V1.5a.5.5 0 0 1 .5-.5" />
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </form>
                    </div>
                    <div class="card-footer">
                        <div class="form-row">
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-success" style="width: 100%;"
                                    form="criar-curso-form">Salvar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function alocarValue(checkbox, id) {
            if (checkbox.checked) {
                document.getElementById('cota-input-' + id).value = id;
            } else {
                document.getElementById('cota-input-' + id).value = null;
            }
        }
    </script>
    @push('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var sortableList = new Sortable(document.getElementById('sortable-list'), {
                    animation: 150,
                    handle: '.drag-handle',
                    onEnd: function(evt) {
                        updateOrder();
                        sortListByOrder(sortableList.toArray());
                    },
                });

                // Ordenar automaticamente ao carregar a página
                sortListByOrder(sortableList.toArray());

                // Adicionar evento para capturar mudanças nas caixas de seleção
                var checkboxes = document.querySelectorAll('[type="checkbox"]');
                checkboxes.forEach(function(checkbox) {
                    checkbox.addEventListener('change', function() {
                        sortListByOrder(sortableList.toArray());
                    });
                });

                function updateOrder() {
                    var orderInputs = document.querySelectorAll('[name="ordem[]"]');
                    orderInputs.forEach(function(input, index) {
                        input.value = index + 1;
                    });
                }

                function sortListByOrder(order) {
                    var items = document.getElementById('sortable-list').children;

                    // Separar cards marcados e desmarcados
                    var markedItems = [];
                    var unmarkedItems = [];

                    Array.from(items).forEach(function(item) {
                        var checkbox = item.querySelector('[type="checkbox"]');
                        if (checkbox && checkbox.checked) {
                            markedItems.push(item);
                        } else {
                            unmarkedItems.push(item);
                        }
                    });

                    // Ordenar cards marcados
                    var sortedMarkedItems = markedItems.sort(function(a, b) {
                        var orderA = parseInt(a.querySelector('[name="ordem[]"]').value, 10);
                        var orderB = parseInt(b.querySelector('[name="ordem[]"]').value, 10);
                        return orderA - orderB;
                    });

                    // Atualizar a ordem visual dos cards
                    document.getElementById('sortable-list').innerHTML = '';
                    sortedMarkedItems.forEach(function(item) {
                        document.getElementById('sortable-list').appendChild(item);
                    });

                    // Adicionar cards desmarcados ao final da lista
                    unmarkedItems.forEach(function(item) {
                        document.getElementById('sortable-list').appendChild(item);
                    });

                    // Atualizar a ordem dos inputs
                    updateOrder();

                    // Definir valor "Sem ordem" para checkboxes desmarcadas
                    unmarkedItems.forEach(function(item) {
                        var checkbox = item.querySelector('[type="checkbox"]');
                        var orderInput = item.querySelector('[name="ordem[]"]');
                        if (!checkbox.checked) {
                            orderInput.value = "Sem ordem";
                        }
                    });
                }
            });
        </script>
    @endpush
</x-app-layout>
