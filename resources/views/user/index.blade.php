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
                                    <a onclick="editarAnalista({{$user->id}})" data-bs-toggle="modal" data-bs-target="#editar-user-modal" style="cursor: pointer;"><img class="m-1 " width="30" src="{{asset('img/Grupo 1665.svg')}}"  alt="icone-busca"></a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{$users->links()}}
            </div>
        </div>
    </div>

    <!-- Modal criar user analista -->

    <div class="modal fade" id="criar-user" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #53F2C7;">
                    <h5 class="modal-title" id="staticBackdropLabel" style="color: white;">Criar analista</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" id="criar-analista" action="{{route('usuarios.store')}}">
                        @csrf
                        <div class="form-row">
                            <div class="col-md-12 mb-3">
                                <label for="name">{{ __('Nome') }}</label>
                                <input id="name" class="form-control apenas_letras @error('name') is-invalid @enderror" type="text" name="name" value="{{old('name')}}" required autofocus autocomplete="name">

                                @error('name')
                                    <div id="validationServer03Feedback" class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-12 mb-3">
                                <label for="email">{{ __('Email') }}</label>
                                <input id="email" class="form-control @error('email') is-invalid @enderror" type="email" name="email" value="{{old('email')}}" required autofocus autocomplete="email">

                                @error('email')
                                    <div id="validationServer03Feedback" class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-12 mb-3">
                                <label for="password">{{ __('Senha') }}</label>
                                <input id="password" class="form-control @error('password') is-invalid @enderror" type="password" name="password" required autofocus autocomplete="new-password">

                                @error('password')
                                    <div id="validationServer03Feedback" class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-12 mb-3">
                                <label for="password_confirmation">{{ __('Confirme a senha') }}</label>
                                <input id="password_confirmation" class="form-control" type="password" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success btn-cota" form="criar-analista">Criar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editar-user-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #53F2C7;">
                    <h5 class="modal-title" id="staticBackdropLabel" style="color: white;">Editar analista</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" id="editar-analista" action="{{route('usuarios.update.analista', ['id' => $user])}}">
                        @csrf
                        <input type="hidden" id="user-edit" name="user_id" value="">
                        <div class="form-row">
                            <div class="col-md-12 mb-3">
                                <label for="name">{{ __('Nome') }}</label>
                                <input id="name-edit" class="form-control apenas_letras @error('name') is-invalid @enderror" type="text" name="name" value="{{old('name')}}" required autofocus>

                                @error('name')
                                    <div id="validationServer03Feedback" class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-12 mb-3">
                                <label for="email">{{ __('Email') }}</label>
                                <input id="email-edit" class="form-control @error('email') is-invalid @enderror" type="email" name="email"  value="{{old('email')}}" required autofocus>

                                @error('email')
                                    <div id="validationServer03Feedback" class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success btn-cota" form="editar-analista">Editar</button>
                </div>
            </div>
        </div>
    </div>

    @foreach ($users as $user)
        <!-- Modal deletar user -->
        <div class="modal fade" id="modalStaticDeletarUser_{{$user->id}}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header" style="background-color: #dc3545;">
                        <h5 class="modal-title" id="staticBackdropLabel" style="color: white;">Confirmação</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="deletar-user-form-{{$user->id}}" method="POST" action="{{route('usuarios.destroy', ['usuario' => $user])}}">
                            @csrf
                            <input type="hidden" name="_method" value="DELETE">
                            Tem certeza que deseja deletar o analista {{$user->name}}?
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger" form="deletar-user-form-{{$user->id}}">Sim</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</x-app-layout>

<script>
    function editarAnalista(id) {

        $.ajax({
            url:"{{route('usuario.info.ajax')}}",
            type:"get",
            data: {"user_id": id},
            dataType:'json',
            success: function(user) {
                document.getElementById('user-edit').value = user.id;
                document.getElementById('name-edit').value = user.name;
                document.getElementById('email-edit').value = user.email;
            }
        });
    }
</script>
