<x-app-layout>
    <div class="fundo px-5 py-5">
        <div class="row justify-content-center">
            <div class="col-md-11 pt-0">
                <div class="row tituloBorda justify-content-between">
                    <div class="d-flex align-items-center justify-content-between mx-0 px-0">
                        <span class="align-middle titulo"> <a href="{{route('sisus.show', ['sisu' => $chamada->sisu->id])}}" style="text-decoration: none; color: #373737;"> SiSU {{$chamada->sisu->edicao}}</a> > Candidatos da {{$chamada->nome}}</span>
                        <div class="col-md-4" style="text-align: right">
                            <a href="{{route('sisus.show', ['sisu' => $chamada->sisu->id])}}" title="Voltar" style="cursor: pointer;"><img class="m-1 " width="40" src="{{asset('img/Grupo 1687.svg')}}" alt="Icone de voltar"></a>
                            {{--<a class="btn btn-primary" id="submeterFormBotao" href="{{route('chamadas.candidatos.aprovar', ['sisu_id' => $chamada->sisu->id, 'chamada_id' => $chamada->id])}}">Efetivar candidatos</a>--}}
                        </div>
                    </div>
                </div>
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
                @php
                    $count = 0;
                    $controle = true;
                @endphp
                @while ($controle)
                    @if(($count+1) % 3 == 1)
                        <div class="row justify-content-between">
                    @endif
                    @if (array_key_exists($count, $cursos->toArray()))
                        <a title="Listar candidatos do curso {{$cursos[$count]->nome}}" style="text-decoration: none" href="{{route('chamadas.candidatos.curso', ['sisu_id' => $chamada->sisu->id, 'chamada_id' => $chamada->id, 'curso_id' => $cursos[$count]->id])}}" class="col-md-3 caixa mt-5 shadow py-4 text-center" >
                            <img src="{{asset('storage/'.$cursos[$count]->icone)}}" width="100" class="img-fluid">
                            <div class="textoagronomia" style="color: {{$cursos[$count]->cor_padrao != null ? $cursos[$count]->cor_padrao : 'black'}}">{{$cursos[$count]->nome}}</div>
                            <div class="subtitulo">(@switch($cursos[$count]->grau_academico)
                                @case($graus['bacharelado']){{"Bacharelado"}}@break
                                @case($graus['licenciatura']){{"Licenciatura"}}@break
                                @case($graus['tecnologo']){{"Tecnólogo"}}@break
                            @endswitch -<strong>
                            @switch($cursos[$count]->turno)
                                @case($turnos['Matutino']){{"Matutino"}}@break
                                @case($turnos['Vespertino']){{"Vespertino"}}@break
                                @case($turnos['Noturno']){{"Noturno"}}@break
                                @case($turnos['Integral']){{"Integral"}}@break
                            @endswitch)</strong>
                            </div>
                            <div class="subtitulo" style="width: 100%">
                                <strong>{{"Concluídos: "}}</strong>{{$concluidos[$count]}}<br>
                                @can('isAdminOrAnalistaGeral', \App\Models\User::class)
                                    <strong>{{"Concluídos (pendências): "}}</strong>{{$concluidosPendentes[$count]}}<br>
                                @endcan
                                <strong>{{"Enviados: "}}</strong>{{$enviados[$count]}}<br>
                                <strong>{{"Pendentes: "}}</strong>{{$naoEnviados[$count]}}<br>
                                <strong>{{"Invalidados: "}}</strong>{{$invalidados[$count]}}
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
