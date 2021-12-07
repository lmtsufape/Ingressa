<x-app-layout>
    <div class="fundo2 px-5">
        <div class="row justify-content-center" >
            <div class="col-md-9 p-0" style="text-align: right">
                <a class="btn botao my-2 py-1" href="{{route('chamadas.candidatos', ['sisu_id' => $chamada->sisu->id, 'chamada_id' => $chamada->id])}}"> <span class="px-4">Voltar</span></a>
            </div>
            <div class="col-md-9 cabecalho p-2 px-3 align-items-center"  style="background-color: {{$curso->cor_padrao != null ? $curso->cor_padrao : 'black'}}">
                <div class="row justify-content-between" >
                    <div class="d-flex align-items-center justify-content-between" >
                        <div class="d-flex align-items-center">
                            <img style="border:2px solid white; border-radius: 50%;"  src="{{asset('storage/'.$curso->icone)}}"
                            alt="" width="40" class="img-flex">
                            <span class="tituloTabelas ps-1">{{$curso->nome}} - {{$turno}}</span>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-9 corpo p-2 px-3">
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
                @error('error')
                    <div class="alert alert-danger" role="alert">
                        {{$message}}
                    </div>
                @enderror
                <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">
                                    Pessoa<a href="{{route('chamadas.candidatos.curso', ['sisu_id' => $sisu->id, 'chamada_id' => $chamada->id, 'curso_id' => $curso->id])}}?ordem=name" style="cursor: pointer; text-decoration: none;">
                                        <img class="icon_order_table" src="{{asset('img/Icon ionic-ios-arrow-dropright-circle.svg')}}" alt="Ordenar por nome" style="background-color: {{$curso->cor_padrao != null ? $curso->cor_padrao : 'black'}}">
                                    </a>
                                </th>
                                <th scope="col">
                                    Cota<a href="{{route('chamadas.candidatos.curso', ['sisu_id' => $sisu->id, 'chamada_id' => $chamada->id, 'curso_id' => $curso->id])}}?ordem=cota" style="cursor: pointer; text-decoration: none;">
                                        <img class="icon_order_table" src="{{asset('img/Icon ionic-ios-arrow-dropright-circle.svg')}}" alt="Ordenar por nome" style="background-color: {{$curso->cor_padrao != null ? $curso->cor_padrao : 'black'}}">
                                    </a>
                                </th>
                                <th scope="col">
                                    Status<a href="{{route('chamadas.candidatos.curso', ['sisu_id' => $sisu->id, 'chamada_id' => $chamada->id, 'curso_id' => $curso->id])}}?ordem=status" style="cursor: pointer; text-decoration: none;">
                                        <img class="icon_order_table" src="{{asset('img/Icon ionic-ios-arrow-dropright-circle.svg')}}" alt="Ordenar por nome" style="background-color: {{$curso->cor_padrao != null ? $curso->cor_padrao : 'black'}}">
                                    </a>
                                </th>
                                <th scope="col" class="text-center">Ação</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($candidatos as $i => $candidato)
                                <tr>
                                    <th class="align-middle"> {{$i+1}}</th>
                                    <td class="align-middle">{{$candidato->candidato->user->name}}</td>
                                    <td class="align-middle">{{$candidato->cota->cod_cota}}</td>
                                    <td class="align-middle">
                                        <div class="btn-group">
                                            @if($candidato->candidato->user->email != null)
                                                <img src="{{asset('img/icon_aprovado_verde.svg')}}" alt="..." width="25px" data-toggle="tooltip" data-placement="top" title="Cadastro do candidato concluído">
                                            @else
                                                <img src="{{asset('img/icon_reprovado_vermelho.svg')}}" alt="..." width="25px" data-toggle="tooltip" data-placement="top" title="Cadastro do candidato não concluído">
                                            @endif

                                            @if($candidato->status == \App\Models\Inscricao::STATUS_ENUM['documentos_aceitos'])
                                                <img src="{{asset('img/icons-document-blue.png')}}" alt="..." width="25px" data-toggle="tooltip" data-placement="top" title="Documentos aceitos">
                                            @elseif($candidato->status == \App\Models\Inscricao::STATUS_ENUM['documentos_enviados'])
                                                <img src="{{asset('img/icons-document-yellow.png')}}" alt="..." width="25px" data-toggle="tooltip" data-placement="top" title="Documentos enviados">
                                            @elseif($candidato->status == \App\Models\Inscricao::STATUS_ENUM['documentos_requeridos'])
                                                <img src="{{asset('img/icons-document-red.png')}}" alt="..." width="25px" data-toggle="tooltip" data-placement="top" title="Documentos requeridos">
                                            @endif
                                        </div>
                                    </td>
                                    <td class="align-middle text-center"><a class="btn botaoVerde" href="{{route('inscricao.show.analisar.documentos', ['sisu_id' => $chamada->sisu->id, 'chamada_id' => $chamada->id, 'curso_id' => $curso->id, 'inscricao_id' => $candidato->id])}}">Avaliar</a></td>
                                </tr>
                            @endforeach
                        </tbody>
                </table>
                <div class="row justify-content-between mt-4">
                    <div class="col-md-12">
                        <strong>Legenda:</strong>
                    </div>
                    <div class="col-md-6">
                        <ul class="list-group">
                            <li class="list-group-item"><img src="{{asset('img/icon_aprovado_verde.svg')}}" alt="..." width="25px" data-toggle="tooltip" data-placement="top" title="Cadastro do candidato concluído"> Cadastro do candidato concluído</li>
                            <li class="list-group-item"><img src="{{asset('img/icon_reprovado_vermelho.svg')}}" alt="..." width="25px" data-toggle="tooltip" data-placement="top" title="Cadastro do candidato não concluído"> Cadastro do candidato não concluído</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <ul class="list-group">
                            <li class="list-group-item"><img src="{{asset('img/icons-document-blue.png')}}" alt="..." width="25px" data-toggle="tooltip" data-placement="top" title="Documentos aceitos"> Documentos aceitos</li>
                            <li class="list-group-item"><img src="{{asset('img/icons-document-yellow.png')}}" alt="..." width="25px" data-toggle="tooltip" data-placement="top" title="Documentos enviados"> Documentos enviados pelos candidatos</li>
                            <li class="list-group-item"><img src="{{asset('img/icons-document-red.png')}}" alt="..." width="25px" data-toggle="tooltip" data-placement="top" title="Documentos requeridos"> Documentos requeridos</li>
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
    })
</script>
