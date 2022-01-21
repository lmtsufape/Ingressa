<x-app-layout>
    <div class="fundo2 px-5">
        <div class="container">
            {{--<div class="col-sm-12">
                <div class="row">
                    <div class="col-md-12" style="text-align: right">
                        <a href="{{route('chamadas.candidatos', ['sisu_id' => $chamada->sisu->id, 'chamada_id' => $chamada->id])}}" class="btn botao my-2 py-1" type="submit"> <span class="px-4">Voltar</span></a>
                    </div>
                </div>
            </div>--}}
            <div class="row justify-content-between">
                <div class="col-md-9">
                    <div class="col-md-12 shadow-sm">
                        <div class="row justify-content-center">
                            <div class="col-md-12 cabecalhoCurso p-2 px-3 align-items-center" style="background-color: {{$curso->cor_padrao != null ? $curso->cor_padrao : 'black'}}">
                              <div class="row justify-content-between">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center">
                                      <img style="border:2px solid white; border-radius: 50%;"  src="{{asset('storage/'.$curso->icone)}}"
                                        alt="" width="45" class="img-flex">
                                      <div>
                                        <span class="tituloTabelas ps-1 mb-0 pb-0">{{$curso->nome}} - {{$turno}}</span>
                                        <div class="ps-1 mt-0 pt-0" style="font-size: 14px; color: white;">
                                            <span style="font-weight: bolder;">Ordenação:
                                                @switch($ordem)
                                                    @case('name')
                                                        Nome do candidato
                                                        @break
                                                    @case('cota')
                                                        Cota
                                                        @break
                                                    @case('status')
                                                        Status da inscrição
                                                        @break
                                                    @default
                                                        Nome do candidato
                                                        @break
                                                @endswitch
                                            </span>
                                        </div>
                                      </div>
                                    </div>
                                    <div>
                                      <button title="Ordenar candidatos" class="" type="button" id="dropdownMenu2" data-bs-toggle="dropdown" aria-expanded="false">
                                        <img width="35" src="{{asset('img/Subtração 2.svg')}}" alt="Icone de ordenação de candidatos">
                                      </button>
                                      <ul class="dropdown-menu px-2" aria-labelledby="dropdownMenu2">
                                        <div class="form-check link-ordenacao">
                                            <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault1" @if($ordem == null || $ordem == 'name') checked @endif>
                                            <label class="form-check-label" for="flexRadioDefault1">
                                                Nome do candidato
                                            </label>
                                            <a id="link-ordem-name" href="{{route('chamadas.candidatos.curso', ['sisu_id' => $sisu->id, 'chamada_id' => $chamada->id, 'curso_id' => $curso->id])}}?ordem=name"></a>
                                        </div>
                                        <div class="form-check link-ordenacao">
                                            <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault2"  @if($ordem == 'cota') checked @endif>
                                            <label class="form-check-label" for="flexRadioDefault2">
                                                Cota
                                            </label>
                                            <a id="link-ordem-cota" href="{{route('chamadas.candidatos.curso', ['sisu_id' => $sisu->id, 'chamada_id' => $chamada->id, 'curso_id' => $curso->id])}}?ordem=cota"></a>
                                        </div>
                                        <div class="form-check link-ordenacao">
                                            <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault2"  @if($ordem == 'status') checked @endif>
                                            <label class="form-check-label" for="flexRadioDefault2">
                                                Status da inscrição
                                            </label>
                                            <a id="link-ordem-status" href="{{route('chamadas.candidatos.curso', ['sisu_id' => $sisu->id, 'chamada_id' => $chamada->id, 'curso_id' => $curso->id])}}?ordem=status"></a>
                                          </div>
                                      </ul>
                                    </div>
                                </div>

                              </div>
                            </div>
                        </div>

                        <div class="row justify-content-center">
                            <div class="col-md-12 corpo p-2 px-3">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Nome</th>
                                            <th class="text-center">Cota</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($candidatos as $i => $candidato)
                                            <tr>
                                                <th class="align-middle"> {{$loop->iteration}}</th>
                                                <td class="align-middle">{{$candidato->candidato->user->name}}</td>
                                                <td class="align-middle text-center">{{$candidato->cota->cod_cota}}</td>
                                                <td class="align-middle text-center">
                                                    <div class="btn-group">
                                                        @if($candidato->candidato->user->email != null)
                                                            <img src="{{asset('img/g830.svg')}}" alt="..." width="25px" data-toggle="tooltip" data-placement="top" title="Primeiro acesso do candidato realizado">
                                                        @else
                                                            <img src="{{asset('img/Icon ionic-ios-person.svg')}}" alt="..." width="25px" data-toggle="tooltip" data-placement="top" title="Primeiro acesso do candidato não realizado">
                                                        @endif
                                                        @can('isAdminOrAnalistaGeral', auth()->user())
                                                            @if($candidato->status == \App\Models\Inscricao::STATUS_ENUM['documentos_aceitos_sem_pendencias'])
                                                                <img src="{{asset('img/g1365.svg')}}" alt="..." width="25px" data-toggle="tooltip" data-placement="top" title="Documentos aceitos">
                                                            @elseif($candidato->status == \App\Models\Inscricao::STATUS_ENUM['documentos_aceitos_com_pendencias'])
                                                                <img src="{{asset('img/g1193.svg')}}" alt="..." width="25px" data-toggle="tooltip" data-placement="top" title="Documentos aceitos com pendências">
                                                            @elseif($candidato->status == \App\Models\Inscricao::STATUS_ENUM['documentos_invalidados'])
                                                                <img src="{{asset('img/g1697.svg')}}" alt="..." width="25px" data-toggle="tooltip" data-placement="top" title="Documentos invalidados">
                                                            @elseif($candidato->status == \App\Models\Inscricao::STATUS_ENUM['documentos_enviados'])
                                                                <img src="{{asset('img/g1949.svg')}}" alt="..." width="25px" data-toggle="tooltip" data-placement="top" title="Documentos enviados">
                                                            @elseif($candidato->status == \App\Models\Inscricao::STATUS_ENUM['documentos_pendentes'])
                                                                <img src="{{asset('img/g2201.svg')}}" alt="..." width="25px" data-toggle="tooltip" data-placement="top" title="Documentos pendentes">
                                                            @endif
                                                        @endcan
                                                        @can('ehAnalistaHeteroidentificacaoOuMedico', auth()->user())
                                                            @if($candidato->status == \App\Models\Inscricao::STATUS_ENUM['documentos_aceitos_sem_pendencias'])
                                                                    <img src="{{asset('img/g1365.svg')}}" alt="..." width="25px" data-toggle="tooltip" data-placement="top" title="Documentos aceitos">
                                                            @elseif($concluidos->contains($candidato->id))
                                                                <img src="{{asset('img/g1365.svg')}}" alt="..." width="25px" data-toggle="tooltip" data-placement="top" title="Documentos aceitos">
                                                            @elseif($invalidados->contains($candidato->id))
                                                                <img src="{{asset('img/g1697.svg')}}" alt="..." width="25px" data-toggle="tooltip" data-placement="top" title="Documentos invalidados">
                                                            @elseif($candidato->status == \App\Models\Inscricao::STATUS_ENUM['documentos_enviados'])
                                                                <img src="{{asset('img/g1949.svg')}}" alt="..." width="25px" data-toggle="tooltip" data-placement="top" title="Documentos enviados">
                                                            @elseif($candidato->status == \App\Models\Inscricao::STATUS_ENUM['documentos_pendentes'])
                                                                <img src="{{asset('img/g2201.svg')}}" alt="..." width="25px" data-toggle="tooltip" data-placement="top" title="Documentos pendentes">
                                                            @endif
                                                        @endcan
                                                    </div>
                                                </td>
                                                <td class="align-middle text-center"><a class="btn btn-sm" href="{{route('inscricao.show.analisar.documentos', ['sisu_id' => $chamada->sisu->id, 'chamada_id' => $chamada->id, 'curso_id' => $curso->id, 'inscricao_id' => $candidato->id])}}" style="background-color: #1CE8B1; color: white; font-size: 14px; font-weight: bolder;">Avaliar</a></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <a href="{{route('chamadas.candidatos', ['sisu_id' => $chamada->sisu->id, 'chamada_id' => $chamada->id])}}" class="btn botao my-2 py-1" type="submit"> <span class="px-4">Voltar</span></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="col-md-12 shadow-sm p-2 px-3" style="background-color: white; border-radius: 00.5rem;">
                        <div style="font-size: 21px;" class="tituloModal">
                            Status
                        </div>
                        <ul class="list-group list-unstyled">
                            <li>
                                <div title="Primeiro acesso do candidato realizado" class="d-flex align-items-center listagemLista my-2 pt-1 pb-1">
                                    <img class="aling-middle" width="30" src="{{asset('img/g830.svg')}}" alt="icone-busca">
                                    <div style="font-size: 14px;" class="tituloLista aling-middle mx-3">
                                        Primeiro acesso do candidato realizado
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div title="Primeiro acesso do candidato não realizado" class="d-flex align-items-center listagemLista my-2 pt-1 pb-1">
                                    <img class="aling-middle" width="30" src="{{asset('img/Icon ionic-ios-person.svg')}}" alt="icone-busca">
                                    <div style="font-size: 13px;" class="tituloLista aling-middle mx-3">
                                        Primeiro acesso do candidato não realizado
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div title="Documentos aceitos sem pendências" class="d-flex align-items-center listagemLista my-2 pt-1 pb-1">
                                    <img class="aling-middle" width="30" src="{{asset('img/g1365.svg')}}" alt="icone-busca">
                                    <div style="font-size: 14px;" class="tituloLista aling-middle mx-3">
                                        Documentos aceitos sem pendências
                                    </div>
                                </div>
                            </li>
                            @can('isAdminOrAnalistaGeral', auth()->user())
                            <li>
                                <div title="Documentos aceitos com pendências" class="d-flex align-items-center listagemLista my-2 pt-1 pb-1">
                                    <img class="aling-middle" width="30" src="{{asset('img/g1193.svg')}}" alt="icone-busca">
                                    <div style="font-size: 14px;" class="tituloLista aling-middle mx-3">
                                        Documentos aceitos com pendências
                                    </div>
                                </div>
                            </li>
                            @endcan
                            <li>
                                <div title="Documentos enviados" class="d-flex align-items-center listagemLista my-2 pt-1 pb-1">
                                    <img class="aling-middle" width="30" src="{{asset('img/g1949.svg')}}" alt="icone-busca">
                                    <div style="font-size: 14px;" class="tituloLista aling-middle mx-3">
                                        Documentos enviados
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div title="Documentos pendentes" class="d-flex align-items-center listagemLista my-2 pt-1 pb-1">
                                    <img class="aling-middle" width="30" src="{{asset('img/g2201.svg')}}" alt="icone-busca">
                                    <div style="font-size: 14px;" class="tituloLista aling-middle mx-3">
                                        Documentos pendentes
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div title="Documentos invalidados" class="d-flex align-items-center listagemLista my-2 pt-1 pb-1">
                                    <img class="aling-middle" width="30" src="{{asset('img/g1697.svg')}}" alt="icone-busca">
                                    <div style="font-size: 14px;" class="tituloLista aling-middle mx-3">
                                        Documentos invalidados
                                    </div>
                                </div>
                            </li>

                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    $('#confirmModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget) // Botão que acionou o modal
        var recipient = button.data('id')
        var texto = button.data('texto')
        var nome = button.data('nome')
        var modal = $(this)
        modal.find('.modal-title').text('Deseja ' + texto + nome)
        modal.find('.modal-body input').val(recipient)
    });

    $('.link-ordenacao').click(function() {
        link = this.children[2].href;
        window.location = link;
    });
</script>
