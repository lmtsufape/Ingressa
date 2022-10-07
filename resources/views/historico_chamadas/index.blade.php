<x-app-layout>
    <div class="fundo px-5 py-5">
        <div class="py-3 px-4 row ms-0 justify-content-between">
            <div class="col-md-12 pt-0">
                <div class="col-md-12 tituloBorda">
                    <div class="d-flex align-items-center justify-content-between mx-0 px-0">
                        <span class="align-middle titulo">Edições anteriores do SiSU</span>
                        <span class="aling-middle">
                        </span>
                    </div>
                </div>
                <div class="col-md-12 mt-4 p-2 caixa shadow">
                    @if ($edicoes->first() != null)
                        <ul class="list-group mx-2 list-unstyled">
                            @foreach ($edicoes as $sisu)
                                <li class="listagemLista">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="d-flex align-items-center my-2 pt-1 pb-3">
                                                <div class="">
                                                    <div class="mx-2 tituloLista" style="font-size: 20px">
                                                        <a title="Exibir detalhes da edição" style="text-decoration: none;" href="{{route('edicoes.show', $sisu->id)}}">SiSU {{$sisu->edicao}}</a>
                                                    </div>
                                                </div>
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
                                    Nenhuma edição foi criada
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>