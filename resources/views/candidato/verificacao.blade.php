<x-guest-layout>
    @if (session('status'))
        <div class="mb-4 font-medium text-sm text-green-600">
            {{ session('status') }}
        </div>
    @endif
    <div class="fundo px-5 py-5">
        <div class="container">
            <div class="py-3 px-4 row ms-0 justify-content-center">
                <div class="col-md-5 caixa shadow p-3 bg-white">
                    <div class="data bordinha">
                        Primeiro acesso
                    </div>
                    <div class="mt-2 subtexto">
                        Caso seu nome conste em alguma das <a href="{{route('index')}}">listas de chamadas</a>, insira seu CPF e data de nascimento para cadastrar um e-mail e senha.
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <form id="primeiro-acesso-form" class="my-4" method="POST" action="{{route('primeiroAcesso.verificacao')}}">
                                @csrf
                                <div class="form-group textoInput">
                                    <label for="exampleInputEmail1">CPF:</label>
                                    <input id="cpf" class="form-control form-control-sm caixaDeTexto @error('cpf') is-invalid @enderror" type="text" placeholder="Insira seu CPF" name="cpf" value="{{old('cpf')}}" required>

                                    @error('cpf')
                                        <div id="validationServer03Feedback" class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="form-group mt-2 textoInput">
                                    <label for="exampleInputPassword1">Data de nascimento:</label>
                                    <input class="form-control form-control-sm caixaDeTexto" placeholder="Insira sua data de nascimento" type="date" name="dt_nasc" required>
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
                                <button type="submit" class="btn botaoEntrar col-md-10" form="primeiro-acesso-form" style="width: 100%;">Confirmar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>

<script type="text/javascript">
    $(document).ready(function(){
        $('#cpf').mask('000.000.000-00');
    });
</script>

