<x-app-layout>
    <div class="fundo px-5 py-5">
        <div class="py-3 px-4 row ms-0 justify-content-between">
            <div class="col-md-3 shadow p-3 caixa">
                <div class="row mx-1 justify-content-between lis">
                    <div class="d-flex align-items-center data justify-content-between mx-0 px-0">
                        <span class="aling-middle " style="font-size: 22px;">Datas Importantes</span>
                    </div>
                </div>
                @foreach ($sisu->chamadas as $chamada)
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
            </div>

            <div class="col-md-8 pt-0">
                <div class="col-md-12 tituloBorda">
                    <div class="d-flex align-items-center justify-content-between mx-0 px-0">
                        <span class="align-middle titulo">Listagens</span>
                    </div>
                </div>
                <div class="col-md-12 mt-4 p-2 caixa shadow">
                    @foreach ($sisu->chamadas as $chamada)
                        <ul class="list-group mx-2 list-unstyled">
                            @foreach ($chamada->listagem as $listagem)
                                @if($listagem->publicada)
                                    <li>
                                        <div class="d-flex align-items-center listagemLista my-2 pt-1 pb-3">
                                            <div class="">
                                                <div class="mx-2 tituloLista">
                                                    {{$listagem->titulo}}
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
                </div>
            </div>
        </div>
    </div>
</x-app-layout>