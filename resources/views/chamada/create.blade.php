<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Criar nova chamada do sisu') }}
        </h2>
    </x-slot>
    <div class="container" style="padding-top: 5rem; padding-bottom: 8rem;">
        <div class="form-row justify-content-center">
            <div class="col-md-10">
                <div class="card" style="width: 100%;">
                    <div class="card-body">
                        <div class="form-row">
                            <div class="col-md-8">
                                <h5 class="card-title">Cadastrar uma nova chamada</h5>
                                <h6 class="card-subtitle mb-2 text-muted">Sisu > Cadastrar chamada</h6>
                            </div>
                        </div>
                        <form method="POST" id="criar-chamada" action="{{route('chamadas.store')}}">
                            @csrf
                            <input type="hidden" name="sisu" value="{{$sisu->id}}">
                            <div class="form-row">
                                <div class="col-md-6 form-group">
                                    <label for="nome">{{ __('Nome') }} <span style="color: red; font-weight: bold;">*</span></label>
                                    <input id="nome" class="form-control @error('nome') is-invalid @enderror" type="text" name="nome" required autofocus autocomplete="nome">

                                    @error('nome')
                                        <div id="validationServer03Feedback" class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="descricao">{{ __('Descrição') }} <span style="color: red; font-weight: bold;">*</span></label>
                                    <textarea id="descricao" class="form-control @error('descricao') is-invalid @enderror" type="text" name="descricao" required autofocus autocomplete="descricao"></textarea>

                                    @error('descricao')
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
                                    <input type="radio" id="regular_sim" name="regular" value="true" {{$tem_regular == null ? '' : 'disabled' }}>

                                    <label for="regular_nao">{{ __('Não') }}</label>
                                    <input type="radio" id="regular_nao" name="regular" value="false" {{$tem_regular == null ? '' : 'checked' }}>
                                </div>
                            </div>

                        </form>
                    </div>
                    <div class="card-footer">
                        <div class="form-row">
                            <div class="col-md-6"></div>
                            <div class="col-md-6" style="text-align: right">
                                <button type="submit" class="btn btn-success" form="criar-chamada" style="width: 100%">Salvar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
