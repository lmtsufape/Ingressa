<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Cotas') }}
        </h2>
    </x-slot>
    <div class="container" style="padding-top: 5rem; padding-bottom: 8rem;">
        <div class="form-row justify-content-center">
            <div class="col-md-10">
                <div class="card" style="width: 100%;">
                    <div class="card-body">
                        <div class="form-row">
                            <div class="col-md-8">
                                <h5 class="card-title">Cotas dos cursos da UFAPE cadastradas</h5>
                                <h6 class="card-subtitle mb-2 text-muted">Cotas</h6>
                            </div>
                            <div class="col-md-4" style="text-align: right">
                                <a class="btn btn-primary" href="{{route('cotas.create')}}">Criar uma nova cota</a>
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
                                        <th scope="col">Opções</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($cotas as $i => $cota)
                                        <tr>
                                            <td>{{$i+1}}</td>
                                            <td>{{$cota->nome}}</td>
                                            <td>
                                                <a class="btn btn-info" href="{{route('cotas.remanejamento', ['cota' => $cota])}}">Remanejamento</a>
                                                <a class="btn btn-primary" href="{{route('cotas.edit', ['cota' => $cota])}}">Editar</a>
                                                <a class="btn btn-danger" data-toggle="modal" data-target="#delete-cota-{{$cota->id}}">Deletar</a>
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

    @foreach ($cotas as $cota)
  
    <!-- Modal -->
    <div class="modal fade" id="delete-cota-{{$cota->id}}" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #dc3545;">
                    <h5 class="modal-title" id="staticBackdropLabel" style="color: white;">Deletar cota</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="delete-cota-{{$cota->id}}-form" method="POST" action="{{route('cotas.destroy', ['cota' => $cota])}}">
                        @csrf
                        <input type="hidden" name="_method" value="DELETE">
                        Tem certeza que deseja deletar essa cota?
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger" form="delete-cota-{{$cota->id}}-form">Sim</button>
                </div>
            </div>
        </div>
    </div>
  
    @endforeach
</x-app-layout>