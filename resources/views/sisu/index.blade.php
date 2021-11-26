<x-app-layout>
    <div class="fundo2 px-5">
        <div class="row justify-content-center">
            <div class="col-md-7 cabecalho p-2 px-3 align-items-center">
              <div class="row justify-content-between">
                <div class="d-flex align-items-center justify-content-between">
                  <div class="d-flex align-items-center">
                    <img src="{{asset('img/Grupo 1662.svg')}}"
                          alt="" width="40" class="img-flex">
                      <span class="tituloTabelas ps-1">Edições</span>
                  </div>
                  <a data-bs-toggle="modal" data-bs-target="#adicionarEdicao"><img width="35" src="{{asset('img/Grupo 1674.svg')}}"></a>
                </div>
              </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-7 corpo p-2 px-3">
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
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Nome</th>
                            <th class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sisus as $i => $sisu)
                            <tr>
                                <th class="align-middle"> {{$i+1}}</th>
                                <td class="align-middle">{{$sisu->edicao}}</td>
                                <td class="align-middle text-center">
                                    <a href="{{route('sisus.show', ['sisu' => $sisu->id])}}"><img class="m-1 " width="30" src="{{asset('img/Grupo 1681.svg')}}"  alt="icone-busca"></a>
                                    <a data-bs-toggle="modal" data-bs-target="#modalStaticDeletarSisu_{{$sisu->id}}" style="cursor: pointer;"><img class="m-1 " width="30" src="{{asset('img/Grupo 1664.svg')}}"  alt="icone-busca"></a>
                                    <a data-bs-toggle="modal" data-bs-target="#modalStaticEditarSisu_{{$sisu->id}}" style="cursor: pointer;"><img class="m-1 " width="30" src="{{asset('img/Grupo 1675.svg')}}"  alt="icone-busca"></a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
              <button class="btn botao my-2 py-1" type="submit"> <span class="px-4">Voltar</span></button>
            </div>
        </div>
    </div>

  <!--CORPO-->

  <!--MODAL CRIAR-->

    <div class="modal fade" id="adicionarEdicao" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content modalFundo p-3">
          <div class="col-md-12 tituloModal">Insira uma nova edição</div>

          <div class="col-md-12 pt-3 pb-2 textoModal">
            <form method="POST" id="criar-sisu" action="{{route('sisus.store')}}">
                @csrf
                <div class="form-row">
                    <div class="col-md-12 form-group">
                        <label class="pb-2" for="edicao"><span style="color: red; font-weight: bold;">* </span>Nome da edição:</label>
                        <input type="name" class="form-control campoDeTexto @error('edicao') is-invalid @enderror" id="nomeEdicao" placeholder="Insira o nome da edição"  name="edicao" required autofocus autocomplete="edicao">

                        @error('edicao')
                            <div id="validationServer03Feedback" class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
            </form>


          </div>

          <div class="row justify-content-between mt-4">
            <div class="col-md-3">
                <button type="button" class="btn botao my-2 py-1" data-bs-dismiss="modal"> <span class="px-4">Voltar</span></button>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn botaoVerde my-2 py-1" form="criar-sisu"><span class="px-4">Publicar</span></button>
            </div>
          </div>


        </div>
      </div>
    </div>

  <!--MODAL-->


    @foreach ($sisus as $sisu)
        <!-- Modal deletar sisu -->
        <div class="modal fade" id="modalStaticDeletarSisu_{{$sisu->id}}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-dialog">
                    <div class="modal-content modalFundo p-3">
                        <div class="col-md-12 tituloModal">Deletar edição</div>
                            <div class="pt-3 pb-2 textoModal">
                                <form id="deletar-sisu-form-{{$sisu->id}}" method="POST" action="{{route('sisus.destroy', ['sisu' => $sisu])}}">
                                    @csrf
                                    <input type="hidden" name="_method" value="DELETE">
                                    Tem certeza que deseja deletar a edição {{$sisu->edicao}}?
                                </form>
                                <div class="row justify-content-between mt-4">
                                    <div class="col-md-3">
                                        <button type="button" class="btn botao my-2 py-1" data-bs-dismiss="modal"><span class="px-4">Cancelar</span></button>
                                    </div>
                                    <div class="col-md-4">
                                        <button type="submit" class="btn botaoVerde my-2 py-1" form="deletar-sisu-form-{{$sisu->id}}" style="background-color: #FC605F;"><span class="px-4">Excluir</span></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    @endforeach

    @foreach ($sisus as $sisu)
        <!-- Modal editar sisu -->
        <div class="modal fade" id="modalStaticEditarSisu_{{$sisu->id}}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-dialog">
                    <div class="modal-content modalFundo p-3">
                        <div class="col-md-12 tituloModal">Editar edição</div>
                            <div class="pt-3 pb-2 textoModal">
                                <form method="POST" id="editar-sisu-form-{{$sisu->id}}" action="{{route('sisus.update', $sisu->id)}}">
                                    @csrf
                                    <input type="hidden" name="_method" value="PUT">
                                    <div class="form-row">
                                        <div class="col-md-6 form-group">
                                            <label class="pb-2" for="edicao"><span style="color: red; font-weight: bold;">* </span>{{ __('Nome da edição:') }}</label>
                                            <input id="edicao" class="form-control campoDeTexto @error('edicao') is-invalid @enderror" type="text" name="edicao" value="{{old('edicao')!=null ? old('edicao') : $sisu->edicao}}" required autofocus autocomplete="edicao">

                                            @error('edicao')
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
                                        <button type="submit" class="btn botaoVerde my-2 py-1" form="editar-sisu-form-{{$sisu->id}}"><span class="px-4">Editar</span></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    @endforeach

</x-app-layout>
