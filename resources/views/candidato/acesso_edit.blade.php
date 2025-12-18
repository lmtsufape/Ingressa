<x-guest-layout>

    <div class="fundo px-5 py-5">
        <div class="container">
            <div class="py-3 px-4 row ms-0 justify-content-center">
                <div class="col-md-5 caixa shadow p-3 bg-white">
                    <div class="data bordinha">
                        Primeiro acesso
                    </div>
                    <div class="mt-2 subtexto">
                        Insira um e-mail e senha que será usado para você realizar o acesso ao sistema.
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <form id="primeiro-acesso-form" class="my-4" method="POST"
                                action="{{ route('primeiroAcesso.atualizar') }}">
                                @csrf

                                @if ($user->id == null)
                                    <x-text-input type="hidden" name="id" :value="old('id')" />
                                    <x-text-input type="hidden" name="role" :value="old('role')" />
                                    <x-text-input type="hidden" name="name" :value="old('name')" />
                                @else
                                    <x-text-input type="hidden" name="id" value="{{ $user->id }}" />
                                    <x-text-input type="hidden" name="role" value="{{ $user->role }}" />
                                    <x-text-input type="hidden" name="name" value="{{ $user->name }}" />
                                @endif

                                <div class="form-group textoInput">
                                    <label for="exampleInputEmail1">{{ __('E-mail') }}</label>
                                    <input id="email"
                                        class="form-control form-control-sm caixaDeTexto @error('email') is-invalid @enderror"
                                        type="text" placeholder="Insira um e-mail para ser usado no login"
                                        name="email" value="{{ old('email') }}" required>

                                    @error('email')
                                        <div id="validationServer03Feedback" class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="form-group mt-2 textoInput">
                                    <label for="exampleInputEmail1">{{ __('Password') }}</label>
                                    <input id="password"
                                        class="form-control form-control-sm caixaDeTexto @error('password') is-invalid @enderror"
                                        type="password" placeholder="Insira uma senha para ser usada no login"
                                        name="password" value="" required>

                                    @error('password')
                                        <div id="validationServer03Feedback" class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="form-group mt-2 textoInput">
                                    <label for="exampleInputPassword1">{{ __('Confirm Password') }}</label>
                                    <input class="form-control form-control-sm caixaDeTexto"
                                        placeholder="Insira a senha novamente" type="password"
                                        name="password_confirmation" required>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6" style="margin-bottom: 10px;">
                            <div class="text-center">
                                <a href="{{ route('index') }}" type="button" class="btn botaoEntrar col-md-10"
                                    style="width: 100%;">Voltar</a>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-center">
                                <button type="submit" class="btn botaoEntrar col-md-10 submeterFormBotao"
                                    form="primeiro-acesso-form" style="width: 100%;">Confirmar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-guest-layout>
<script>
    document.getElementById("email").addEventListener("input", function() {
        this.value = this.value.toLowerCase();
    });
</script>
