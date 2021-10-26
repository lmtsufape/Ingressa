<x-app-layout>
    <div class="fundo2 px-5">
        <div class="row justify-content-center">
            <div class="col-md-9 cabecalho p-2 px-3 align-items-center">
                <div class="row justify-content-between">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <span class="tituloTabelas ps-1">Edições
                            </span>
                        </div>
                        <div class="col-md-4" style="text-align: right">
                            <a class="btn btn-primary" href="{{route('sisus.create')}}">Criar nova edição</a>
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
                            <th scope="col">Edição</th>
                            <th scope="col">Opções</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sisus as $i => $sisu)
                            <tr>
                                <th class="align-middle"> {{$i+1}}</th>
                                <td>{{$sisu->edicao}}</td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-light dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                            <img class="filter-green" src="{{asset('img/icon_acoes.svg')}}" style="width: 4px;">
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                            @if(Auth::user()->role == \App\Models\User::ROLE_ENUM['admin'] || Auth::user()->role == \App\Models\User::ROLE_ENUM['analista'])
                                                <a class="dropdown-item" href="{{route('sisus.show', ['sisu' => $sisu->id])}}">Visualizar edição</a>
                                                <a class="dropdown-item" href="{{route('sisus.edit', ['sisu' => $sisu->id])}}">Editar edição</a>
                                                <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#modalStaticDeletarSisu_{{$sisu->id}}" style="color: red; cursor: pointer;">Deletar edição</a>
                                            @endif
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    @foreach ($sisus as $sisu)
        <!-- Modal deletar sisu -->
        <div class="modal fade" id="modalStaticDeletarSisu_{{$sisu->id}}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header" style="background-color: #dc3545;">
                        <h5 class="modal-title" id="staticBackdropLabel" style="color: white;">Confirmação</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="deletar-sisu-form-{{$sisu->id}}" method="POST" action="{{route('sisus.destroy', ['sisu' => $sisu])}}">
                            @csrf
                            <input type="hidden" name="_method" value="DELETE">
                            Tem certeza que deseja deletar a edição {{$sisu->edicao}} do sisu?
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger" form="deletar-sisu-form-{{$sisu->id}}">Sim</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</x-app-layout>
