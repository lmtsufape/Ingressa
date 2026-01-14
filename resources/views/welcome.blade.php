<x-guest-layout>
    <div class="fundo px-5 py-5">
        @can('isCandidato', \App\Models\User::class)
               @if($edicao_atual != null && auth()->user()->candidato->inscricoes->last()->sisu == $edicao_atual)
                @if(auth()->user()->candidato->inscricoes->last()->retificacao != null)
                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Success:"><use xlink:href="#check-circle-fill"/></svg>
                                    @if(auth()->user()->candidato->inscricoes->last()->retificacao == App\Models\Inscricao::STATUS_RETIFICACAO['bloqueado_motivo_racial'])
                                        Sua documentação de heteroidentificação foi <strong>INVALIDADA</strong>. Se deseja interpor recurso em relação ao paracer da banca de heteroidentificação siga os trâmites descritos no Edital.
                                    @elseif(auth()->user()->candidato->inscricoes->last()->retificacao == App\Models\Inscricao::STATUS_RETIFICACAO['bloqueado_motivo_medico'])
                                        Sua documentação referente ao laudo médico e exames foi <strong>INVALIDADA</strong>.
                                    @else
                                        Sua documentação de heteroidentificação, laudo médico e exames foi <strong>INVALIDADA</strong>. Se deseja interpor recurso em relação ao paracer da banca de heteroidentificação siga os trâmites descritos no Edital.
                                    @endif
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        </div>
                    </div>
                @endif
            @endif
        @endcan
        <div class="row justify-content-center" style="margin-bottom: 40px;">
            <div class="col-md-12" style="text-align: center; margin-bottom: 10px;">
                <span style="font-size: 35px; color: var(--secundaria); font-weight: bolder;">Bem-vindo(a) ao Ingressa!</span>
            </div>
            <div class="col-md-8" style="text-align: center;">
                O Ingressa é um sistema desenvolvido pela Universidade Federal do Agreste de Pernambuco (UFAPE) que visa auxiliar no processo de ingresso de alunos via SiSU, facilitando o recebimento de documentos para revisão, análise e matrícula do(a) aluno(a) na Universidade. Pense no Ingressa como a continuação do SiSU, o(a) candidato(a) entra com seus dados e envia os documentos para efetivar a matrícula! É rápido, simples e fácil! <br> Confira mais informações sobre a matrícula <a href="http://www.ufape.edu.br/sisu" target="_blank">AQUI</a>
            </div>
        </div>
        <div class="py-3 px-4 row ms-0 justify-content-between">
            @if($edicao_atual != null)
                <div class="col-md-3 shadow p-3 caixa">
                    <div class="row mx-1 justify-content-between lis">
                        <div class="d-flex align-items-center data justify-content-between mx-0 px-0">
                            <span class="aling-middle " style="font-size: 22px;">Datas Importantes</span>
                        </div>
                    </div>
                    @if(is_null($chamadas))
                        <div class="col-md-12 text-center">
                            <img class="img-fluid py-4" width="270" src="{{asset('img/Grupo 1652.svg')}}">
                        </div>
                        <div class="col-md-12 text-center legenda" style="font-weight: bolder;">
                            Nenhuma chamada criada
                        </div>
                    @else
                        @if(\App\Models\DataChamada::select('data_chamadas.*')
                                ->whereIn('chamada_id', $chamadas->pluck('id')->toArray())
                                ->get()->count() == 0)
                            <div class="col-md-12 text-center">
                                <img class="img-fluid py-4" width="270" src="{{asset('img/Grupo 1652.svg')}}">
                            </div>
                            <div class="col-md-12 text-center legenda" style="font-weight: bolder;">
                                Nenhuma data criada
                            </div>
                        @else
                            @foreach ($chamadas as $chamada)
                                @php
                                    $exibirTitulo = true;
                                @endphp
                                @if ($chamada->datasChamada->count() > 0)
                                    @if($exibirTitulo)
                                        <div style="color: var(--textcolors); font-size: 19px; font-weight: 600;" class="mt-2">{{$chamada->nome}}</div>
                                    @endif
                                    @php
                                        $exibirTitulo = false;
                                    @endphp
                                    <ul class="list-group list-unstyled">
                                        @foreach ($chamada->datasChamada as $data)
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
                                @endif
                            @endforeach
                        @endif
                    @endif
                </div>

                <div class="col-md-8 pt-0">
                    <div class="col-md-12 tituloBorda">
                        <div class="d-flex align-items-center justify-content-between mx-0 px-0">
                            <span class="align-middle titulo">Listagens</span>
                        </div>
                    </div>
                    <div class="col-md-12 mt-4 p-2 caixa shadow">
                        @if(is_null($chamadas))
                            <div class="text-center" style="margin-bottom: 10px;" >
                                <img class="img-fluid py-4" width="270" src="{{asset('img/Grupo 1654.svg')}}">
                                <div class="col-md-12 text-center legenda" style="font-weight: bolder;">
                                    Nenhuma listagem foi adicionada
                                </div>
                            </div>
                        @else
                            @if($checagem_chamada)
                                <div class="text-center" style="margin-bottom: 10px;" >
                                    <img class="img-fluid py-4" width="270" src="{{asset('img/Grupo 1654.svg')}}">
                                    <div class="col-md-12 text-center legenda" style="font-weight: bolder;">
                                        Nenhuma listagem foi adicionada
                                    </div>
                                </div>
                            @else
                                @foreach ($chamadas as $chamada)
                                    <ul class="list-group mx-2 list-unstyled">
                                        @foreach ($chamada->listagem as $listagem)
                                            @if($listagem->publicada)
                                                <li>
                                                    <div class="d-flex align-items-center listagemLista my-2 pt-1 pb-3">
                                                        <div class="">
                                                            <div class="mx-2 tituloLista">
                                                                {{$listagem->titulo}}{{-- - <span class="destaqueLista">@switch($listagem->tipo)
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
                                                                <a href="{{asset('storage/' . $listagem->caminho_listagem)}}" target="blanck" style="text-decoration: none; word-break: break-all"><img width="13" src="{{asset('img/Icon feather-link.svg')}}">{{asset('storage/' . $listagem->caminho_listagem)}}</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                            @endif
                                        @endforeach
                                    </ul>
                                @endforeach
                            @endif
                        @endif
                    </div>
                </div>
            @else
                <div class="row justify-content-center">
                    <div class="col-md-12" style="text-align: center;">
                        <div class="tituloEntrada" >
                            Fora do período de ingresso
                        </div>
                        {{--<div class="textoEntrada mt-2 text-justify">
                            O Lorem Ipsum é um texto modelo da indústria tipográfica e de impressão. O Lorem Ipsum tem vindo a ser o texto padrão usado por estas indústrias desde o ano de 1500, quando uma misturou os caracteres de um texto para criar um espécime de livro. Este texto não só sobreviveu 5 séculos, mas também o salto para a tipografia electrónica, mantendo-se essencialmente inalterada. Foi popularizada nos anos 60 com a disponibilização das folhas de Letraset, que continham passagens com Lorem Ipsum, e mais recentemente com os programas de publicação como o Aldus PageMaker que incluem versões do Lorem Ipsum.
                        </div>--}}
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{--<div class="container" style="margin-top: 50px; margin-bottom: 50px;">
        <div class="card text-center">
            <div class="card-header">
                <span class="titulo pt-0">Bem vindo ao {nome_do_sistema}!</span>
            </div>
            <div class="card-body" style="background-color: rgba(0, 0, 0, 0.03);">
                @if($edicao_atual != null)
                    <h5 class="card-title titulo pt-0" style="font-size: 34px;">SISU {{$edicao_atual->edicao}}</h5>
                    @foreach ($chamadas as $chamada)
                        <div class="row pb-4">
                            <div class="col-sm-12">
                                <div class="card" style="text-align: left">
                                    <div class="card-body">
                                        <h5 class="card-title titulo pt-0" style="font-size: 30px;">{{$chamada->nome}}</h5>
                                        <div class="">
                                            <div class="row ms-0 justify-content-between">
                                                <div class="col-md-4 shadow p-3 caixa">
                                                    <div class="col-md-12 data" style="font-size: 25px;">
                                                        Datas Importantes
                                                    </div>
                                                    @if ($chamada->datasChamada->count() > 0)
                                                        <ul class="list-group list-unstyled">
                                                            @foreach ($chamada->datasChamada as $data)
                                                                <li>
                                                                    <div class="d-flex align-items-center listagemLista my-2 pt-1 pb-3">
                                                                        @if ($data->tipo == $tipos_data['convocacao'])
                                                                            <img class="img-card-data" src="{{asset('img/icon_convocacao.png')}}" alt="Icone de convocação" width="45">
                                                                        @elseif($data->tipo == $tipos_data['envio'])
                                                                            <img class="img-card-data" src="{{asset('img/icon_envio.png')}}" alt="Icone de envio" width="45">
                                                                        @elseif($data->tipo == $tipos_data['analise'])
                                                                            <img class="img-card-data" src="{{asset('img/icon_resultado.png')}}" alt="Icone de envio" width="45">
                                                                        @elseif($data->tipo == $tipos_data['resultado_parcial'])
                                                                            <img class="img-card-data" src="{{asset('img/icon_resultado.png')}}" alt="Icone de resultados" width="45">
                                                                        @elseif($data->tipo == $tipos_data['reenvio'])
                                                                            <img class="img-card-data" src="{{asset('img/icon_envio.png')}}" alt="Icone de resultados" width="45">
                                                                        @elseif($data->tipo == $tipos_data['analise_reenvio'])
                                                                            <img class="img-card-data" src="{{asset('img/icon_envio.png')}}" alt="Icone de resultados" width="45">
                                                                        @elseif($data->tipo == $tipos_data['resultado_final'])
                                                                            <img class="img-card-data" src="{{asset('img/icon_resultado.png')}}" alt="Icone de resultados" width="45">
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
                                                    @else
                                                        <div class="col-md-12 text-center">
                                                            <img class="img-fluid py-4" width="270" src="{{asset('img/Grupo 1652.svg')}}">
                                                        </div>
                                                        <div class="col-md-12 text-center legenda">
                                                            Nenhuma data foi adicionada
                                                            @can('isAdmin', \App\Models\User::class)
                                                                <p><a class="redirecionamento" data-bs-toggle="modal" data-bs-target="#adicionarData">clique aqui</a> <span class="legenda">para adicionar</span></p>
                                                            @endcan
                                                        </div>
                                                    @endif
                                                </div>

                                                <div class="col-md-8 pt-0">
                                                    <div class="col-md-12 tituloBorda">
                                                        <span class="titulo pt-0" style="font-size: 28px;">Listagens</span>
                                                    </div>
                                                    <div class="col-md-12 mt-4 p-2 caixa shadow text-center">
                                                        @if($chamada->listagem->count() > 0)
                                                            <ul class="list-group mx-2 list-unstyled">
                                                                @foreach ($chamada->listagem as $listagem)
                                                                    <li>
                                                                        <div class="d-flex align-items-center listagemLista my-2 pt-1 pb-3">
                                                                            <div class="">
                                                                                <div class="mx-2 tituloLista">
                                                                                    {{$listagem->titulo}} - <span class="destaqueLista">@switch($listagem->tipo)
                                                                                        @case(\App\Models\Listagem::TIPO_ENUM['convocacao'])
                                                                                            convocação
                                                                                            @break
                                                                                        @case(\App\Models\Listagem::TIPO_ENUM['pendencia'])
                                                                                            pendência
                                                                                            @break
                                                                                        @case(\App\Models\Listagem::TIPO_ENUM['resultado'])
                                                                                            resultado
                                                                                            @break
                                                                                    @endswitch</span>
                                                                                </div>
                                                                                <div class="row px-1 link" style="text-align: left;">
                                                                                    <a href="{{asset('storage/' . $listagem->caminho_listagem)}}" target="blanck" style="text-decoration: none;"><img width="13" src="{{asset('img/Icon feather-link.svg')}}">{{asset('storage/' . $listagem->caminho_listagem)}}</a>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                        @else
                                                            <img class="img-fluid py-4" width="270" src="{{asset('img/Grupo 1654.svg')}}">
                                                            <div class="col-md-12 text-center legenda" style="margin-bottom: 20px;">
                                                                Nenhuma listagem foi adicionada
                                                                @can('isAdmin', \App\Models\User::class)
                                                                    <p><a class="redirecionamento" data-bs-toggle="modal" data-bs-target="#adicionarListagem">clique aqui</a> <span class="legenda">para adicionar</span></p>
                                                                @endcan
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mt-4 tituloEntrada">
                                Fora do período de ingresso
                            </div>
                            <div class="textoEntrada mt-2 text-justify">
                                O Lorem Ipsum é um texto modelo da indústria tipográfica e de impressão. O Lorem Ipsum tem vindo a ser o texto padrão usado por estas indústrias desde o ano de 1500, quando uma misturou os caracteres de um texto para criar um espécime de livro. Este texto não só sobreviveu 5 séculos, mas também o salto para a tipografia electrónica, mantendo-se essencialmente inalterada. Foi popularizada nos anos 60 com a disponibilização das folhas de Letraset, que continham passagens com Lorem Ipsum, e mais recentemente com os programas de publicação como o Aldus PageMaker que incluem versões do Lorem Ipsum.
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>--}}
</x-guest-layout>
