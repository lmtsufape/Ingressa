<x-app-layout>
    <div class="container" style="padding-top: 3rem;  ">
        <div class="form-row justify-content-left">
            <div class="col-md-4">
                <div class="card" style="width: 100%;">
                    <div class="card-body">
                        <div class="form-row">
                            <div class="col-md-12">
                                <div class="btn-group">
                                    <h5 class="card-title" style="color:#1492E6; font-weight: bold;">Datas importantes da {{$chamada->nome}} da edição {{$chamada->sisu->edicao}}</h5>
                                    <a data-toggle="modal" data-target="#modalStaticCriarData_{{$chamada->id}}"><img src="{{ asset('img/icon_adicionar.png') }}" alt="Inserir nova data" width="50.5px" ></a>
                                </div>
                            </div>
                        </div>
                        <div div class="form-row">
                            @if(session('success_data'))
                                <div class="col-md-12" style="margin-top: 5px;">
                                    <div class="alert alert-success" role="alert">
                                        <p>{{session('success_data')}}</p>
                                    </div>
                                </div>
                            @endif
                        </div>

                        @if ($datas->first() != null)
                            <table cellspacing="0" cellpadding="1"width="100%" >
                                <tbody>
                                    <div div class="form-row">
                                    @foreach ($datas as $data)
                                        <div class="col-md-12">
                                            <div class="d-flex justify-content-left align-items-center">
                                                <div style="margin-right:10px; margin-top:-20px">
                                                    @if ($data->tipo == $tipos['convocacao'])
                                                        <img class="" src="{{asset('img/icon_convocacao.png')}}" alt="" width="40px">
                                                    @elseif($data->tipo == $tipos['envio'])
                                                        <img class="" src="{{asset('img/icon_envio.png')}}" alt="" width="40px">
                                                    @elseif($data->tipo == $tipos['resultado'])
                                                        <img class="" src="{{asset('img/icon_resultado.png')}}" alt="" width="40px">
                                                    @endif
                                                </div>
                                                <div class="form-group">
                                                    <div style="margin-bottom: -8px;"><h5 style=" font-size:17px; font-weight: bold;">{{$data->titulo}}</h5></div>
                                                    <div><h5 style="font-size:15px; font-weight: normal; color:#909090">{{date('d/m/Y',strtotime($data->data_inicio))}} - {{date('d/m/Y',strtotime($data->data_fim))}}</h5></div>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                    @endforeach
                                </div>
                                </tbody>
                            </table>
                        @else

                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="form-row">
                    <div class="col-md-10">
                        <h2 class="card-title">Listagens</h2>
                    </div>
                </div>
                <div class="card" style="width: 100%;">
                    <div class="card-body">
                        <div div class="form-row">
                            @if(session('success_listagem'))
                                <div class="col-md-12" style="margin-top: 5px;">
                                    <div class="alert alert-success" role="alert">
                                        <p>{{session('success_listagem')}}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                        @if ($datas->first() != null)
                            <table cellspacing="0" cellpadding="1"width="100%" >
                                <tbody>
                                    <div div class="form-row">
                                    @foreach ($datas as $data)
                                        <div class="col-md-12">
                                            <div class="d-flex justify-content-left align-items-center">
                                                <div style="margin-right:10px; margin-top:-20px">
                                                    @if ($data->tipo == $tipos['convocacao'])
                                                        <img class="" src="{{asset('img/icon_convocacao.png')}}" alt="" width="40px">
                                                    @elseif($data->tipo == $tipos['envio'])
                                                        <img class="" src="{{asset('img/icon_envio.png')}}" alt="" width="40px">
                                                    @elseif($data->tipo == $tipos['resultado'])
                                                        <img class="" src="{{asset('img/icon_resultado.png')}}" alt="" width="40px">
                                                    @endif
                                                </div>
                                                <div class="form-group">
                                                    <div style="margin-bottom: -8px;"><h5 style=" font-size:17px; font-weight: bold;">{{$data->titulo}}</h5></div>
                                                    <div><h5 style="font-size:15px; font-weight: normal; color:#909090">{{date('d/m/Y',strtotime($data->data_inicio))}} - {{date('d/m/Y',strtotime($data->data_fim))}}</h5></div>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                    @endforeach
                                </div>
                                </tbody>
                            </table>
                        @else

                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>


    <!-- Modal criar data -->
    <div class="modal fade" id="modalStaticCriarData_{{$chamada->id}}" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #ffffff00;">
                    <h5 class="modal-title" id="staticBackdropLabel" style="color: rgb(0, 142, 185);">Insira uma nova data</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="criar-data-form" method="POST" action="{{route('datas.store')}}">
                        @csrf
                        <input type="hidden" name="chamada" value="{{$chamada->id}}">
                        <div class="form-row">
                            <div class="col-sm-12 form-group">
                                <label for="titulo">{{__('Título da data')}}</label>
                                <input type="text" id="titulo" name="titulo" class="form-control @error('titulo') is-invalid @enderror" value="{{old('titulo')}}" autofocus required>

                                @error('titulo')
                                    <div id="validationServer03Feedback" class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-sm-8 form-group">
                                <label for="tipo">{{__('Tipo da data')}}</label>
                                <select name="tipo" id="tipo" class="form-control @error('tipo') is-invalid @enderror" required>
                                    <option value="" selected disabled>-- Selecione o tipo da data --</option>
                                    <option @if(old('tipo') == $tipos['convocacao']) selected @endif value="{{$tipos['convocacao']}}">Convocação</option>
                                    <option @if(old('tipo') == $tipos['envio']) selected @endif value="{{$tipos['envio']}}">Envio de documentos</option>
                                    <option @if(old('tipo') == $tipos['resultado']) selected @endif value="{{$tipos['resultado']}}">Resultado</option>
                                </select>

                                @error('tipo')
                                    <div id="validationServer03Feedback" class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-sm-6 form-group">
                                <label for="data_inicio">{{ __('Data de início') }} </label>
                                <input type="date" @error('data_inicio') is-invalid @enderror id="data_inicio" name="data_inicio" required autofocus autocomplete="data_inicio">

                                @error('data_inicio')
                                    <div id="validationServer03Feedback" class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-sm-6 form-group">
                                <label for="data_fim">{{ __('Data de fim') }} </label>
                                <input type="date" @error('data_fim') is-invalid @enderror id="data_fim" name="data_fim" required autofocus autocomplete="data_fim">

                                @error('data_fim')
                                    <div id="validationServer03Feedback" class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Voltar</button>
                    <button type="submit" class="btn btn-success" form="criar-data-form">Publicar</button>
                </div>
            </div>
        </div>
    </div>

    @foreach ($datas as $data)
        <!-- Modal deletar data -->
        <div class="modal fade" id="modalStaticDeletarData_{{$data->id}}" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header" style="background-color: #dc3545;">
                        <h5 class="modal-title" id="staticBackdropLabel" style="color: white;">Confirmação</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="deletar-data-form-{{$data->id}}" method="POST" action="{{route('datas.destroy', ['data' => $data])}}">
                            @csrf
                            <input type="hidden" name="_method" value="DELETE">
                            Tem certeza que deseja deletar a data {{$data->titulo}}?
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger" form="deletar-data-form-{{$data->id}}">Sim</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    {{--@foreach ($chamadas as $chamada)
        <!-- Modal importar candidatos da chamada -->
        <div class="modal fade" id="modalStaticImportarCandidatos_{{$chamada->id}}" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header" style="background-color: #28a745;">
                        <h5 class="modal-title" id="staticBackdropLabel" style="color: white;">Importar Candidatos</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="cadastrar-candidatos-chamada-form-{{$chamada->id}}" method="POST" action="{{route('chamadas.importar.candidatos', ['sisu_id' =>$sisu->id, 'chamada_id' => $chamada->id])}}" enctype="multipart/form-data">
                            @csrf
                            <input type="file" name="arquivo" accept=".csv" required><br>
                            Anexe o arquivo .csv da chamada {{$chamada->nome}} da edição {{$sisu->edicao}}.
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success" form="cadastrar-candidatos-chamada-form-{{$chamada->id}}" id="submeterFormBotao">Importar</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach--}}
</x-app-layout>
