<x-app-layout>
    <div class="fundo2 px-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="row justify-content-center">
                    <div class="col-md-11 cabecalho p-2 px-3 align-items-center">
                        <div class="row">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <img src="{{asset('img/Grupo 1662.svg')}}"
                                        alt="" width="40" class="img-flex">
                                    <span class="tituloTabelas ps-1">Edições</span>
                                </div>
                                <div class="col-md-4" style="text-align: right">
                                    <button title="Adicionar nova edição do sisu" data-bs-toggle="modal" data-bs-target="#adicionarEdicao" style="cursor: pointer;"><img width="35" src="{{asset('img/Grupo 1674.svg')}}" alt="Icone de adicionar nova edicao"></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row justify-content-center" style="margin-bottom: 20px;">
                    <div class="col-md-11 corpo p-2 px-3">
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
                                            @if ($sisu->caminho_import_regular == null)
                                                <button title ="Importar lista regular" data-bs-toggle="modal" data-bs-target="#modalStaticImportarCandidatos_{{$sisu->id}}" style="cursor: pointer;"><img class="m-1 " width="30" src="{{asset('img/Grupo 1683.svg')}}"  alt="Icone de importar lista"></button>
                                            @else
                                                <a title="Exibir chamadas da edição" href="{{route('sisus.show', ['sisu' => $sisu->id])}}"><img class="m-1 " width="30" src="{{asset('img/Grupo 1681.svg')}}"  alt="Icone de exibir chamadas"></a>
                                            @endif
                                            @if ($sisu->caminho_import_regular != null)
                                                @if($sisu->caminho_import_espera == null)
                                                    <button title ="Importar lista de espera" data-bs-toggle="modal" data-bs-target="#modalStaticImportarCandidatos_{{$sisu->id}}_espera" style="cursor: pointer;"><img class="m-1 " width="30" src="{{asset('img/Grupo 1683.svg')}}"  alt="Icone de importar lista"></button>
                                                @endif
                                            @endif
                                            <button title="Deletar edição do sisu" data-bs-toggle="modal" data-bs-target="#modalStaticDeletarSisu_{{$sisu->id}}" style="cursor: pointer;"><img class="m-1 " width="30" src="{{asset('img/Grupo 1664.svg')}}"  alt="Icone de deletar edicao"></button>
                                            <button title="Editar edição do sisu" data-bs-toggle="modal" data-bs-target="#modalStaticEditarSisu_{{$sisu->id}}" style="cursor: pointer;"><img class="m-1 " width="30" src="{{asset('img/Grupo 1675.svg')}}"  alt="Icone de editar edicao"></button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <a href="{{route('dashboard')}}" class="btn botao my-2 py-1"> <span class="px-4">Voltar</span></a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="row justify-content-start">
                    <div class="col-md-12 shadow-sm p-2 px-3" style="background-color: white; border-radius: 00.5rem;">
                        <div style="font-size: 21px;" class="tituloModal">
                            Legenda
                        </div>
                        <ul class="list-group list-unstyled">
                            <li>
                                <div title="Exibir chamadas da edição" class="d-flex align-items-center listagemLista my-1 pt-1 pb-1">
                                    <img class="aling-middle" width="33" src="{{asset('img/Grupo 1681.svg')}}" alt="Icone de exibir chamadas">
                                    <div style="font-size: 13px;" class="tituloLista aling-middle mx-3">
                                        Exibir chamadas da edição
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div title="Importar lista de candidatos regulares/espera" class="d-flex align-items-center listagemLista my-1 pt-1 pb-1">
                                    <img class="aling-middle" width="33" src="{{asset('img/Grupo 1683.svg')}}" alt="Icone de importar lista de candidatos regulares/espera">
                                    <div style="font-size: 13px;" class="tituloLista aling-middle mx-3">
                                        Importar lista de candidatos regulares/espera
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div title="Deletar edição" class="d-flex align-items-center listagemLista my-1 pt-1 pb-1">
                                    <img class="aling-middle" width="33" src="{{asset('img/Grupo 1664.svg')}}" alt="Icone de deletar edição">
                                    <div style="font-size: 13px;" class="tituloLista aling-middle mx-3">
                                        Deletar edição
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div title="Editar edição" class="d-flex align-items-center listagemLista my-1 pt-1 pb-1">
                                    <img class="aling-middle" width="33" src="{{asset('img/Grupo 1665.svg')}}" alt="Icone de editar edição">
                                    <div style="font-size: 13px;" class="tituloLista aling-middle mx-3">
                                        Editar edição
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
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
                        <label class="pb-2" for="nomeEdicao"><span style="color: red; font-weight: bold;">* </span>Nome da edição:</label>
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
                                        <div class="col-md-12 form-group">
                                            <label class="pb-2" for="edicao"><span style="color: red; font-weight: bold;">* </span>{{ __('Nome da edição:') }}</label>
                                            <input id="edicao" class="form-control campoDeTexto @error('edicao') is-invalid @enderror" type="text" name="edicao" value="{{old('edicao')!=null ? old('edicao') : $sisu->edicao}}" required placeholder="Insira o nome da edição" autocomplete="edicao">

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


    @foreach ($sisus as $sisu)
        <!-- Modal importar candidatos da chamada -->
        <div class="modal fade" id="modalStaticImportarCandidatos_{{$sisu->id}}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content modalFundo p-3">
                    <div class="col-md-12 tituloModal" id="staticBackdropLabel">Importar lista regular</div>
                    <div class="modal-body textoModal">
                        <form id="importar-candidatos-sisu-form-{{$sisu->id}}-espera" method="POST" action="{{route('chamadas.importar.planilhas.regular', ['sisu_id' =>$sisu->id])}}" enctype="multipart/form-data">
                            @csrf
                            <div class="form-row">
                                <div class="col-md-12">
                                    <label for="arquivoRegular" class="form-label">Anexe o arquivo .csv da <strong>chamada regular</strong> da edição {{$sisu->edicao}}</label>
                                    <input id="arquivoRegular" type="file" class="form-control" name="arquivoRegular" accept=".csv" required><br>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="row justify-content-between mt-4">
                        <div class="col-md-3">
                            <button type="button" class="btn botao my-2 py-1" data-bs-dismiss="modal"><span class="px-4">Cancelar</span></button>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn botaoVerde my-2 py-1" form="importar-candidatos-sisu-form-{{$sisu->id}}-espera" id="submeterFormBotao"><span class="px-4">Importar</span></button>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="modal fade" id="modalStaticImportarCandidatos_{{$sisu->id}}_espera" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content modalFundo p-3">
                    <div class="col-md-12 tituloModal" id="staticBackdropLabel">Importar lista de espera</div>
                    <div class="modal-body textoModal">
                        <form id="importar-candidatos-sisu-form-{{$sisu->id}}" method="POST" action="{{route('chamadas.importar.planilhas.espera', ['sisu_id' =>$sisu->id])}}" enctype="multipart/form-data">
                            @csrf
                            <div class="form-row">
                                <div class="col-md-12">
                                    <label for="arquivoEspera" class="form-label">Anexe o arquivo .csv da <strong>lista de espera</strong> da edição {{$sisu->edicao}}</label>
                                    <input id="arquivoEspera" type="file" class="form-control" name="arquivoEspera" accept=".csv" required>
                                </div>
                            </div>

                        </form>
                    </div>
                    <div class="row justify-content-between mt-4">
                        <div class="col-md-3">
                            <button type="button" class="btn botao my-2 py-1" data-bs-dismiss="modal"><span class="px-4">Cancelar</span></button>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn botaoVerde my-2 py-1" form="importar-candidatos-sisu-form-{{$sisu->id}}" id="submeterFormBotao"><span class="px-4">Importar</span></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

</x-app-layout>
