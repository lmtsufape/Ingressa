<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar cota') }}
        </h2>
    </x-slot>

    <div class="container" style="padding-top: 5rem; padding-bottom: 8rem;">
        <div class="form-row justify-content-center">
            <div class="col-md-10">
                <div class="card" style="width: 100%;">
                    <div class="card-body">
                        <div class="form-row">
                            <div class="col-md-8">
                                <h5 class="card-title">Ordem de remanejamento para a cota {{$cota->nome}}</h5>
                                <h6 class="card-subtitle mb-2 text-muted">Cotas > Remanejamento de cota</h6>
                            </div>
                        </div>
                        @error('error')
                            <div class="alert alert-danger" role="alert">
                                {{$message}}
                            </div>
                        @enderror
                        <form id="criar-curso-form" method="POST" action="{{route('cotas.remanejamento.update', ['cota' => $cota])}}">
                            @csrf
                            <input type="hidden" name="modo" value="@if($cota->remanejamentos->count()>0)edit @else create @endif">
                            @foreach ($cotas as $i => $prox_cota)
                                <div class="form-row" style="border: 1px solid rgb(156, 156, 156); border-radius: 5px; margin-top: 10px; margin-bottom: 10px; padding:10px;">
                                    <div class="col-md-6 form-group">
                                        <input id="cota-input-{{$prox_cota->id}}" type="hidden" name="cotas[]" value="{{$cota->remanejamentos->contains('id_prox_cota', $prox_cota->id) ? old('cotas.'.$i, $prox_cota->id) : old('cotas.'.$i)}}">
                                        <input id="cota-{{$prox_cota->id}}" type="checkbox" onclick="alocarValue(this, {{$prox_cota->id}})" @if(old('cotas.'.$i, $cota->remanejamentos->contains('id_prox_cota', $prox_cota->id))) checked @endif>
                                        <label for="cota-{{$prox_cota->id}}">{{$prox_cota->nome}}</label>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label for="ordem-{{$prox_cota->id}}">{{__('Ordem na fila')}}</label>
                                        <input type="number" name="ordem[]" id="ordem-{{$prox_cota->id}}" class="form-control @error('ordem.'.$i) is-invalid @enderror" value="{{$cota->remanejamentos->contains('id_prox_cota', $prox_cota->id) ? old('ordem.'.$i, $cota->remanejamentos()->where('id_prox_cota', $prox_cota->id)->first()->ordem) : old('ordem.'.$i)}}">

                                        @error('ordem.'.$i)
                                            <div id="validationServer03Feedback" class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            @endforeach
                        </form>
                    </div>
                    <div class="card-footer">
                        <div class="form-row">
                            <div class="col-md-6"></div>
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-success" style="width: 100%;" form="criar-curso-form">Salvar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function alocarValue(checkbox, id){
            if(checkbox.checked) {
                document.getElementById('cota-input-'+id).value = id;
            } else {
                document.getElementById('cota-input-'+id).value = null;
            }
        }
    </script>
</x-app-layout>