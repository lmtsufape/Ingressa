<x-app-layout>
    <div class="fundo2 px-5">
        <div class="row justify-content-center">
            <div class="col-md-9 cabecalho p-2 px-3 align-items-center">
                <div class="row justify-content-between">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <span class="tituloTabelas ps-1">Chamadas da edição {{$sisu->edicao}}
                            </span>
                        </div>
                        <div class="col-md-4" style="text-align: right">
                            <a class="btn btn-primary" href="{{route('chamadas.create', $sisu->id)}}">Criar nova chamada</a>
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
                            <th scope="col">Regular</th>
                            <th scope="col">Opções</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($chamadas as $i => $chamada)
                            <tr>
                                <th class="align-middle"> {{$i+1}}</th>
                                <td> {{$chamada->nome}}</td>
                                @if ($chamada->regular)
                                    <td>Sim</td>
                                @else
                                    <td>Não</td>
                                @endif
                                <td>
                                    <div class="btn-group">
                                        @if ($chamada->caminho_import_sisu_gestao == null)
                                            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalStaticImportarCandidatos_{{$chamada->id}}">
                                                Importar candidatos
                                            </button>
                                        @else

                                            @if($batches[$i]->finished())
                                                <a class="btn btn-success shadow-sm" href="{{route('chamadas.candidatos', ['sisu_id' => $sisu->id, 'chamada_id' => $chamada->id])}}"><img src="{{ asset('img/icon_candidato.svg') }}" alt="Candidatos inscritos no sisu {{$sisu->edicao}}" width="23.5px" ></a>
                                            @else
                                                <a><img style="width: 70px;" src="http://rpg.drivethrustuff.com/shared_images/ajax-loader.gif" alt="Cadastrando todos os candidatos..."/></a>
                                            @endif
                                        @endif
                                    </div>
                                    <a class="btn btn-primary" href="{{route('chamadas.edit', ['chamada' => $chamada])}}">Editar</a>
                                    <a class="btn btn-info" href="{{route('chamadas.show', ['chamada' => $chamada])}}">Ver</a>
                                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modalStaticDeletarChamada_{{$chamada->id}}">
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


    @foreach ($chamadas as $chamada)
        <!-- Modal deletar chamada -->
        <div class="modal fade" id="modalStaticDeletarChamada_{{$chamada->id}}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header" style="background-color: #dc3545;">
                        <h5 class="modal-title" id="staticBackdropLabel" style="color: white;">Confirmação</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
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
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger" form="deletar-chamada-form-{{$chamada->id}}">Sim</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    @foreach ($chamadas as $chamada)
        <!-- Modal importar candidatos da chamada -->
        <div class="modal fade bd-example-modal-lg" id="modalStaticImportarCandidatos_{{$chamada->id}}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header" style="background-color: #28a745;">
                        <h5 class="modal-title" id="staticBackdropLabel" style="color: white;">Importar Candidatos</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @if($chamada->regular)
                            <form id="cadastrar-candidatos-chamada-form-{{$chamada->id}}" method="POST" action="{{route('chamadas.importar.candidatos', ['sisu_id' =>$sisu->id, 'chamada_id' => $chamada->id])}}" enctype="multipart/form-data">
                                @csrf
                                <input type="file" name="arquivo" accept=".csv" required><br>
                                Anexe o arquivo .csv da chamada {{$chamada->nome}} da edição {{$sisu->edicao}}.
                            </form>
                        @else
                            <form id="cadastrar-candidatos-chamada-form-{{$chamada->id}}" method="POST" action="{{route('chamadas.importar.candidatos', ['sisu_id' =>$sisu->id, 'chamada_id' => $chamada->id])}}" enctype="multipart/form-data">
                                @csrf
                                <input type="file" name="arquivo" accept=".csv" required><br>
                                Anexe o arquivo .csv da chamada {{$chamada->nome}} da edição {{$sisu->edicao}}.
                                <div class="accordion" id="accordionExample">
                                    @foreach ($cursos as $i => $curso)
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="heading-{{$curso->id}}">
                                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{$curso->id}}" aria-expanded="false" aria-controls="collapse-{{$curso->id}}">
                                                    {{$curso->nome}} - @switch($curso->turno)
                                                    @case($turnos['matutino']){{"Matutino"}}@break
                                                    @case($turnos['vespertino']){{"Vespertino"}}@break
                                                    @case($turnos['noturno']){{"Noturno"}}@break
                                                    @case($turnos['integral']){{"Integral"}}@break
                                                    @endswitch
                                                </button>
                                            </h2>
                                            <div id="collapse-{{$curso->id}}" class="collapse" aria-labelledby="heading-{{$curso->id}}" data-parent="#{{$curso->id}}-Heading">
                                                <div class="card-body">
                                                    @foreach ($curso->cotas as $cota)
                                                        @if($cota->cod_cota != "B4342")
                                                            <hr>
                                                            <div class="row">
                                                                <div class="col-sm-12">
                                                                    <label><strong>{{$cota->cod_cota}}</strong></label>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-sm-4">
                                                                    <div class="" id="vagas{{$curso->id}}_{{$cota->id}}">
                                                                        <label for="vagas-{{$curso->id}}-{{$cota->id}}">{{__('Número de vagas')}}</label>
                                                                        <input type="number" id="vagas-curso-{{$curso->id}}-{{$cota->id}}" class="form-control" value="{{$cota->pivot->quantidade_vagas}}" disabled>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-4">
                                                                    <div class="" id="efetivados{{$curso->id}}_{{$cota->id}}">
                                                                        <label for="efetivados-{{$curso->id}}-{{$cota->id}}">{{__('Número de efetivados')}}</label>
                                                                        <input type="number" id="candidatos-efetivados-{{$curso->id}}-{{$cota->id}}" class="form-control" value="{{$cota->pivot->vagas_ocupadas}}" disabled>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-4">
                                                                    <div class="" id="multiplicador_{{$curso->id}}_{{$cota->id}}">
                                                                        <label for="multiplicador-{{$curso->id}}-{{$cota->id}}">{{__('Multiplicador de vagas')}}</label>
                                                                        <input type="number" name="quantidade_curso_{{$curso->id}}[]" id="quantidade-{{$curso->id}}-{{$cota->id}}" class="form-control @error('quantidade-'.$curso->id.'-'.$cota->id) is-invalid @enderror" value="{{old('quantidade-'.$curso->id.'-'.$cota->id)!=null ? old('quantidade-'.$curso->id.'-'.$cota->id) : 3}}">
                                                                        <input type="hidden" name="cota_id_{{$curso->id}}[]" id="cota-id-{{$curso->id}}-{{$cota->id}}" class="form-control @error('cota-id-'.$curso->id.'-'.$cota->id) is-invalid @enderror" value="{{$cota->id}}">

                                                                        @error('quantidade-'.$curso->id.'-'.$cota->id)
                                                                            <div id="validationServer03Feedback" class="invalid-feedback">
                                                                                {{ $message }}
                                                                            </div>
                                                                        @enderror
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </form>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success" form="cadastrar-candidatos-chamada-form-{{$chamada->id}}" id="submeterFormBotao">Importar</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</x-app-layout>

<script>
</script>
