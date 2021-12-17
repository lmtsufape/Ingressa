<x-app-layout>
    <div class="fundo2 px-5">
        <div class="row justify-content-center">
            <div class="col-md-9 cabecalho p-2 px-3 align-items-center">
                <div class="row justify-content-between">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <img src="{{asset('img/Grupo 1662.svg')}}"
                            alt="" width="40" class="img-flex">
                            <span class="tituloTabelas ps-1">Analistas</span>
                        </div>
                        <div class="col-md-4" style="text-align: right">
                            <a id="criar-user-btn" data-bs-toggle="modal" data-bs-target="#criar-user" style="cursor: pointer;"><img width="35" src="{{asset('img/Grupo 1663.svg')}}"></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-9 corpo p-2 px-3">
                @if(session('success'))
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
                                <symbol id="check-circle-fill" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                                </symbol>
                            </svg>

                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Success:"><use xlink:href="#check-circle-fill"/></svg>{{session('success')}}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        </div>
                    </div>
                @endif
                @if($users->count() > 0)
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Nome</th>
                                <th scope="col">E-mail</th>
                                <th class="text-center">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $i => $user)
                                <tr>
                                    <th class="align-middle"> {{$i+1}}</th>
                                    <td class="align-middle">{{$user->name}}</td>
                                    <td class="align-middle">{{$user->email}}</td>
                                    <td class="align-middle text-center">
                                        <a data-bs-toggle="modal" data-bs-target="#modalStaticDeletarUser_{{$user->id}}" style="cursor: pointer;"><img class="m-1 " width="30" src="{{asset('img/Grupo 1664.svg')}}"  alt="icone-busca"></a>
                                        <a onclick="editarAnalista({{$user->id}}, {{$tipos}})" data-bs-toggle="modal" data-bs-target="#editar-user-modal" style="cursor: pointer;"><img class="m-1 " width="30" src="{{asset('img/Grupo 1665.svg')}}"  alt="icone-busca"></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{$users->links()}}
                @else
                    <div class="pt-3 pb-3">
                        Nenhum analista cadastrado no sistema.
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal criar user analista -->

    <div class="modal fade" id="criar-user" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content modalFundo p-3">
                <div class="col-md-12 tituloModal">Insira um novo analista</div>
                <form method="POST" id="criar-analista" action="{{route('usuarios.store')}}">
                    @csrf
                    <input type="hidden" id="user" name="user_id" value="none">
                    <div class="col-md-12 pt-3 textoModal">
                        <label class="pb-2" for="name">Nome completo:</label>
                        <input id="name" class="form-control apenas_letras campoDeTexto @error('name') is-invalid @enderror" type="text" name="name" value="{{old('name')}}" required autofocus autocomplete="name" placeholder="Insira o nome completo do analista">

                        @error('name')
                            <div id="validationServer03Feedback" class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="col-md-12 pt-3 textoModal">
                        <label class="pb-2 pt-2" for="email">E-mail:</label>
                        <input id="email" class="form-control campoDeTexto @error('email') is-invalid @enderror" type="email" name="email" value="{{old('email')}}" required autofocus autocomplete="email" placeholder="Insira o e-mail de acesso do analista">

                        @error('email')
                            <div id="validationServer03Feedback" class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="col-md-12 pt-3 textoModal">
                        <div class="row justify-content-between">
                            <div class="col-md-6">
                                <label class="pb-2 pt-2" for="password">Senha:</label>
                                <input id="password" class="form-control campoDeTexto @error('password') is-invalid @enderror" type="password" name="password" required autofocus autocomplete="new-password">

                                @error('password')
                                    <div id="validationServer03Feedback" class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="pb-2 pt-2" for="password_confirmation">Confirme a senha:</label>
                                <input id="password_confirmation" class="form-control campoDeTexto" type="password" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 pt-3 textoModal">
                        <label class="pb-2 pt-2" for="tipo">{{__('Selecione o(s) cargo(s) do analista:')}}</label>
                        <input type="hidden" class="checkbox_tipo @error('tipos_analista') is-invalid @enderror">
                        @foreach ($tipos as $tipo)
                            <div class="form-check">
                                <input class="checkbox_tipo form-check-input form-check-cursos" type="checkbox" name="tipos_analista[]" value="{{$tipo->id}}" id="tipo_{{$tipo->id}}">
                                <label class="form-check-label" for="tipo_{{$tipo->id}}">
                                    @if($tipo->tipo == \App\Models\TipoAnalista::TIPO_ENUM['geral'])
                                        Geral
                                    @elseif($tipo->tipo == \App\Models\TipoAnalista::TIPO_ENUM['heteroidentificacao'])
                                        Heteroidentificação
                                    @elseif($tipo->tipo == \App\Models\TipoAnalista::TIPO_ENUM['medico'])
                                        Seção médica
                                    @endif
                                </label>
                            </div>
                        @endforeach
                        @error('tipos_analista')
                            <div id="validationServer03Feedback" class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </form>

                <div class="row justify-content-between mt-4">
                    <div class="col-md-3">
                        <button type="button" class="btn botao my-2 py-1" data-bs-dismiss="modal"> <span class="px-4" style="font-weight: bolder;">Voltar</span></button>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn botaoVerde my-2 py-1" form="criar-analista"><span class="px-4" style="font-weight: bolder;">Salvar</span></button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editar-user-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content modalFundo p-3">
                <div class="col-md-12 tituloModal">Editar analista</div>

                <form method="POST" id="editar-analista" action="{{route('usuarios.update.analista')}}">
                    @csrf
                    <input type="hidden" id="user-edit" name="user_id" value="">
                    <div class="form-row">
                        <div class="col-md-12 pt-3 textoModal">
                            <label class="pb-2" for="name-edit">Nome completo:</label>
                            <input id="name-edit" class="form-control apenas_letras campoDeTexto @error('name') is-invalid @enderror" type="text" name="name" value="{{old('name')}}" required autofocus autocomplete="name" placeholder="Insira o nome completo do analista">

                            @error('name')
                                <div id="validationServer03Feedback" class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-12 pt-3 textoModal">
                            <label class="pb-2 pt-2" for="email-edit">E-mail:</label>
                            <input id="email-edit" class="form-control campoDeTexto @error('email') is-invalid @enderror" type="email" name="email" value="{{old('email')}}" required autofocus autocomplete="email" placeholder="Insira o e-mail de acesso do analista">

                            @error('email')
                                <div id="validationServer03Feedback" class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-md-12 pt-3 textoModal">
                            <label class="pb-2 pt-2" for="tipo">{{__('Selecione o(s) cargo(s) do analista:')}}</label>
                            <input type="hidden" class="checkbox_tipo @error('tipos_analista_edit') is-invalid @enderror">
                            @foreach ($tipos as $tipo)
                                <div class="form-check">
                                    <input class="form-check-input form-check-cursos" type="checkbox" name="tipos_analista_edit[]" value="{{$tipo->id}}" id="tipo_edit_{{$tipo->id}}">
                                    <label class="form-check-label" for="tipo_edit_{{$tipo->id}}">
                                        @if($tipo->tipo == \App\Models\TipoAnalista::TIPO_ENUM['geral'])
                                            Geral
                                        @elseif($tipo->tipo == \App\Models\TipoAnalista::TIPO_ENUM['heteroidentificacao'])
                                            Heteroidentificação
                                        @elseif($tipo->tipo == \App\Models\TipoAnalista::TIPO_ENUM['medico'])
                                            Seção médica
                                        @endif
                                    </label>
                                </div>
                            @endforeach
                            @error('tipos_analista_edit')
                                <div id="validationServer03Feedback" class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </form>
                <div class="row justify-content-between mt-4">
                    <div class="col-md-3">
                        <button type="button" class="btn botao my-2 py-1" data-bs-dismiss="modal"> <span class="px-4" style="font-weight: bolder;">Voltar</span></button>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn botaoVerde my-2 py-1" form="editar-analista"><span class="px-4" style="font-weight: bolder;" >Salvar</span></button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @foreach ($users as $user)
        <!-- Modal deletar user -->
        <div class="modal fade" id="modalStaticDeletarUser_{{$user->id}}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content modalFundo p-3">
                    <div class="col-md-12 tituloModal">Excluir analista</div>

                    <form id="deletar-user-form-{{$user->id}}" method="POST" action="{{route('usuarios.destroy', ['usuario' => $user])}}">
                        @csrf
                        <div class="pt-3">
                            <input type="hidden" name="_method" value="DELETE">
                            Tem certeza que deseja deletar o analista {{$user->name}}?
                        </div>
                    </form>
                    <div class="row justify-content-between mt-4">
                        <div class="col-md-3">
                            <button type="button" class="btn botao my-2 py-1" data-bs-dismiss="modal"> <span class="px-4" style="font-weight: bolder;">Voltar</span></button>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn botaoVerde my-2 py-1" form="deletar-user-form-{{$user->id}}" style="background-color: #FC605F;"><span class="px-4" style="font-weight: bolder;" >Excluir</span></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</x-app-layout>

@if(old('user_id') == "none")
    <script>
        $(document).ready(function(){
            $('#criar-user').modal('show');
        });
    </script>
@endif

@if(old('user_id') != "none" && old('user_id') != null)
    <script>
        $(document).ready(function(){
            $('#editar-user-modal').modal('show');
        });
    </script>
@endif

<script>
    function editarAnalista(id, tipos) {
        for(var i = 0; i < tipos.length; i++){
            $('#tipo_edit_'+tipos[i].id).attr('checked', false);
        }
        $.ajax({
            url:"{{route('usuario.info.ajax')}}",
            type:"get",
            data: {"user_id": id},
            dataType:'json',
            success: function(user) {
                document.getElementById('user-edit').value = user.id;
                document.getElementById('name-edit').value = user.name;
                document.getElementById('email-edit').value = user.email;
                for(var i = 0; i < user.cargos.length; i++){
                    $('#tipo_edit_'+user.cargos[i].id).attr('checked', true);
                }
            }
        });
    }
</script>
