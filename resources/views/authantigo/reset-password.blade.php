<x-guest-layout>
    <div class="fundo px-5 py-5">
        <div class="container">
            <div class="py-3 px-4 row ms-0 justify-content-center">
                <div class="col-md-5 caixa shadow p-3 bg-white">
                    <div class="data bordinha">
                        Atualizar a senha
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <form id="update-form" class="my-4" method="POST" action="{{route('password.update')}}">
                                @csrf
                                <input type="hidden" name="token" value="{{ $request->route('token') }}">
                                <div class="form-group textoInput">
                                    <label for="email">{{ __('E-mail') }}</label>
                                    <input id="email" class="form-control form-control-sm caixaDeTexto @error('email') is-invalid @enderror" type="text" placeholder="Insira o e-mail utilizado no login" name="email" value="{{old('email', $request->email)}}" required>

                                    @error('email')
                                        <div id="validationServer03Feedback" class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="form-group textoInput">
                                    <label for="password">{{ __('Senha') }}</label>
                                    <input id="password" class="form-control form-control-sm caixaDeTexto @error('password') is-invalid @enderror" type="password" placeholder="Insira a nova senha" name="password" required>
                                    @error('password')
                                        <div id="validationServer03Feedback" class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="form-group textoInput">
                                    <label for="password_confirmation">{{ __('Confirmação de senha') }}</label>
                                    <input id="password_confirmation" class="form-control form-control-sm caixaDeTexto @error('password_confirmation') is-invalid @enderror" type="password" placeholder="Repita a senha" name="password_confirmation" required>

                                    @error('password_confirmation')
                                        <div id="validationServer03Feedback" class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="flex justify-end">
                        <div class="col-md-6">
                            <div class="text-center">
                                <button type="submit" class="btn botaoEntrar col-md-10" form="update-form" style="width: 100%;">Modificar senha</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
