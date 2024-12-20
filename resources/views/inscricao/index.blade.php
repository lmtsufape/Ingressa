<x-app-layout>
    <div class="fundo px-5 py-5">
        <div class="row justify-content-center">
            <div class="col-md-11 pt-0">
                <div class="row tituloBorda justify-content-between">
                    <div class="d-flex align-items-center justify-content-between mx-0 px-0">
                        <span class="align-middle titulo">Minhas inscrições</span>
                    </div>
                </div>
                @if(session('success'))
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
                @endif
                @php
                    $count = 0;
                    $controle = true;
                @endphp
                @while ($controle)
                    @if(($count+1) % 3 == 1)
                        <div class="row justify-content-between">
                    @endif
                    @if (array_key_exists($count, $cursos->toArray()))

                        <a style="text-decoration: none"  href="{{route('inscricao.documentacao', $inscricoes[$count]->id)}}" class="col-md-3 caixa mt-5 shadow p-3 py-3 text-center" >
                            <h6 style="color: rgb(110, 110, 110)">
                                <strong>SiSU {{$inscricoes[$count]->chamada->sisu->edicao}}</strong><br>
                                <strong>{{$inscricoes[$count]->chamada->nome}}</strong><br>
                            </h6>
                            <img src="{{asset('storage/'.$cursos[$count]->icone)}}" width="100" class="img-fluid">
                            <div class="textoagronomia" style="color: {{$cursos[$count]->cor_padrao != null ? $cursos[$count]->cor_padrao : 'black'}}">{{$cursos[$count]->nome}}</div>
                            <div class="subtitulo">(@switch($cursos[$count]->grau_academico)
                                @case($graus['bacharelado']){{"Bacharelado"}}@break
                                @case($graus['licenciatura']){{"Licenciatura"}}@break
                                @case($graus['tecnologo']){{"Tecnólogo"}}@break
                            @endswitch -
                            @switch($cursos[$count]->turno)
                                @case($turnos['Matutino']){{"Matutino"}}@break
                                @case($turnos['Vespertino']){{"Vespertino"}}@break
                                @case($turnos['Noturno']){{"Noturno"}}@break
                                @case($turnos['Integral']){{"Integral"}}@break
                            @endswitch)
                            </div>
                            <div class="subtitulo" >
                                @can('periodoRetificacao', $inscricoes[$count]->chamada)
                                    <strong>Reenvio da documentação: </strong>
                                @else
                                    <strong>Envio da documentação: </strong>
                                @endcan
                                <span>
                                    @can('periodoRetificacao', $inscricoes[$count]->chamada)
                                        @if($inscricoes[$count]->chamada->datasChamada()->where('tipo', \App\Models\DataChamada::TIPO_ENUM['reenvio'])->first() != null)
                                            {{date('d/m/Y',strtotime($inscricoes[$count]->chamada->datasChamada()->where('tipo', \App\Models\DataChamada::TIPO_ENUM['reenvio'])->first()->data_inicio))}} - {{date('d/m/Y',strtotime($inscricoes[$count]->chamada->datasChamada()->where('tipo', \App\Models\DataChamada::TIPO_ENUM['reenvio'])->first()->data_fim))}}
                                        @else
                                            <span style="color: rgb(255, 89, 89)">período de reenvio indefinido</span>
                                        @endif
                                    @else
                                        @if($inscricoes[$count]->chamada->datasChamada()->where('tipo', \App\Models\DataChamada::TIPO_ENUM['envio'])->first() != null)
                                            {{date('d/m/Y',strtotime($inscricoes[$count]->chamada->datasChamada()->where('tipo', \App\Models\DataChamada::TIPO_ENUM['envio'])->first()->data_inicio))}} - {{date('d/m/Y',strtotime($inscricoes[$count]->chamada->datasChamada()->where('tipo', \App\Models\DataChamada::TIPO_ENUM['envio'])->first()->data_fim))}}
                                        @else
                                            <span style="color: rgb(255, 89, 89)">período de envio indefinido</span>
                                        @endif
                                    @endcan
                                </span>
                            </div>
                            <div class="subtitulo" style="margin-top: 10px;">
                                <strong>Status: </strong>
                                <span>
                                    @switch($inscricoes[$count]->status)
                                        @case($situacoes['documentos_pendentes'])
                                            @can('dataEnvio', $inscricoes[$count]->chamada)
                                                Documentos pendentes
                                            @else 
                                                Documentos pendentes
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <small> Fora do periodo de envio/reenvio</small>
                                                    </div>
                                                </div>
                                            @endcan
                                            @break
                                        @case($situacoes['documentos_enviados'])
                                                Documentos em análise
                                            @break
                                        @case($situacoes['documentos_aceitos_sem_pendencias'])
                                                Documentos aceitos
                                            @break
                                        @case($situacoes['documentos_aceitos_com_pendencias'])
                                                Documentos aceitos com pendências
                                            @break
                                        @case($situacoes['documentos_invalidados'])
                                                Documentos invalidados
                                            @break
                                    @endswitch
                                </span>
                            </div>

                        </a>
                    @else
                        <div class="col-md-3  mt-4  p-3 text-center"></div>
                    @endif
                    @if(($count+1) % 3 == 0)
                        </div>
                        @if (!(array_key_exists($count, $cursos->toArray())))
                            @php
                                $controle = false;
                            @endphp
                        @endif
                    @endif
                    @php
                        $count++;
                    @endphp
                @endwhile
            </div>
        </div>
    </div>
</x-app-layout>
