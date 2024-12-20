<x-app-layout>
<div class="fundo px-5 py-5">
    <div class="row justify-content-center">
        <div class="col-md-11 pt-0">
            <div class="row tituloBorda justify-content-between">
                <div class="d-flex align-items-center justify-content-between mx-0 px-0">
                    <span class="align-middle titulo">Cursos - Listagem</span>
                    <span class="aling-middle">
                        <a href="{{route('sisus.index')}}" title="Voltar" style="cursor: pointer;"><img class="m-1 " width="40" src="{{asset('img/Grupo 1687.svg')}}" alt="Icone de voltar"></a>
                        <a href="{{route('exportar-ingressantes-personalizado', $sisu->id)}}" title ="Exportar ingressantes SIGA" style="cursor: pointer;"><img class="m-1 " width="40" src="{{asset('img/Grupo 1700.svg')}}"  alt="Icone de exportar ingressantes siga"></a>
                        <a data-bs-toggle="modal" data-bs-target="#modalCriarListaFinal" title ="Gerar lista final" style="cursor: pointer;"><img class="m-1 " width="40" src="{{asset('img/Grupo 1654.svg')}}"  alt="Icone de listagem"></a>
                        <a data-bs-toggle="modal" data-bs-target="#modalResetarLista" title ="Resetar lista personalizada" style="cursor: pointer;"><img class="m-1 " width="40" src="{{asset('img/reset-red.svg')}}"  alt="Icone de resetar"></a>
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
                            @case($turnos['Matutino']){{"Matutino"}}@break
                            @case($turnos['Vespertino']){{"Vespertino"}}@break
                            @case($turnos['Noturno']){{"Noturno"}}@break
                            @case($turnos['Integral']){{"Integral"}}@break
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

<div class="modal fade" id="modalCriarListaFinal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-dialog">
            <div class="modal-content modalFundo p-3">
                <div class="col-md-12 tituloModal">Criar listagem final (personalizada)</div>
                    <div class="pt-3 pb-2 textoModal">
                        <form method="GET" id="gerar-lista-final" action="{{route('gerar-lista-final-personalizada', $sisu->id)}}">
                            @csrf
                            <div class="form-row">
                                <div class="col-md-12 form-group">
                                    <label for="titulo">{{ __('Título') }}</label>
                                    <input id="titulo" class="form-control @error('titulo') is-invalid @enderror" type="text" name="titulo" value="{{old('titulo')}}" required autofocus autocomplete="titulo">

                                    @error('titulo')
                                        <div id="validationServer03Feedback" class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </form>
                        <div class="row justify-content-between mt-4">
                            <div class="col-md-3">
                                <button type="button" class="btn botao my-2 py-1" data-bs-dismiss="modal"><span class="px-4">Cancelar</span></button>
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn botaoVerde my-2 py-1 submeterFormBotao" form="gerar-lista-final"><span class="px-4">Criar</span></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalResetarLista" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-dialog">
            <div class="modal-content modalFundo p-3">
                <div class="col-md-12 tituloModal">Resetar alterações da lista personalizada</div>
                    <div class="pt-3 pb-2 textoModal">
                        <form method="POST" id="resetar-lista" action="{{route('resetar.lista.personalizada', $sisu->id)}}">
                            @csrf
                            <h6><strong>
                                Tem certeza que deseja resetar todas as alterações manuais feitas na lista de ingressantes e reservas?
                            </strong></h6>
                        </form>
                        <div class="row justify-content-between mt-4">
                            <div class="col-md-3">
                                <button type="button" class="btn botao my-2 py-1" data-bs-dismiss="modal"><span class="px-4">Cancelar</span></button>
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn botaoVerde my-2 py-1 submeterFormBotao" form="resetar-lista"><span class="px-4">Resetar</span></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</x-app-layout>
