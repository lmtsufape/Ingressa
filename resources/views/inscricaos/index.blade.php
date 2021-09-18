<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Minhas Inscrições') }}
        </h2>
    </x-slot>
    <div class="container" style="padding-top: 5rem; padding-bottom: 8rem;">
        <div class="form-row justify-content-center">
            <div class="col-md-10">
                <div class="card" style="width: 100%;">
                    <div class="card-body">
                        <div class="form-row">
                            <div class="col-md-8">
                                <h5 class="card-title">Minhas Inscrições do SiSU na UFAPE</h5>
                                <h6 class="card-subtitle mb-2 text-muted">Cursos</h6>
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
                                            <td>{{$i+1}}</td>
                                            <td>{{$curso->nome}}</td>
                                            <td>
                                                @switch($curso->turno)
                                                    @case($turnos['matutino'])
                                                        {{__('Matutino')}}
                                                        @break
                                                    @case($turnos['vespertino'])
                                                        {{__('Vespertino')}}
                                                        @break
                                                    @case($turnos['noturno'])
                                                        {{__('Noturno')}}
                                                        @break
                                                    @case($turnos['integral'])
                                                        {{__('Integral')}}
                                                        @break
                                                @endswitch
                                            </td>
                                            <td>
                                                <a class="btn btn-primary" href="{{route('cursos.edit', ['curso' => $curso])}}">Editar</a>
                                                <a class="btn btn-danger" data-toggle="modal" data-target="#delete-curso-{{$curso->id}}">Deletar</a>
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

    @foreach ($cursos as $curso)

    <!-- Modal -->
    <div class="modal fade" id="delete-curso-{{$curso->id}}" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #dc3545;">
                    <h5 class="modal-title" id="staticBackdropLabel" style="color: white;">Deletar curso</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="delete-curso-{{$curso->id}}-form" method="POST" action="{{route('cursos.destroy', ['curso' => $curso])}}">
                        @csrf
                        <input type="hidden" name="_method" value="DELETE">
                        Tem certeza que deseja deletar esse curso?
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger" form="delete-curso-{{$curso->id}}-form">Sim</button>
                </div>
            </div>
        </div>
    </div>

    @endforeach
</x-app-layout>
