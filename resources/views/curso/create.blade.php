<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Criar curso') }}
        </h2>
    </x-slot>

    <div class="container" style="padding-top: 5rem; padding-bottom: 8rem;">
        <div class="form-row justify-content-center">
            <div class="col-md-10">
                <div class="card" style="width: 100%;">
                    <div class="card-body">
                        <div class="form-row">
                            <div class="col-md-8">
                                <h5 class="card-title">Criar um novo curso</h5>
                                <h6 class="card-subtitle mb-2 text-muted">Cursos > Criar curso</h6>
                            </div>
                        </div>
                        <form id="criar-curso-form" method="POST" action="{{route('cursos.store')}}">
                            @csrf
                            <div class="form-row">
                                <div class="col-md-6">
                                    <label for="nome">{{__('Name')}}</label>
                                    <input type="text" id="nome" name="nome" class="form-control @error('nome') is-invalid @enderror" value="{{old('nome')}}" autofocus required>
                                
                                    @error('nome')
                                        <div id="validationServer03Feedback" class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="codigo">{{__('Código do curso')}}</label>
                                    <input type="text" id="codigo" name="codigo" class="form-control @error('codigo') is-invalid @enderror" value="{{old('codigo')}}" required>
                                
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
                                        <option @if(old('turno') == $turnos['Matutino']) selected @endif value="{{$turnos['Matutino']}}">Matutino</option>
                                        <option @if(old('turno') == $turnos['Vespertino']) selected @endif value="{{$turnos['Vespertino']}}">Vespertino</option>
                                        <option @if(old('turno') == $turnos['Noturno']) selected @endif value="{{$turnos['Noturno']}}">Noturno</option>
                                        <option @if(old('turno') == $turnos['Integral']) selected @endif value="{{$turnos['Integral']}}">Integral</option>
                                    </select>
                                
                                    @error('turno')
                                        <div id="validationServer03Feedback" class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="vagas">{{__('Quantidade de vagas')}}</label>
                                    <input type="number" id="vagas" name="quantidade_de_vagas" class="form-control @error('quantidade_de_vagas') is-invalid @enderror" value="{{old('quantidade_de_vagas')}}" required>
                                
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
                                <button type="submit" class="btn btn-success" style="width: 100%;" form="criar-curso-form">Criar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>