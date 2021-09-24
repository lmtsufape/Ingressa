<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Chamadas') }}
        </h2>
    </x-slot>

    <div class="container" style="padding-top: 5rem; padding-bottom: 8rem;">
        <div class="form-row justify-content-center">
            <div class="col-md-10">
                <div class="card" style="width: 100%;">
                    <div class="card-body">
                        <div class="form-row">
                            <div class="col-md-8">
                                <h5 class="card-title">Chamadas da edição {{$sisu->edicao}} cadastradas no sistema</h5>
                                <h6 class="card-subtitle mb-2 text-muted">Chamadas</h6>
                            </div>
                            <div class="col-md-4" style="text-align: right">
                                <a class="btn btn-primary" href="{{route('chamadas.create', $sisu->id)}}">Criar nova chamada</a>
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
                                        <th scope="col">Nome</th>
                                        <th scope="col">Regular</th>
                                        <th scope="col">Opções</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($chamadas as $chamada)
                                        <tr>
                                            <td> {{$chamada->nome}}</td>
                                            @if ($chamada->regular)
                                                <td>Sim</td>
                                            @else
                                                <td>Não</td>
                                            @endif
                                            <td>
                                                <div class="btn-group">
                                                    @if ($chamada->caminho_import_sisu_gestao == null)
                                                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modalStaticImportarCandidatos_{{$chamada->id}}">
                                                            Importar candidatos
                                                        </button>
                                                    @else
                                                        <a class="btn btn-success shadow-sm" href="{{route('chamadas.candidatos', ['sisu_id' => $sisu->id, 'chamada_id' => $chamada->id])}}"><img src="{{ asset('img/icon_candidato.svg') }}" alt="Candidatos inscritos no sisu {{$sisu->edicao}}" width="23.5px" ></a>
                                                    @endif
                                                </div>
                                                <a class="btn btn-primary" href="{{route('chamadas.edit', ['chamada' => $chamada])}}">Editar</a>
                                                <a class="btn btn-info" href="{{route('chamadas.show', ['chamada' => $chamada])}}">Ver</a>
                                                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modalStaticDeletarChamada_{{$chamada->id}}">
                                                    Deletar
                                                </button>
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


    @foreach ($chamadas as $chamada)
        <!-- Modal deletar chamada -->
        <div class="modal fade" id="modalStaticDeletarChamada_{{$chamada->id}}" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header" style="background-color: #dc3545;">
                        <h5 class="modal-title" id="staticBackdropLabel" style="color: white;">Confirmação</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="deletar-chamada-form-{{$chamada->id}}" method="POST" action="{{route('chamadas.destroy', ['chamada' => $chamada])}}">
                            @csrf
                            <input type="hidden" name="_method" value="DELETE">
                            Tem certeza que deseja deletar a chamada da {{$chamada->nome}} edição {{$sisu->edicao}} do sisu?
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger" form="deletar-chamada-form-{{$chamada->id}}">Sim</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    @foreach ($chamadas as $chamada)
        <!-- Modal importar candidatos da chamada -->
        <div class="modal fade" id="modalStaticImportarCandidatos_{{$chamada->id}}" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header" style="background-color: #28a745;">
                        <h5 class="modal-title" id="staticBackdropLabel" style="color: white;">Importar Candidatos</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="cadastrar-candidatos-chamada-form-{{$chamada->id}}" method="POST" action="{{route('chamadas.importar.candidatos', ['sisu_id' =>$sisu->id, 'chamada_id' => $chamada->id])}}" enctype="multipart/form-data">
                            @csrf
                            <input type="file" name="arquivo" accept=".csv" required><br>
                            Anexe o arquivo .csv da chamada {{$chamada->nome}} da edição {{$sisu->edicao}}.
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success" form="cadastrar-candidatos-chamada-form-{{$chamada->id}}" id="submeterFormBotao">Importar</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</x-app-layout>
