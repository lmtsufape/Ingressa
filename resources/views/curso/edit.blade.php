<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar curso') }}
        </h2>
    </x-slot>

    <div class="container" style="padding-top: 5rem; padding-bottom: 8rem;">
        <div class="form-row justify-content-center">
            <div class="col-md-10">
                <div class="card" style="width: 100%;">
                    <div class="card-body">
                        <div class="form-row">
                            <div class="col-md-8">
                                <h5 class="card-title">Editar curso</h5>
                                <h6 class="card-subtitle mb-2 text-muted">Cursos > Editar curso</h6>
                            </div>
                        </div>
                        <form id="editar-curso-form" method="POST" action="{{route('cursos.update', ['curso' => $curso])}}">
                            @csrf
                            <input type="hidden" name="_method" value="PUT">
                            <div class="form-row">
                                <div class="col-md-6">
                                    <label for="nome">{{__('Name')}}</label>
                                    <input type="text" id="nome" name="nome" class="form-control @error('nome') is-invalid @enderror" value="{{old('nome', $curso->nome)}}" autofocus required>
                                
                                    @error('nome')
                                        <div id="validationServer03Feedback" class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="codigo">{{__('Código do curso')}}</label>
                                    <input type="text" id="codigo" name="codigo" class="form-control @error('codigo') is-invalid @enderror" value="{{old('codigo', $curso->cod_curso)}}" required>
                                
                                    @error('codigo')
                                        <div id="validationServer03Feedback" class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-md-6">
                                    <label for="turno">{{__('Turno')}}</label>
                                    <select name="turno" id="turno" class="form-control @error('turno') is-invalid @enderror" required>
                                        <option value="" selected disabled>-- Selecione o turno do curso --</option>
                                        @if (old('turno') != null)
                                            <option @if(old('turno') == $turnos['matutino']) selected @endif value="{{$turnos['matutino']}}">Matutino</option>
                                            <option @if(old('turno') == $turnos['vespertino']) selected @endif value="{{$turnos['vespertino']}}">Vespertino</option>
                                            <option @if(old('turno') == $turnos['noturno']) selected @endif value="{{$turnos['noturno']}}">Noturno</option>
                                            <option @if(old('turno') == $turnos['integral']) selected @endif value="{{$turnos['integral']}}">Integral</option>
                                        @else 
                                            <option @if($curso->turno == $turnos['matutino']) selected @endif value="{{$turnos['matutino']}}">Matutino</option>
                                            <option @if($curso->turno == $turnos['vespertino']) selected @endif value="{{$turnos['vespertino']}}">Vespertino</option>
                                            <option @if($curso->turno == $turnos['noturno']) selected @endif value="{{$turnos['noturno']}}">Noturno</option>
                                            <option @if($curso->turno == $turnos['integral']) selected @endif value="{{$turnos['integral']}}">Integral</option>
                                        @endif
                                    </select>
                                
                                    @error('turno')
                                        <div id="validationServer03Feedback" class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="vagas">{{__('Quantidade de vagas')}}</label>
                                    <input type="number" id="vagas" name="quantidade_de_vagas" class="form-control @error('quantidade_de_vagas') is-invalid @enderror" value="{{old('quantidade_de_vagas', $curso->vagas)}}" required>
                                
                                    @error('quantidade_de_vagas')
                                        <div id="validationServer03Feedback" class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer">
                        <div class="form-row">
                            <div class="col-md-6"></div>
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-success" style="width: 100%;" form="editar-curso-form">Salvar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>