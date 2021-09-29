<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edições') }}
        </h2>
    </x-slot>

    <div class="container" style="padding-top: 5rem; padding-bottom: 8rem;">
        <div class="form-row justify-content-center">
            <div class="col-md-10">
                <div class="card" style="width: 100%;">
                    <div class="card-body">
                        <div class="form-row">
                            <div class="col-md-8">
                                <h5 class="card-title">Edições do sisu cadastradas no sistema</h5>
                                <h6 class="card-subtitle mb-2 text-muted">Edições</h6>
                            </div>
                            <div class="col-md-4" style="text-align: right">
                                <a class="btn btn-primary" href="{{route('sisus.create')}}">Criar nova edição</a>
                            </div>
                        </div>
                        <div div class="form-row">
                            @if(session('success'))
                                <div class="col-md-12" style="margin-top: 5px;">
                                    <div class="alert alert-success" role="alert">
                                        <p>{{session('success')}}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">ID</th>
                                        <th scope="col">Edição</th>
                                        <th scope="col">Opções</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($sisus as $sisu)
                                        <tr>
                                            <td> {{$sisu->id}}</td>
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
            </div>
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
