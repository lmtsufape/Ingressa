<x-app-layout>
    <div class="fundo px-5 py-5">
        <div class="py-3 px-4 row ms-0 justify-content-between">
            <div class="col-md-3 shadow p-3 caixa">
                <div class="row mx-1 justify-content-between lis">
                    <div class="d-flex align-items-center data justify-content-between mx-0 px-0">
                        <span class="aling-middle " style="font-size: 22px;">Datas Importantes</span>
                        <span class="aling-middle">
                            <button title="Adicionar data" data-bs-toggle="modal" data-bs-target="#modalStaticCriarData_{{$chamada->id}}" ><img width="30" src="{{asset('img/Grupo 1668.svg')}}" alt="Icone de criar data" style="cursor:pointer;"></button>
                            <button title="Editar data" data-bs-toggle="modal" data-bs-target="#editarData" ><img width="30" src="{{asset('img/Grupo 1675.svg')}}" alt="Icone de editar data" style="cursor:pointer;"></button>
                        </span>
                    </div>
                </div>
                <div div class="form-row">
                    @if(session('success_data'))
                        <div class="col-md-12" style="margin-top: 5px;">
                            <div class="alert alert-success" role="alert">
                                <p>{{session('success_data')}}</p>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        </div>
                    @endif
                </div>
                @if ($datas->first() != null)
                <ul class="list-group list-unstyled">
                    @foreach ($datas as $data)
                        <li>
                            <div class="d-flex align-items-center listagemLista my-2 pt-1 pb-3">
                                @if ($data->tipo == $tipos_data['convocacao'])
                                    <img class="img-card-data" src="{{asset('img/icon-chamada.svg')}}" alt="Icone de convocação" width="45">
                                @elseif($data->tipo == $tipos_data['envio'])
                                    <img class="img-card-data" src="{{asset('img/icon-envioDoc.svg')}}" alt="Icone de envio" width="45">
                                @elseif($data->tipo == $tipos_data['analise'])
                                    <img class="img-card-data" src="{{asset('img/icon-analiseDoc (2).svg')}}" alt="Icone de analise" width="45">
                                @elseif($data->tipo == $tipos_data['resultado_parcial'])
                                    <img class="img-card-data" src="{{asset('img/icon-resultadoParcial.svg')}}" alt="Icone de resultado parcial" width="45">
                                @elseif($data->tipo == $tipos_data['reenvio'])
                                    <img class="img-card-data" src="{{asset('img/icon-envioDoc.svg')}}" alt="Icone de reenvio" width="45">
                                @elseif($data->tipo == $tipos_data['analise_reenvio'])
                                    <img class="img-card-data" src="{{asset('img/icon-analiseRetificacao.svg')}}" alt="Icone de analise do reenvio" width="45">
                                @elseif($data->tipo == $tipos_data['resultado_final'])
                                    <img class="img-card-data" src="{{asset('img/icon-resultadoFinal.svg')}}" alt="Icone de resultado final" width="45">
                                @endif

                                <div class="">
                                    <div class="tituloLista aling-middle mx-3">
                                        {{$data->titulo}}
                                    </div>
                                    <div class="aling-middle mx-3 datinha">
                                        {{date('d/m/Y',strtotime($data->data_inicio))}} > {{date('d/m/Y',strtotime($data->data_fim))}}
                                    </div>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
                    {{-- @foreach ($datas as $data)
                        <div class="container" style="margin: 0px; padding: 0px;">
                            <div class="card" style="margin-bottom: 10px;">
                                <div class="card-body">
                                    <div class="row" data-bs-toggle="modal" data-bs-target="#modalStaticEditarData_{{$data->id}}">
                                        <div class="col-md-3">
                                            @if ($data->tipo == $tipos['convocacao'])
                                                <a ><img class="img-card-data" src="{{asset('img/icon_convocacao.png')}}" alt="" width="40px"></a>
                                            @elseif($data->tipo == $tipos['envio'])
                                                <img class="img-card-data" src="{{asset('img/icon_envio.png')}}" alt="" width="40px">
                                            @elseif($data->tipo == $tipos['resultado'])
                                                <img class="img-card-data" src="{{asset('img/icon_resultado.png')}}" alt="" width="40px">
                                            @endif
                                        </div>
                                        <div class="col-md-6">
                                            <div><h5 style=" font-size:17px; font-weight: bold;">{{$data->titulo}}</h5></div>
                                            <div><h6 style="font-size:15px; font-weight: normal; color:#909090">{{date('d/m/Y',strtotime($data->data_inicio))}}</h6></div>
                                            <div><h6 style="font-size:15px; font-weight: normal; color:#909090">{{date('d/m/Y',strtotime($data->data_inicio))}}</h6></div>
                                        </div>
                                        <div class="col-md-3">
                                            <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modalStaticDeletarData_{{$data->id}}">x</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach --}}
                @else
                    <div class="col-md-12 text-center">
                        <img class="img-fluid py-4" width="270" src="{{asset('img/Grupo 1652.svg')}}">
                    </div>
                    <div class="col-md-12 text-center legenda" style="font-weight: bolder;">
                        Nenhuma data foi adicionada
                        <p><button class="redirecionamento" data-bs-toggle="modal" data-bs-target="#modalStaticCriarData_{{$chamada->id}}" style="cursor: pointer; font-weight: bolder;">clique aqui</button> <span class="legenda" style="font-weight: bolder;">para adicionar</span></p>
                    </div>
                @endif
            </div>

            <div class="col-md-8 pt-0">
                <div class="col-md-12 tituloBorda">
                    <div class="d-flex align-items-center justify-content-between mx-0 px-0">
                        <span class="align-middle titulo">Listagens</span>
                        <span class="aling-middle">
                            <a href="{{route('sisus.show', ['sisu' => $chamada->sisu->id])}}" title="Voltar" style="cursor: pointer;"><img class="m-1 " width="40" src="{{asset('img/Grupo 1687.svg')}}" alt="Icone de voltar"></a>
                            <button title="Criar listagem" data-bs-toggle="modal" data-bs-target="#modalStaticCriarListagem"><img src="{{asset('img/Grupo 1666.svg')}}" class="m-1" alt="Inserir nova listagem" width="40px" style="cursor:pointer;"></button>
                            <button title="Editar listagem" data-bs-toggle="modal" data-bs-target="#editarListagem"><img src="{{asset('img/Grupo 1667.svg')}}" class="m-1" alt="Editar listagem" width="40px" style="cursor:pointer;"></button>
                        </span>
                    </div>
                </div>
                <div class="col-md-12 mt-4 p-2 caixa shadow">
                    @if(session('success_listagem'))
                        <div class="col-md-12" style="margin-top: 5px;">
                            <div class="alert alert-success" role="alert">
                                <p>{{session('success_listagem')}}</p>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        </div>
                    @endif
                    @if ($listagens->first() != null)
                        <ul class="list-group mx-2 list-unstyled">
                            @foreach ($listagens as $listagem)
                                <li class="listagemLista">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="d-flex align-items-center my-2 pt-1 pb-3">
                                                <div class="">
                                                    <div class="mx-2 tituloLista">
                                                        {{$listagem->titulo}} {{--  - <span class="destaqueLista">@switch($listagem->tipo)
                                                            @case(\App\Models\Listagem::TIPO_ENUM['convocacao'])
                                                                convocação
                                                                @break
                                                            @case(\App\Models\Listagem::TIPO_ENUM['pendencia'])
                                                                pendência
                                                                @break
                                                            @case(\App\Models\Listagem::TIPO_ENUM['resultado'])
                                                                resultado
                                                                @break
                                                        @endswitch</span> --}}
                                                    </div>
                                                    <div class="row px-1 link">
                                                        <a href="{{asset('storage/' . $listagem->caminho_listagem)}}" target="blanck" style="text-decoration: none;"><img width="13" src="{{asset('img/Icon feather-link.svg')}}">{{asset('storage/' . $listagem->caminho_listagem)}}</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 form-group" style="text-align: right;">
                                            <div class="mt-4">
                                                <input id="publicada" type="checkbox" name="publicada" class="form-check-input form-check-cursos" @if($listagem->publicada) checked @endif onclick="publicar({{$listagem->id}}, this)">
                                                <label for="publicada">Publicada</label>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="text-center" style="margin-bottom: 10px;" >
                            <img class="img-fluid py-4" width="270" src="{{asset('img/Grupo 1654.svg')}}">
                            <div class="col-md-12 text-center legenda" style="font-weight: bolder;">
                                    Nenhuma listagem foi adicionada
                                <p><button class="redirecionamento" data-bs-toggle="modal" data-bs-target="#modalStaticCriarListagem" style="cursor: pointer; font-weight: bolder;" >clique aqui</a> <span class="legenda" style="font-weight: bolder;">para adicionar</span></button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editarListagem" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content modalFundo pt-3 px-3 pb-1">
                <div class="col-md-12 tituloModal">Listagens</div>
                <div class="col-md-12 pt-1 textoModal">
                    <ul class="list-group list-unstyled">
                        @foreach ($listagens as $listagem)
                            <li>
                                <div class="d-flex align-items-center justify-content-between my-2 pt-1">
                                    <div class="">
                                        <div class="mx-2 tituloLista">
                                            {{$listagem->titulo}} - <span class="destaqueLista">@switch($listagem->tipo)
                                                @case(\App\Models\Listagem::TIPO_ENUM['convocacao'])
                                                    convocação
                                                    @break
                                                @case(\App\Models\Listagem::TIPO_ENUM['resultado'])
                                                    resultado
                                                    @break
                                                @case(\App\Models\Listagem::TIPO_ENUM['final'])
                                                    final
                                                    @break
                                            @endswitch</span>
                                        </div>
                                        <div class="row px-1 link">
                                            <a href="{{asset('storage/' . $listagem->caminho_listagem)}}" target="blanck" style="text-decoration: none;"><img width="13" src="{{asset('img/Icon feather-link.svg')}}">{{asset('storage/' . $listagem->caminho_listagem)}}</a>
                                        </div>
                                    </div>
                                    <div>
                                        <button title="Deletar listagem" data-bs-toggle="modal" data-bs-target="#modalStaticDeletarListagem_{{$listagem->id}}"><img class="m-1 " width="35" src="{{asset('img/Grupo 1664.svg')}}" alt="Icone de deletar listagem" style="cursor: pointer;"></button>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="row justify-content-between mt-4">
                    <div class="col-md-3">
                        <button type="button" class="btn botao my-2 py-1" data-bs-dismiss="modal"> <span class="px-4">Voltar</span></button>
                    </div>
                    {{-- <div class="col-md-4">
                        <button type="button" class="btn botaoVerde my-2 py-1"><span class="px-4">Publicar</span></button>
                    </div>        --}}
                </div>
            </div>
        </div>
    </div>

    <!-- Modal criar data -->
    <div class="modal fade" id="modalStaticCriarData_{{$chamada->id}}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content modalFundo p-3">
                <div class="col-md-12 tituloModal">Insira uma nova data</div>

                <div class="col-md-12 pt-3 pb-2 textoModal">
                    <form id="criar-data-form" method="POST" action="{{route('datas.store')}}">
                        @csrf
                        <input type="hidden" name="data_id" value="-1">
                        <input type="hidden" name="chamada" value="{{$chamada->id}}">
                        <div class="row">
                            <div class="col-sm-12 mb-3">
                                <label for="titulo" class="form-label">{{__('Título da data')}}</label>
                                <input type="text" id="titulo" name="titulo" class="form-control campoDeTexto @error('titulo') is-invalid @enderror" value="{{old('titulo')}}" autofocus required placeholder="Insira o título da data">

                                @error('titulo')
                                    <div id="validationServer03Feedback" class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 mb-3">
                                <label for="tipo" class="form-label">{{__('Tipo da data')}}</label>
                                <select name="tipo" id="tipo" class="form-control campoDeTexto @error('tipo') is-invalid @enderror" required>
                                    <option value="" selected disabled>-- Selecione o tipo da data --</option>
                                    <option @if(old('tipo') == $tipos_data['convocacao']) selected @endif value="{{$tipos_data['convocacao']}}">Publicação da lista de convocados</option>
                                    <option @if(old('tipo') == $tipos_data['envio']) selected @endif value="{{$tipos_data['envio']}}">Envio de documentos</option>
                                    <option @if(old('tipo') == $tipos_data['analise']) selected @endif value="{{$tipos_data['analise']}}">Análise de documentos</option>
                                    <option @if(old('tipo') == $tipos_data['resultado_parcial']) selected @endif value="{{$tipos_data['resultado_parcial']}}">Resultado parcial</option>
                                    <option @if(old('tipo') == $tipos_data['reenvio']) selected @endif value="{{$tipos_data['reenvio']}}">Período de retificação</option>
                                    <option @if(old('tipo') == $tipos_data['analise_reenvio']) selected @endif value="{{$tipos_data['analise_reenvio']}}">Análise do período de retificação</option>
                                    <option @if(old('tipo') == $tipos_data['resultado_final']) selected @endif value="{{$tipos_data['resultado_final']}}">Resultado final</option>
                                </select>

                                @error('tipo')
                                    <div id="validationServer03Feedback" class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6 mb-3">
                                <label for="data_inicio" class="form-label">{{ __('Data de início') }} </label>
                                <input class="form-control campoDeTexto @error('data_inicio') is-invalid @enderror" type="date"  id="data_inicio" name="data_inicio" required autocomplete="data_inicio">

                                @error('data_inicio')
                                    <div id="validationServer03Feedback" class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-sm-6 mb-3">
                                <label for="data_fim" class="form-label">{{ __('Data de fim') }} </label>
                                <input class="form-control campoDeTexto @error('data_inicio') is-invalid @enderror" type="date"  id="data_fim" name="data_fim" required autocomplete="data_fim">

                                @error('data_fim')
                                    <div id="validationServer03Feedback" class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </form>

                    <div class="row justify-content-between mt-4">
                        <div class="col-md-3">
                            <button type="button" class="btn botao my-2 py-1" data-bs-dismiss="modal"> <span class="px-4">Voltar</span></button>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn botaoVerde my-2 py-1" form="criar-data-form"><span class="px-4" >Publicar</span></button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    @foreach ($datas as $data)
        <!-- Modal deletar data -->
        <div class="modal fade" id="modalStaticDeletarData_{{$data->id}}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content modalFundo p-3">
                    <div class="col-md-12 tituloModal">Excluir data</div>

                    <div class="col-md-12 pt-3 pb-2 textoModal">
                        <form id="deletar-data-form-{{$data->id}}" method="POST" action="{{route('datas.destroy', ['data' => $data])}}">
                            @csrf
                            <input type="hidden" name="_method" value="DELETE">
                            Tem certeza que deseja deletar a data {{$data->titulo}}?
                        </form>

                        <div class="row justify-content-between mt-4">
                            <div class="col-md-3">
                                <button type="button" class="btn botao my-2 py-1" data-bs-toggle="modal" data-bs-target="#editarData"> <span class="px-4">Voltar</span></button>
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn botaoVerde my-2 py-1" form="deletar-data-form-{{$data->id}}" style="background-color: #FC605F;"><span class="px-4" >Excluir</span></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal editar data -->
        <div class="modal fade" id="modalStaticEditarData_{{$data->id}}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content modalFundo p-3">
                    <div class="col-md-12 tituloModal">Editar data</div>
                    <div class="col-md-12 pt-3 pb-2 textoModal">
                        <form id="editar-data-form-{{$data->id}}" method="POST" action="{{route('datas.update', ['data' => $data])}}">
                            @csrf
                            <input type="hidden" name="data_id" value="{{$data->id}}">
                            <input type="hidden" name="_method" value="PUT">
                            <div class="row">
                                <div class="col-sm-12 mb-3">
                                    <label for="titulo">{{__('Título da data')}}</label>
                                    <input type="text" id="titulo" name="titulo" class="form-control @error('titulo') is-invalid @enderror" value="{{old('titulo')!=null ? old('titulo') : $data->titulo}}" required autofocus autocomplete="titulo">

                                    @error('titulo')
                                        <div id="validationServer03Feedback" class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12 mb-3">
                                    <label for="tipo">{{__('Tipo da data')}}</label>
                                    <select name="tipo" id="tipo" class="form-control @error('tipo') is-invalid @enderror" required>
                                        <option value="{{$data->tipo}}" selected >
                                            @if ($data->tipo == $tipos_data['convocacao']) Publicação da lista de convocados
                                            @elseif($data->tipo == $tipos_data['envio']) Envio de documentos
                                            @elseif($data->tipo == $tipos_data['analise']) Análise de documentos
                                            @elseif($data->tipo == $tipos_data['resultado_parcial']) Resultado parcial
                                            @elseif($data->tipo == $tipos_data['reenvio']) Período de retificação
                                            @elseif($data->tipo == $tipos_data['analise_reenvio']) Análise do período de retificação
                                            @elseif($data->tipo == $tipos_data['resultado_final']) Resultado
                                            @endif
                                        </option>
                                        @if ($data->tipo != $tipos_data['convocacao'])
                                            <option @if(old('tipo') == $tipos_data['convocacao']) selected @endif value="{{$tipos_data['convocacao']}}">Publicação da lista de convocados</option>
                                        @endif
                                        @if ($data->tipo != $tipos_data['envio'])
                                            <option @if(old('tipo') == $tipos_data['envio']) selected @endif value="{{$tipos_data['envio']}}">Envio de documentos</option>
                                        @endif
                                        @if ($data->tipo != $tipos_data['analise'])
                                            <option @if(old('tipo') == $tipos_data['analise']) selected @endif value="{{$tipos_data['analise']}}">Análise de documentos</option>
                                        @endif
                                        @if ($data->tipo != $tipos_data['resultado_parcial'])
                                            <option @if(old('tipo') == $tipos_data['resultado_parcial']) selected @endif value="{{$tipos_data['resultado_parcial']}}">Resultado parcial</option>
                                        @endif
                                        @if ($data->tipo != $tipos_data['reenvio'])
                                            <option @if(old('tipo') == $tipos_data['reenvio']) selected @endif value="{{$tipos_data['reenvio']}}">Período de retificação</option>
                                        @endif
                                        @if ($data->tipo != $tipos_data['analise_reenvio'])
                                            <option @if(old('tipo') == $tipos_data['analise_reenvio']) selected @endif value="{{$tipos_data['analise_reenvio']}}">Análise do período de retificação</option>
                                        @endif
                                        @if ($data->tipo != $tipos_data['resultado_final'])
                                            <option @if(old('tipo') == $tipos_data['resultado_final']) selected @endif value="{{$tipos_data['resultado_final']}}">Resultado final</option>
                                        @endif
                                    </select>

                                    @error('tipo')
                                        <div id="validationServer03Feedback" class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6 mb-3">
                                    <label for="data_inicio">{{ __('Data de início') }} </label>
                                    <input type="date" class="form-control @error('data_fim') is-invalid @enderror" id="data_inicio" name="data_inicio" value="{{old('data_inicio')!=null ? old('data_inicio') : $data->data_inicio}}" required autofocus autocomplete="data_inicio">

                                    @error('data_inicio')
                                        <div id="validationServer03Feedback" class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-sm-6 mb-3">
                                    <label for="data_fim">{{ __('Data de fim') }} </label>
                                    <input type="date" class="form-control @error('data_fim') is-invalid @enderror"  id="data_fim" name="data_fim" required autofocus autocomplete="data_fim" value="{{old('data_fim')!=null ? old('data_fim') : $data->data_fim}}" required autofocus autocomplete="data_fim">

                                    @error('data_fim')
                                        <div id="validationServer03Feedback" class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </form>
                        <div class="row justify-content-between mt-4">
                            <div class="col-md-3">
                                <button type="button" class="btn botao my-2 py-1" data-bs-toggle="modal" data-bs-target="#editarData"> <span class="px-4">Voltar</span></button>
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn botaoVerde my-2 py-1" form="editar-data-form-{{$data->id}}"><span class="px-4">Salvar</span></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

   <!-- Modal criar listagem -->
   <div class="modal fade" id="modalStaticCriarListagem" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content modalFundo p-3">
                <div class="col-md-12 tituloModal">Insira uma nova listagem</div>

                <div class="col-md-12 pt-3 pb-2 textoModal">
                    <form id="criar-listagem-form" method="POST" action="{{route('listagems.store')}}">
                        @csrf
                        <input type="hidden" name="chamada" value="{{$chamada->id}}">
                        <div class="row">
                            <div class="col-sm-12 mb-3">
                                <label for="titulo">{{__('Título da listagem')}}</label>
                                <input type="text" id="titulo" name="titulo" class="form-control campoDeTexto @error('titulo') is-invalid @enderror" value="{{old('titulo')}}" autofocus required placeholder="Insira o titulo da lista">

                                @error('titulo')
                                    <div id="validationServer03Feedback" class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 mb-3">
                                <label for="tipo">{{__('Selecione o tipo')}}</label>
                                <select name="tipo" id="tipo" class="form-control campoDeTexto @error('tipo') is-invalid @enderror" required>
                                    <option value="" selected disabled>-- Selecione o tipo da listagem --</option>
                                    <option @if(old('tipo') == $tipos_listagem['convocacao']) selected @endif value="{{$tipos_listagem['convocacao']}}">Convocados</option>
                                    <option @if(old('tipo') == $tipos_listagem['pendencia']) selected @endif value="{{$tipos_listagem['pendencia']}}">Parcial/Pendências documentais</option>
                                    <option @if(old('tipo') == $tipos_listagem['resultado']) selected @endif value="{{$tipos_listagem['resultado']}}">Validados e invalidados</option>
                                    <option @if(old('tipo') == $tipos_listagem['final']) selected @endif value="{{$tipos_listagem['final']}}">Final/Ingressantes e reservas</option>
                                </select>

                                @error('tipo')
                                    <div id="validationServer03Feedback" class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 mb-3">
                                <label for="tipo">{{__('Ordenação')}}</label>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <input id="radio-ordenacao-nome" class="form-check-input" type="radio" name="ordenacao" value="nome" @if(old('ordenacao') == 'nome') @else checked @endif>
                                        <label for="radio-ordenacao-nome">{{__('Nome do candidato')}}</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <input id="radio-ordenacao-nota" class="form-check-input" type="radio" name="ordenacao" value="nota" @if(old('ordenacao') == 'nota') checked @endif>
                                        <label for="radio-ordenacao-nota">{{__('Nota do ENEM')}}</label>
                                    </div>
                                </div>

                                @error('tipo')
                                    <div id="validationServer03Feedback" class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <br>

                        <div class="accordion" id="accordionExample">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingOne">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                        Cursos
                                    </button>
                                </h2>
                            </div>

                            <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#cursosHeading">
                                <div class="card-body">
                                    <div class="row">
                                        <label for="curso">{{__('Selecione o(s) curso(s)')}}</label>
                                        <div class="col-sm-12">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input form-check-cursos" id="chk_marcar_desmarcar_todos_cursos" onclick="marcar_desmarcar_todos_checkbox_por_classe(this, 'checkbox_curso')">
                                                <label for="chk_marcar_desmarcar_todos_cursos"><b>Selecionar todos</b></label>
                                            </div>
                                        </div>
                                        @foreach ($cursos as $curso)
                                            <div class="col-sm-12">
                                                <div class="form-check">
                                                    <input class="checkbox_curso form-check-input form-check-cursos" type="checkbox" name="cursos[]" value="{{$curso->id}}" id="curso_{{$curso->id}}">
                                                    <label class="form-check-label" for="curso_{{$curso->id}}">
                                                        {{$curso->nome}} - {{$curso->getTurno()}}
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingTwo">
                                  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                    Cotas
                                  </button>
                                </h2>
                                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#cotasHeading">
                                    <div class="card-body">
                                        <div class="form-row">
                                            <label for="cota">{{__('Selecione a(s) cota(s)')}}</label>
                                            <div class="col-sm-12 form-group">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input form-check-cursos" id="chk_marcar_desmarcar_todas_cotas" onclick="marcar_desmarcar_todos_checkbox_por_classe(this, 'checkbox_cota')">
                                                    <label for="chk_marcar_desmarcar_todas_cotas"><b>Selecionar todas</b></label>
                                                </div>
                                            </div>
                                            @foreach ($cotas as $cota)
                                                <div class="col-sm-12 form-group">
                                                    <div class="form-check">
                                                        <input class="checkbox_cota form-check-input form-check-cursos" type="checkbox" name="cotas[]" value="{{$cota->id}}" id="cota_{{$cota->id}}">
                                                        <label class="form-check-label" for="cota_{{$cota->id}}">
                                                            {{$cota->cod_cota}} - {{$cota->nome}}
                                                        </label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="row justify-content-between mt-4">
                    <div class="col-md-3">
                        <button type="button" class="btn botao my-2 py-1" data-bs-dismiss="modal"> <span class="px-4">Voltar</span></button>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn botaoVerde my-2 py-1" form="criar-listagem-form"><span class="px-4">Publicar</span></button>
                    </div>
                </div>
                {{-- <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Voltar</button>
                    <button type="submit" class="btn btn-success btn-data-listagem" >Publicar</button>
                </div> --}}
            </div>
        </div>
    </div>
    @foreach ($listagens as $listagem)
        <!-- Modal deletar listagem -->
        <div class="modal fade" id="modalStaticDeletarListagem_{{$listagem->id}}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content modalFundo pt-3 px-3 pb-1">
                  <div class="col-md-12 tituloModal">Listagens</div>

                    <div class="col-md-12 pt-1 textoModal">
                        <form id="deletar-listagem-form-{{$listagem->id}}" method="POST" action="{{route('listagems.destroy', ['listagem' => $listagem])}}">
                            @csrf
                            <input type="hidden" name="_method" value="DELETE">
                            Tem certeza que deseja deletar a listagem {{$listagem->titulo}}?
                        </form>

                        <div class="row justify-content-between mt-4">
                            <div class="col-md-3">
                                <button type="button" class="btn botao my-2 py-1" data-bs-toggle="modal" data-bs-target="#editarListagem"> <span class="px-4">Voltar</span></button>
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn botaoVerde my-2 py-1" form="deletar-listagem-form-{{$listagem->id}}" style="background-color: #FC605F;"><span class="px-4">Excluir</span></button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    @endforeach

    <div class="modal fade" id="editarData" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content modalFundo pt-3 px-3 pb-1">
                <div class="col-md-12 tituloModal">Datas</div>
                <div class="col-md-12 pt-1 textoModal">
                    <ul class="list-group list-unstyled">
                        <li>
                            <div class="d-flex align-items-center my-2 pt-1">
                                <table class="table mt-0">
                                    <tbody>
                                        @foreach ($datas as $data)
                                            <tr>
                                                <th class="align-middle pe-0">
                                                    @if ($data->tipo == $tipos_data['convocacao'])
                                                        <img class="img-card-data" src="{{asset('img/icon-chamada.svg')}}" alt="Icone de convocação" width="45">
                                                    @elseif($data->tipo == $tipos_data['envio'])
                                                        <img class="img-card-data" src="{{asset('img/icon-envioDoc.svg')}}" alt="Icone de envio" width="45">
                                                    @elseif($data->tipo == $tipos_data['analise'])
                                                        <img class="img-card-data" src="{{asset('img/icon-analiseDoc (2).svg')}}" alt="Icone de analise" width="45">
                                                    @elseif($data->tipo == $tipos_data['resultado_parcial'])
                                                        <img class="img-card-data" src="{{asset('img/icon-resultadoParcial.svg')}}" alt="Icone de resultado parcial" width="45">
                                                    @elseif($data->tipo == $tipos_data['reenvio'])
                                                        <img class="img-card-data" src="{{asset('img/icon-envioDoc.svg')}}" alt="Icone de reenvio" width="45">
                                                    @elseif($data->tipo == $tipos_data['analise_reenvio'])
                                                        <img class="img-card-data" src="{{asset('img/icon-analiseRetificacao.svg')}}" alt="Icone de analise do reenvio" width="45">
                                                    @elseif($data->tipo == $tipos_data['resultado_final'])
                                                        <img class="img-card-data" src="{{asset('img/icon-resultadoFinal.svg')}}" alt="Icone de resultado final" width="45">
                                                    @endif
                                                </th>
                                                <td class="align-middle p-0">
                                                    <div class="">
                                                        <div class="tituloLista aling-middle">
                                                            {{$data->titulo}}
                                                        </div>
                                                        <div class="aling-middle datinha">
                                                            {{date('d/m/Y', strtotime($data->data_inicio))}} > {{date('d/m/Y', strtotime($data->data_fim))}}
                                                        </div>
                                                    </div>
                                                </td>
                                                <td style="text-align: right;" class="align-middle">
                                                    <button title="Deletar data" data-bs-toggle="modal" data-bs-target="#modalStaticDeletarData_{{$data->id}}"><img class="m-1" width="35" src="{{asset('img/Grupo 1664.svg')}}" alt="Icone excluir data" style="cursor: pointer;"></button>
                                                    <button title="Editar data" data-bs-toggle="modal" data-bs-target="#modalStaticEditarData_{{$data->id}}"><img class="m-1" width="35" src="{{asset('img/Grupo 1665.svg')}}"alt="Icone editar data" style="cursor: pointer;"></button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="row justify-content-between mt-4">
                    <div class="col-md-3">
                        <button type="button" class="btn botao my-2 py-1" data-bs-dismiss="modal"> <span class="px-4">Voltar</span></button>
                    </div>
                    <div class="col-md-4">
                        {{-- <button type="button" class="btn botaoVerde my-2 py-1"><span class="px-4">Publicar</span></button> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
<script>
    function publicar(id, checkbox_input) {
        $.ajax({
            url: "{{route('publicar.listagem')}}",
            method: 'get',
            type: 'get',
            data: {
                "listagem_id": id,
                "publicar": checkbox_input.checked,
                },
            dataType:'json',
        });
        document.location.reload(true);
    }
</script>
<script src="{{ asset('js/checkbox_marcar_todos.js') }}" defer></script>

@if(old('data_id') == -1)
<script>
    $(document).ready(function(){
        $('#modalStaticCriarData_{{old('chamada')}}').modal('show');
    });
</script>
@endif

@if(old('data_id') > 0)
<script>
    $(document).ready(function(){
        $('#modalStaticEditarData_{{old('data_id')}}').modal('show');
    });
</script>
@endif
