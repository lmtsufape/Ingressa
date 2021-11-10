<x-app-layout>
    <div class="fundo px-5 py-5">
        <div class="row justify-content-center">
            <div class="col-md-11 pt-0">
                <div class="row tituloBorda justify-content-between">
                    <div class="d-flex align-items-center justify-content-between mx-0 px-0">
                        <span class="align-middle titulo">Candidatos por curso</span>
                        <div class="col-md-4" style="text-align: right">
                            <a class="btn btn-primary" id="submeterFormBotao" href="{{route('chamadas.candidatos.aprovar', ['sisu_id' => $chamada->sisu->id, 'chamada_id' => $chamada->id])}}">Efetivar candidatos</a>
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
                    @if(($count+1) % 4 == 1)
                        <div class="row justify-content-between">
                    @endif
                    @if (array_key_exists($count, $cursos->toArray()))
                        <a style="text-decoration: none" href="{{route('chamadas.candidatos.curso', ['sisu_id' => $chamada->sisu->id, 'chamada_id' => $chamada->id, 'curso_id' => $cursos[$count]->id])}}" class="col-md-2 caixa mt-5 shadow p-3 py-4 text-center" >
                            <img src="{{asset('storage/'.$cursos[$count]->icone)}}" width="100" class="img-fluid">
                            <div class="textoagronomia" style="color: {{$cursos[$count]->cor_padrao != null ? $cursos[$count]->cor_padrao : 'black'}}">{{$cursos[$count]->nome}}</div>
                            <div class="subtitulo">(@switch($cursos[$count]->grau_academico)
                                @case($graus['bacharelado']){{"Bacharelado"}}@break
                                @case($graus['licenciatura']){{"Licenciatura"}}@break
                                @case($graus['tecnologo']){{"Tecnólogo"}}@break
                            @endswitch -
                            @switch($cursos[$count]->turno)
                                @case($turnos['matutino']){{"Manhã"}}@break
                                @case($turnos['vespertino']){{"Tarde"}}@break
                                @case($turnos['noturno']){{"Noturno"}}@break
                                @case($turnos['integral']){{"Integral"}}@break
                            @endswitch)
                            </div>
                            <div class="subtitulo">
                                {{"Pendentes: ".($chamados[$count]-$concluidos[$count])}}<br>
                                {{"Concluídos: ".$concluidos[$count]}}
                            </div>
                        </a>
                    @else
                        <div class="col-md-2  mt-4  p-3 text-center"></div>
                    @endif
                    @if(($count+1) % 4 == 0)
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
