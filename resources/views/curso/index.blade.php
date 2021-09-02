<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Cursos') }}
        </h2>
    </x-slot>
    <div class="container" style="padding-top: 5rem; padding-bottom: 8rem;">
        <div class="form-row justify-content-center">
            <div class="col-md-10">
                <div class="card" style="width: 100%;">
                    <div class="card-body">
                        <div class="form-row">
                            <div class="col-md-8">
                                <h5 class="card-title">Cursos da UFAPE cadastrados</h5>
                                <h6 class="card-subtitle mb-2 text-muted">Cursos</h6>
                            </div>
                            <div class="col-md-4" style="text-align: right">
                                <a class="btn btn-primary" href="{{route('cursos.create')}}">Criar novo curso</a>
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
                                        <th scope="col">#</th>
                                        <th scope="col">Nome</th>
                                        <th scope="col">Turno</th>
                                        <th scope="col">Opções</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($cursos as $i => $curso)
                                        <tr>
                                            <td> {{$i+1}}</td>
                                            <td>{{$curso->nome}}</td>
                                            <td>{{$curso->turno}}</td>
                                            <td>
                                                {{-- <div class="btn-group">
                                                    <div class="dropdown">
                                                        <button class="btn btn-light dropdown-toggle shadow-sm" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <img class="filter-green" src="{{asset('img/icon_acoes.svg')}}" style="width: 4px;">
                                                        </button>
                                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                                                            @if(Auth::user()->role == \App\Models\User::ROLE_ENUM['admin'] || Auth::user()->role == \App\Models\User::ROLE_ENUM['analista'])
                                                                <a class="dropdown-item" href="{{route('sisus.show', ['sisu' => $sisu->id])}}">Visualizar edição</a>
                                                                <a class="dropdown-item" data-toggle="modal" data-target="#modalStaticDeletarSisu_{{$sisu->id}}" style="color: red; cursor: pointer;">Deletar edição</a>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div> --}}
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
</x-app-layout>