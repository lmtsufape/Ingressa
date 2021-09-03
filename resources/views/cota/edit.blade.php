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
                                <h5 class="card-title">Editar cota</h5>
                                <h6 class="card-subtitle mb-2 text-muted">Cotas > Editar cota</h6>
                            </div>
                        </div>
                        <form id="criar-curso-form" method="POST" action="{{route('cotas.update', ['cota' => $cota])}}">
                            @csrf
                            <input type="hidden" name="_method" value="PUT">
                            <div class="form-row">
                                <div class="col-md-6 form-group">
                                    <label for="nome">{{__('Name')}}</label>
                                    <input type="text" id="nome" name="nome" class="form-control @error('nome') is-invalid @enderror" value="{{old('nome', $cota->nome)}}" autofocus required>
                                
                                    @error('nome')
                                        <div id="validationServer03Feedback" class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="codigo">{{__('Código da cota')}}</label>
                                    <input type="text" id="codigo" name="codigo" class="form-control @error('codigo') is-invalid @enderror" value="{{old('codigo', $cota->cod_cota)}}" required>
                                
                                    @error('codigo')
                                        <div id="validationServer03Feedback" class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-md-12 form-group">
                                    <label for="descrição">{{__('Descrição')}}</label>
                                    <textarea name="descrição" id="descrição" cols="30" rows="3" class="form-control @error('descrição') is-invalid @enderror" required>{{old('descrição', $cota->descricao)}}</textarea>
                                
                                    @error('descrição')
                                        <div id="validationServer03Feedback" class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            @foreach ($cursos as $i => $curso)
                                <div class="form-row" style="border: 1px solid rgb(156, 156, 156); border-radius: 5px; margin-top: 10px; margin-bottom: 10px; padding:10px;">
                                    <div class="col-md-6 form-group">
                                        <input id="curso-input-{{$curso->id}}" type="hidden" name="cursos[]" value="{{old('cursos.'.$i, $cota->cursos->contains('id', $curso->id) ? $curso->id : null)}}">
                                        <input id="curso-{{$curso->id}}" type="checkbox" onclick="alocarValue(this, {{$curso->id}})" @if(old('cursos.'.$i, $cota->cursos->contains('id', $curso->id) ? $curso->id : null) != null) checked @endif>
                                        <label for="curso-{{$curso->id}}">{{$curso->nome}}</label>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label for="percentual-{{$curso->id}}">{{__('Percentual da cota')}}</label>
                                        <input type="number" name="percentual[]" id="percentual-{{$curso->id}}" class="form-control @error('percentual.'.$i) is-invalid @enderror" value="{{old('percentual.'.$i, $cota->cursos->contains('id', $curso->id) ?  $cota->cursos()->where('curso_id', $curso->id)->first()->pivot->percentual_cota : null)}}">

                                        @error('percentual.'.$i)
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
                document.getElementById('curso-input-'+id).value = id;
            } else {
                document.getElementById('curso-input-'+id).value = null;
            }
        }
    </script>
</x-app-layout>