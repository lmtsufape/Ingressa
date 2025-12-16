<x-guest-layout>
    <div class="fundo px-5 py-5">
        <div class="container">
            <div class="py-3 px-4 row ms-0 justify-content-center">
                <div class="col-md-5 caixa shadow p-3 bg-white">
                    <div class="data bordinha">
                        Recuperar senha
                    </div>
                    @if (session('status'))
                        <div class="mb-2 mt-1 font-medium text-sm text-green-600">
                            {{ session('status') }}
                        </div>
                    @endif
                    <div class="mt-2 subtexto">
                        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <form id="reset-form" class="my-4" method="POST" action="{{route('password.email')}}">
                                @csrf

                                <div class="form-group textoInput">
                                    <label for="email">{{ __('E-mail') }}</label>
                                    <input id="email" class="form-control form-control-sm caixaDeTexto @error('email') is-invalid @enderror" type="text" placeholder="Insira o e-mail utilizado no login" name="email" value="{{old('email')}}" required>

                                    @error('email')
                                        <div id="validationServer03Feedback" class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6" style="margin-bottom: 10px;">
                            <div class="text-center">
                                <a href="{{route('login')}}" type="button" class="btn botaoEntrar col-md-10" style="width: 100%;">Voltar</a>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-center">
                                <button type="submit" class="btn botaoEntrar col-md-10" form="reset-form" style="width: 100%;">Enviar link</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
