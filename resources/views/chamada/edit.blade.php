<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar chamada') }}
        </h2>
    </x-slot>
    <div class="container" style="padding-top: 5rem; padding-bottom: 8rem;">
        <div class="form-row justify-content-center">
            <div class="col-md-10">
                <div class="card" style="width: 100%;">
                    <div class="card-body">
                        <div class="form-row">
                            <div class="col-md-8">
                                <h5 class="card-title">Editar o cnae {{$chamada->nome}}</h5>
                                <h6 class="card-subtitle mb-2 text-muted">Sisu > Chamada > Editar chamada</h6>
                            </div>
                        </div>
                        <div div class="form-row">
                            <div class="col-sm-12">
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <form method="POST" id="editar-chamada" action="{{route('chamadas.update', $chamada->id)}}">
                            @csrf
                            <input type="hidden" name="_method" value="PUT">
                            <div class="form-row">
                                <div class="col-md-6 form-group">
                                    <label for="nome">{{ __('Nome') }} <span style="color: red; font-weight: bold;">*</span></label>
                                    <input id="nome" class="form-control @error('nome') is-invalid @enderror" type="text" name="nome" value="{{old('nome')!=null ? old('nome') : $chamada->nome}}" required autofocus autocomplete="nome">

                                    @error('nome')
                                        <div id="validationServer03Feedback" class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="descricao">{{ __('Descrição') }} <span style="color: red; font-weight: bold;">*</span></label>
                                    <textarea id="descricao" class="form-control @error('descricao') is-invalid @enderror" type="text" name="descricao" required autofocus autocomplete="descricao">@if(old('descricao')!=null){{old('descricao')}}@else{{($chamada->descricao)}}@endif</textarea>

                                    @error('descricao')
                                        <div id="validationServer03Feedback" class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-sm-6 form-group">
                                    <label for="data_inicio">{{ __('Data de início') }} <span style="color: red; font-weight: bold;">*</span></label>
                                    <input type="date" @error('data_inicio') is-invalid @enderror id="data_inicio" name="data_inicio" value="{{old('data_inicio')!=null ? old('data_inicio') : $chamada->data_inicio}}" required autofocus autocomplete="data_inicio">

                                    @error('data_inicio')
                                        <div id="validationServer03Feedback" class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-sm-6 form-group">
                                    <label for="data_fim">{{ __('Data final') }} <span style="color: red; font-weight: bold;">*</span></label>
                                    <input type="date" @error('data_fim') is-invalid @enderror id="data_fim" name="data_fim" value="{{old('data_fim')!=null ? old('data_fim') : $chamada->data_fim}}" required autofocus autocomplete="data_fim">

                                    @error('data_fim')
                                        <div id="validationServer03Feedback" class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-sm-6 form-group">
                                    <p>Por favor, selecione se a chamada é regular: <span style="color: red; font-weight: bold;">*</span></p>
                                    <label for="regular_sim">{{ __('Sim') }}</label>
                                    <input type="radio" id="regular_sim" name="regular" value="true" {{($tem_regular && !($chamada->regular)) ? 'disabled' : '' }} @if(!old('regular') || ($chamada->regular)) checked @endif>

                                    <label for="regular_nao">{{ __('Não') }}</label>
                                    <input type="radio" id="regular_nao" name="regular" value="false" @if(old('regular') || !($chamada->regular)) checked @endif>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer">
                        <div class="form-row">
                            <div class="col-md-6"></div>
                            <div class="col-md-6" style="text-align: right">
                                <button type="submit" class="btn btn-success" form="editar-chamada" style="width: 100%">Salvar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
