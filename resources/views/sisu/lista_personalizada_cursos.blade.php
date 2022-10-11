<x-app-layout>
<div class="fundo px-5 py-5">
    <div class="row justify-content-center">
        <div class="col-md-11 pt-0">
            <div class="row tituloBorda justify-content-between">
                <div class="d-flex align-items-center justify-content-between mx-0 px-0">
                    <span class="align-middle titulo">Cursos - Listagem</span>
                    <span class="aling-middle">
                        <a href="{{route('sisus.index')}}" title="Voltar" style="cursor: pointer;"><img class="m-1 " width="40" src="{{asset('img/Grupo 1687.svg')}}" alt="Icone de voltar"></a>
                    </span>
                </div>
            </div>
            @php
                $count = 0;
                $controle = true;
            @endphp
            @while ($controle)
                @if(($count+1) % 3 == 1)
                    <div class="row justify-content-between">
                @endif
                @if (array_key_exists($count, $cursos->toArray()))
                    <a title="Listar candidatos ingressantes do curso {{$cursos[$count]->nome}}" style="text-decoration: none" href="{{route('lista.personalizada.curso', ['sisu_id' => $sisu->id, 'curso_id' => $cursos[$count]->id])}}" class="col-md-3 caixa mt-5 shadow py-4 text-center" >
                        <img src="{{asset('storage/'.$cursos[$count]->icone)}}" width="100" class="img-fluid">
                        <div class="textoagronomia" style="color: {{$cursos[$count]->cor_padrao != null ? $cursos[$count]->cor_padrao : 'black'}}">{{$cursos[$count]->nome}}</div>
                        <div class="subtitulo">(@switch($cursos[$count]->grau_academico)
                            @case($graus['bacharelado']){{"Bacharelado"}}@break
                            @case($graus['licenciatura']){{"Licenciatura"}}@break
                            @case($graus['tecnologo']){{"Tecnólogo"}}@break
                        @endswitch -<strong>
                        @switch($cursos[$count]->turno)
                            @case($turnos['matutino']){{"Matutino"}}@break
                            @case($turnos['vespertino']){{"Vespertino"}}@break
                            @case($turnos['noturno']){{"Noturno"}}@break
                            @case($turnos['integral']){{"Integral"}}@break
                        @endswitch)</strong>
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
