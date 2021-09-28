<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Candidatos do curso {{$curso->nome}}
        </h2>
    </x-slot>
    <div class="container" style="padding-top: 5rem; padding-bottom: 8rem;">
        <div class="form-row justify-content-center">
            <div class="col-md-10">
                <div class="card" style="width: 100%;">
                    <div class="card-body">
                        <div class="form-row">
                            <div class="col-md-8">
                                <h5 class="card-title">Candidatos do curso {{$curso->nome}} da {{$chamada->nome}} da edição {{$chamada->sisu->edicao}} cadastrados</h5>
                                <h6 class="card-subtitle mb-2 text-muted">Candidatos</h6>
                            </div>
                        </div>
                        <div div class="form-row">
                            @if(session('success'))
                                <div class="col-md-12" style="margin-top: 5px;">
                                    <div class="alert alert-success" role="alert">
                                        <p>{{session('success')}}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col" style="text-align: center">Nome</th>
                                        <th scope="col" style="text-align: center">Status</th>
                                        <th scope="col" style="text-align: center">Ações</th>
                                        <th scope="col" style="text-align: center">Efetivado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($candidatos as $i => $candidato)
                                        <tr>
                                            <td>{{$i+1}}</td>
                                            <td style="text-align: center">{{$candidato->candidato->user->name}}</td>
                                            <td style="text-align: center">
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
                                            <td style="text-align: center"><a class="btn btn-primary" href="{{route('inscricao.show.analisar.documentos', ['sisu_id' => $chamada->sisu->id, 'chamada_id' => $chamada->id, 'curso_id' => $curso->id, 'inscricao_id' => $candidato->id])}}">Analisar documentos</a></td>
                                            <td style="text-align: center">
                                                <div class="btn-group">
                                                    <form method="post" action="{{route('inscricao.status.efetivado',['sisu_id' => $chamada->sisu->id, 'chamada_id' => $chamada->id, 'curso_id' => $curso->id])}}">
                                                        @csrf
                                                        <input type="hidden" name="inscricaoID" value="{{$candidato->id}}"/>
                                                        @if($candidato->cd_efetivado == true)
                                                            <button  type="submit">
                                                                <img src="{{asset('img/icon_aprovado_verde.svg')}}" alt="..." width="25px" data-toggle="tooltip" data-placement="top" title="Candidato efetivado">
                                                            </button>
                                                        @else
                                                            <button  type="submit">
                                                                <img src="{{asset('img/icon_reprovado_vermelho.svg')}}" alt="..." width="25px"  autodata-toggle="tooltip" data-placement="top" title="Candidato não efetivado">
                                                            </button>
                                                        @endif
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>