<x-guest-layout>
    <style>
        .btn-eye {
            display: block;
            padding: 0.375rem 0.75rem;
            font-size: 1rem;
            font-weight: 400;
            line-height: 1.5;
            background-clip: padding-box;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            color: #555;
            cursor: pointer;
            background-color: #f5f5f5;
            border-radius: 0.5rem;
            border: 1px solid transparent;
            transition: transform .08s ease, background-color .15s ease, border-color .15s ease, color .15s ease;
        }

        .btn-eye:hover {
            background: #f3f4f6;
            border-color: #bdbdbd;
            color: #111;
        }

        .btn-eye:active {
            transform: scale(0.96);
        }

        .btn-eye:focus,
        .btn-eye:focus-visible {
            color: #212529;
            background-color: #fff;
            border-color: #86b7fe;
            outline: 0;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }
    </style>
    <div class="fundo px-5 py-5">
        <div class="container">
            <div class="py-3 px-4 row ms-0 justify-content-between">
                <div class="col-md-7">
                    <div class="text-center "style="font-size: 55px;">
                        <img width="250px" src="{{ asset('img/Ingressa.svg') }}">
                    </div>
                    <div class="mt-4 tituloEntrada">
                        1- Envie seus documentos
                    </div>
                    <!--deixar o texto justificado-->
                    <div class="textoEntrada mt-2 text-justify" style="text-align: justify;">
                        <p>Para enviar um documento por esta plataforma, primeiro você deve digitalizá-lo utilizando um
                            scanner, uma câmera digital ou um celular. Em seguida, salvar o arquivo em seu computador ou
                            celular. Finalmente, entrar no link correspondente ao documento em questão (os links estão
                            nomeados de acordo com cada documento a ser enviado), anexar o arquivo escaneado
                            correspondente e clicar em ENVIAR. <a href="{{ route('envio.docs') }}">Continuar
                                lendo...</a></p>
                    </div>
                </div>
                <div class="col-md-3 caixa shadow p-3 bg-white">
                    @if (session('success'))
                        <div class="row">
                            <div class="col-md-12">
                                <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
                                    <symbol id="check-circle-fill" fill="currentColor" viewBox="0 0 16 16">
                                        <path
                                            d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
                                    </symbol>
                                </svg>

                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img"
                                        aria-label="Success:">
                                        <use xlink:href="#check-circle-fill" />
                                    </svg>{{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="data bordinha">
                        Entrar
                    </div>
                    <div class="mt-2 subtexto">
                        Entre com o seu e-mail e senha na conta. Caso não tenha uma senha, realize o primeiro acesso.
                    </div>
                    <div class="row">
                        <form id="login-form" class="my-4" method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="form-group textoInput">
                                <label for="email">E-mail</label>
                                <input
                                    class="form-control form-control-sm caixaDeTexto @error('email') is-invalid @enderror"
                                    id="email" name="email" value="{{ old('email') }}" type="text"
                                    placeholder="E-mail" required>

                                @error('email')
                                    <div id="validationServer03Feedback" class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-group mt-2 textoInput">
                                <label for="password">Senha</label>
                                <div class="input-group">
                                    <input id="password" type="password"
                                        class="form-control form-control-sm caixaDeTexto @error('password') is-invalid @enderror"
                                        name="password" value="{{ old('password') }}" placeholder="Senha" required
                                        autocomplete="password">

                                    <button type="button" class="btn-eye" id="togglePassword" tabindex="-1">
                                        <i class="bi bi-eye" id="eyeIcon"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <div id="validationServer03Feedback" class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="mt-3">
                                <div class="form-check mb-0 pb-0 checkbox">
                                    <input class="form-check-input" type="checkbox" id="remember_me" name="remember"
                                        @if (old('remember') != null) checked @endif>
                                    <label class="form-check-label" for="defaultCheck1">
                                        Lembre-se de mim
                                    </label>
                                </div>
                                <a href="{{ route('password.request') }}" class="link">Esqueceu seu acesso?</a>
                            </div>
                        </form>
                    </div>
                    <div class="row">
                        <div class="col-md-12 text-center" style="margin-bottom: 10px;">
                            <button type="submit" class="btn botaoEntrar col-md-10" form="login-form"
                                style="width: 100%;">Entrar</button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <a href="{{ route('primeiro.acesso') }}" type="button" class="btn botaoEntrar col-md-10"
                                style="width: 100%;">Primeiro acesso</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).on('click', '#togglePassword', function() {
                const $input = $('#password');
                const isPassword = $input.prop('type') === 'password';

                $input.prop('type', isPassword ? 'text' : 'password');

                $("#eyeIcon").toggleClass("bi-eye");
                $("#eyeIcon").toggleClass("bi-eye-slash");
            });
        </script>
    @endpush
</x-guest-layout>
