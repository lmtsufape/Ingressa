<x-app-layout>
    <div class="fundo2 px-5">
        <div class="row justify-content-center">
            <div class="col-md-7 cabecalho p-2 px-3 align-items-center">
              <div class="row justify-content-between">
                <div class="d-flex align-items-center justify-content-between">
                  <div class="d-flex align-items-center">
                    <img src="{{asset('img/Grupo 1684.svg')}}"
                          alt="" width="40" class="img-flex">
                      <span class="tituloTabelas ps-1">Chamadas da edição <span style="font-weight: 600;">{{$sisu->edicao}}</span></span>
                  </div>
                    @if(auth()->user()->role == \App\Models\User::ROLE_ENUM['admin'])
                        <a data-bs-toggle="modal" data-bs-target="#adicionarChamada"><img width="35" src="{{asset('img/Grupo 1674.svg')}}"></a>
                    @endif
                </div>

              </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-7 corpo p-2 px-3">
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
                            <th class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($chamadas as $i => $chamada)
                            <tr>
                                <th class="align-middle"> {{$i+1}}</th>
                                <td class="align-middle"> {{$chamada->nome}}</td>
                                @if ($chamada->regular)
                                    <td class="align-middle">Sim</td>
                                @else
                                    <td class="align-middle">Não</td>
                                @endif
                                <td class="align-middle text-center">
                                    <div class="btn-group">
                                        @if ($chamada->caminho_import_sisu_gestao == null)
                                            @if(auth()->user()->role == \App\Models\User::ROLE_ENUM['admin'])
                                                <a data-bs-toggle="modal" data-bs-target="#modalStaticImportarCandidatos_{{$chamada->id}}" style="cursor: pointer;"><img class="m-1 " width="30" src="{{asset('img/Grupo 1683.svg')}}"  alt="icone-busca"></a>
                                            @else
                                                <a style="cursor: pointer;" disabled><img class="m-1 " width="30" src="{{asset('img/Grupo 1683.svg')}}"  alt="icone-busca"></a>
                                            @endif
                                        @else
                                            @if($batches[$i]->finished())
                                                <a href="{{route('chamadas.candidatos', ['sisu_id' => $sisu->id, 'chamada_id' => $chamada->id])}}"><img class="m-1 " width="30" src="{{asset('img/Grupo 1682.svg')}}" alt="icone-busca"></a>
                                            @else
                                                <a><img style="width: 70px;" src="http://rpg.drivethrustuff.com/shared_images/ajax-loader.gif" alt="Cadastrando todos os candidatos..."/></a>
                                            @endif
                                        @endif
                                    </div>
                                    @if(auth()->user()->role == \App\Models\User::ROLE_ENUM['admin'])
                                        <a href="{{route('chamadas.show', ['chamada' => $chamada])}}"><img class="m-1 " width="30" src="{{asset('img/Grupo 1681.svg')}}"  alt="icone-busca"></a>
                                        <a data-bs-toggle="modal" data-bs-target="#modalStaticEditarChamada_{{$chamada->id}}" style="cursor: pointer;"><img class="m-1 " width="30" src="{{asset('img/Grupo 1675.svg')}}"  alt="icone-busca"></a>
                                        <a data-bs-toggle="modal" data-bs-target="#modalStaticDeletarChamada_{{$chamada->id}}" style="cursor: pointer;"><img class="m-1 " width="30" src="{{asset('img/Grupo 1664.svg')}}"  alt="icone-busca"></a>
                                    @endif
                                </td>
                        @endforeach
                    </tbody>
                </table>
                @if(auth()->user()->role == \App\Models\User::ROLE_ENUM['admin'])
                    <a class="btn botao my-2 py-1" href="{{route('sisus.index')}}"> <span class="px-4">Voltar</span></a>
                @endif
            </div>
        </div>
    </div>

  <!--CORPO-->

 <!--MODAL-->

 <div class="modal fade" id="adicionarChamada" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content modalFundo p-3">
            <div class="col-md-12 tituloModal">Insira uma nova chamada</div>

            <div class="col-md-12 pt-3 pb-2 textoModal">
                <form method="POST" id="criar-chamada" action="{{route('chamadas.store')}}">
                    @csrf
                    <input type="hidden" name="chamada_id" value="-1">
                    <input type="hidden" name="sisu" value="{{$sisu->id}}">
                    <div class="form-row">
                        <div class="col-md-12 form-group">
                            <label class="pb-2" for="nome"><span style="color: red; font-weight: bold;">* </span>{{ __('Nome da chamada:') }}</label>
                            <input id="nome" class="form-control l campoDeTexto @error('nome') is-invalid @enderror"  placeholder="Insira o nome da chamada" type="name" name="nome" required autofocus autocomplete="nome">

                            @error('nome')
                                <div id="validationServer03Feedback" class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-12 form-group">
                            <label class="pb-2 pt-2" for="descricao"><span style="color: red; font-weight: bold;">* </span>{{ __('Descrição da chamada:') }}</label>
                            <textarea id="descricao" class="form-control campoDeTexto @error('descricao') is-invalid @enderror" rows="3" type="text" name="descricao" required autofocus autocomplete="descricao"></textarea>

                            @error('descricao')
                                <div id="validationServer03Feedback" class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <label class="pb-2 pt-2"><span style="color: red; font-weight: bold;">*</span> Selecione se é uma chamada regular ou não:</label>
                    <div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" id="regular_sim" name="regular" value="true" {{$tem_regular == null ? '' : 'disabled' }}>
                            <label class="form-check-label" for="regular_sim">Sim</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" id="regular_nao" name="regular" value="false" {{$tem_regular == null ? '' : 'checked' }}>
                            <label class="form-check-label" for="regular_nao">Não</label>
                        </div>
                    </div>
                </form>
            </div>

            <div class="row justify-content-between mt-4">
                <div class="col-md-3">
                    <button type="button" class="btn botao my-2 py-1" data-bs-dismiss="modal"> <span class="px-4">Voltar</span></button>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn botaoVerde my-2 py-1" form="criar-chamada" ><span class="px-4">Publicar</span></button>
                </div>
            </div>
        </div>
    </div>
</div>

<!--MODAL-->


    @foreach ($chamadas as $chamada)
        <!-- Modal deletar sisu -->
        <div class="modal fade" id="modalStaticDeletarChamada_{{$chamada->id}}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-dialog">
                    <div class="modal-content modalFundo p-3">
                        <div class="col-md-12 tituloModal">Deletar chamada</div>
                            <div class="pt-3 pb-2 textoModal">
                                <form id="deletar-chamada-form-{{$chamada->id}}" method="POST" action="{{route('chamadas.destroy', ['chamada' => $chamada])}}">
                                    @csrf
                                    <input type="hidden" name="_method" value="DELETE">
                                    Tem certeza que deseja deletar a chamada {{$chamada->nome}}?
                                </form>
                                <div class="row justify-content-between mt-4">
                                    <div class="col-md-3">
                                        <button type="button" class="btn botao my-2 py-1" data-bs-dismiss="modal"><span class="px-4">Cancelar</span></button>
                                    </div>
                                    <div class="col-md-4">
                                        <button type="submit" class="btn botaoVerde my-2 py-1" form="deletar-chamada-form-{{$chamada->id}}" style="background-color: #FC605F;"><span class="px-4">Excluir</span></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    @endforeach

    @foreach ($chamadas as $chamada)
        <!-- Modal editar chamada -->
        <div class="modal fade" id="modalStaticEditarChamada_{{$chamada->id}}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-dialog">
                    <div class="modal-content modalFundo p-3">
                        <div class="col-md-12 tituloModal">Editar chamada</div>
                            <div class="pt-3 pb-2 textoModal">
                                <form method="POST" id="editar-chamada-form-{{$chamada->id}}" action="{{route('chamadas.update', $chamada->id)}}">
                                    @csrf
                                    <input type="hidden" name="chamada_id" value="{{$chamada->id}}">
                                    <input type="hidden" name="_method" value="PUT">
                                    <div class="form-row">
                                        <div class="col-md-12 form-group">
                                            <label class="pb-2"  for="nome"><span style="color: red; font-weight: bold;">* </span>{{ __('Nome') }}</label>
                                            <input id="nome" class="form-control campoDeTexto @error('nome') is-invalid @enderror" type="text" name="nome" value="{{old('nome')!=null ? old('nome') : $chamada->nome}}" required autofocus autocomplete="nome">

                                            @error('nome')
                                                <div id="validationServer03Feedback" class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-12 form-group">
                                            <label class="pb-2 pt-2" for="descricao"><span style="color: red; font-weight: bold;">* </span>{{ __('Descrição') }}</label>
                                            <textarea id="descricao" class="form-control campoDeTexto @error('descricao') is-invalid @enderror" rows="3" type="text" name="descricao" required autofocus autocomplete="descricao">@if(old('descricao')!=null){{old('descricao')}}@else{{($chamada->descricao)}}@endif</textarea>

                                            @error('descricao')
                                                <div id="validationServer03Feedback" class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <label class="pb-2 pt-2"><span style="color: red; font-weight: bold;">*</span> Selecione se é uma chamada regular ou não:</label>
                                    <div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" id="regular_sim" name="regular" value="true" {{($tem_regular && !($chamada->regular)) ? 'disabled' : '' }} @if(!old('regular') || ($chamada->regular)) checked @endif>
                                            <label class="form-check-label" for="regular_sim">Sim</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" id="regular_nao" name="regular" value="false" @if(old('regular') || !($chamada->regular)) checked @endif>
                                            <label class="form-check-label" for="regular_nao">Não</label>
                                        </div>
                                    </div>
                                </form>
                                <div class="row justify-content-between mt-4">
                                    <div class="col-md-3">
                                        <button type="button" class="btn botao my-2 py-1" data-bs-dismiss="modal"><span class="px-4">Cancelar</span></button>
                                    </div>
                                    <div class="col-md-4">
                                        <button type="submit" class="btn botaoVerde my-2 py-1" form="editar-chamada-form-{{$chamada->id}}"><span class="px-4">Editar</span></button>
                                    </div>
                                </div>
                            </div>
                        </div>
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
                                                                        <input type="number" name="multiplicadores_curso_{{$curso->id}}[]" id="multiplicadores-curso-{{$curso->id}}-{{$cota->id}}" class="form-control @error('multiplicadores-curso-'.$curso->id.'-'.$cota->id) is-invalid @enderror" value="{{old('multiplicadores-curso-'.$curso->id.'-'.$cota->id)!=null ? old('multiplicadores-curso-'.$curso->id.'-'.$cota->id) : 3}}">
                                                                        <input type="hidden" name="cotas_id_{{$curso->id}}[]" id="cota-id-{{$curso->id}}-{{$cota->id}}" value="{{$cota->id}}">

                                                                        @error('multiplicadores-curso-'.$curso->id.'-'.$cota->id)
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

@if(old('chamada_id') == -1)
<script>
    $(document).ready(function() {
        $('#adicionarChamada').modal('show');
    });
</script>
@endif

@if(old('chamada_id') > 0)
<script>
    $(document).ready(function() {
        $('#modalStaticEditarChamada_{{old('chamada_id')}}').modal('show');
    });
</script>
@endif

