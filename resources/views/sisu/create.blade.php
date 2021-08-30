<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Criar nova edição do sisu') }}
        </h2>
    </x-slot>
    <div class="container" style="padding-top: 5rem; padding-bottom: 8rem;">
        <div class="form-row justify-content-center">
            <div class="col-md-10">
                <div class="card" style="width: 100%;">
                    <div class="card-body">
                        <div class="form-row">
                            <div class="col-md-8">
                                <h5 class="card-title">Cadastrar uma nova edição</h5>
                                <h6 class="card-subtitle mb-2 text-muted">Sisu > Criar edição</h6>
                            </div>
                        </div>
                        <form method="POST" id="criar-sisu" action="{{route('sisus.store')}}">
                            @csrf
                            <div class="form-row">
                                <div class="col-md-6 form-group">
                                    <label for="edicao">{{ __('Edição') }}</label>
                                    <input id="edicao" class="form-control @error('edicao') is-invalid @enderror" type="text" name="edicao" required autofocus autocomplete="edicao">

                                    @error('edicao')
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
                            <div class="col-md-6" style="text-align: right">
                                <button type="submit" class="btn btn-success" form="criar-sisu" style="width: 100%">Salvar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
