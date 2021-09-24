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
                                            <div class="d-flex justify-content-left align-items-center" data-toggle="modal" data-target="#modalStaticEditarData_{{$data->id}}">
                                                <div style="margin-right:10px; margin-top:-20px">
                                                    @if ($data->tipo == $tipos['convocacao'])
                                                        <a ><img class="" src="{{asset('img/icon_convocacao.png')}}" alt="" width="40px"></a>
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
                                            <button class="btn btn-danger" data-toggle="modal" data-target="#modalStaticDeletarData_{{$data->id}}">x</button>
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
                        <div class="btn-group">
                            <h2 class="card-title">Listagens</h2>
                            <a data-toggle="modal" data-target="#modalStaticCriarListagem"><img src="{{ asset('img/icon_adicionar.png') }}" alt="Inserir nova listagem" width="30.5px" ></a>
                        </div>
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
                        @if ($listagens->first() != null)
                            <table cellspacing="0" cellpadding="1"width="100%" >
                                <tbody>
                                    <div div class="form-row">
                                        @foreach ($listagens as $listagem)
                                            <div class="col-md-12">
                                                <div class="d-flex justify-content-left align-items-center">
                                                    <div class="form-group">
                                                        <div style="margin-bottom: -8px;"><h5 style=" font-size:17px; font-weight: bold;">{{$listagem->titulo}}</h5></div>
                                                        <div><h5 style="font-size:15px; font-weight: normal; color:#909090">{{date('d/m/Y',strtotime($data->data_inicio))}} - {{date('d/m/Y',strtotime($data->data_fim))}}</h5></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <button class="btn btn-danger" data-toggle="modal" data-target="#modalStaticDeletarListagem_{{$listagem->id}}">x</button>
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

        <!-- Modal editar data -->
        <div class="modal fade" id="modalStaticEditarData_{{$data->id}}" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header" style="background-color: #3591dc;">
                        <h5 class="modal-title" id="staticBackdropLabel" style="color: white;">Editar data</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="editar-data-form-{{$data->id}}" method="POST" action="{{route('datas.update', ['data' => $data])}}">
                            @csrf
                            <input type="hidden" name="_method" value="PUT">
                            <div class="form-row">
                                <div class="col-sm-12 form-group">
                                    <label for="titulo">{{__('Título da data')}}</label>
                                    <input type="text" id="titulo" name="titulo" class="form-control @error('titulo') is-invalid @enderror" value="{{old('titulo')!=null ? old('titulo') : $data->titulo}}" required autofocus autocomplete="titulo">

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
                                        <option value="{{$data->id}}" selected >@if ($data->tipo == $tipos['convocacao']) Convocação @elseif($data->tipo == $tipos['envio']) Envio de documentos @elseif($data->tipo == $tipos['resultado']) Resultado @endif</option>
                                        @if ($data->tipo != $tipos['convocacao'])
                                            <option @if(old('tipo') == $tipos['convocacao']) selected @endif value="{{$tipos['convocacao']}}">Convocação</option>

                                        @endif
                                        @if ($data->tipo != $tipos['envio'])
                                            <option @if(old('tipo') == $tipos['envio']) selected @endif value="{{$tipos['envio']}}">Envio de documentos</option>
                                        @endif
                                        @if ($data->tipo != $tipos['resultado'])
                                            <option @if(old('tipo') == $tipos['resultado']) selected @endif value="{{$tipos['resultado']}}">Resultado</option>
                                        @endif
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
                                    <input type="date" @error('data_inicio') is-invalid @enderror id="data_inicio" name="data_inicio" value="{{old('data_inicio')!=null ? old('data_inicio') : $data->data_inicio}}" required autofocus autocomplete="data_inicio">

                                    @error('data_inicio')
                                        <div id="validationServer03Feedback" class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-sm-6 form-group">
                                    <label for="data_fim">{{ __('Data de fim') }} </label>
                                    <input type="date" @error('data_fim') is-invalid @enderror id="data_fim" name="data_fim" required autofocus autocomplete="data_fim" value="{{old('data_fim')!=null ? old('data_fim') : $data->data_fim}}" required autofocus autocomplete="data_fim">

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
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success" form="editar-data-form-{{$data->id}}">Editar</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

   <!-- Modal criar listagem -->
   <div class="modal fade" id="modalStaticCriarListagem" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #ffffff00;">
                    <h5 class="modal-title" id="staticBackdropLabel" style="color: rgb(0, 142, 185);">Insira uma nova listagem</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="criar-listagem-form" method="POST" action="{{route('listagems.store')}}">
                        @csrf
                        <input type="hidden" name="chamada" value="{{$chamada->id}}">
                        <div class="form-row">
                            <div class="col-sm-12 form-group">
                                <label for="titulo">{{__('Título da listagem')}}</label>
                                <input type="text" id="titulo" name="titulo" class="form-control @error('titulo') is-invalid @enderror" value="{{old('titulo')}}" autofocus required>

                                @error('titulo')
                                    <div id="validationServer03Feedback" class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-row">
                            <label for="tipo">{{__('Selecione o tipo')}}</label>
                            <select name="tipo" id="tipo" class="form-control @error('tipo') is-invalid @enderror" required>
                                <option value="" selected disabled>-- Selecione o tipo da listagem --</option>
                                <option @if(old('tipo') == $tipos['convocacao']) selected @endif value="{{$tipos['convocacao']}}">Convocação</option>
                                <option @if(old('tipo') == $tipos['resultado']) selected @endif value="{{$tipos['resultado']}}">Resultado</option>
                            </select>

                            @error('tipo')
                                <div id="validationServer03Feedback" class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <br>

                        <div class="accordion" id="cursosHeading">
                            <div class="card">
                                <div class="card-header" id="headingOne">
                                    <h2 class="mb-0">
                                        <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                            Cursos
                                        </button>
                                    </h2>
                                </div>

                                <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#cursosHeading">
                                    <div class="card-body">
                                        <div class="form-row">
                                            <label for="curso">{{__('Selecione o(s) curso(s)')}}</label>
                                            <div class="col-sm-12 form-group">
                                                <div class="form-check">
                                                    <input type="checkbox" id="chk_marcar_desmarcar_todos_cursos" onclick="marcar_desmarcar_todos_checkbox_por_classe(this, 'checkbox_curso')">
                                                    <label for="btn_marcar_desmarcar_todos_cursos"><b>Selecionar todos</b></label>
                                                </div>
                                            </div>
                                            @foreach ($cursos as $curso)
                                                <div class="col-sm-12 form-group">
                                                    <div class="form-check">
                                                        <input class="checkbox_curso" type="checkbox" name="cursos[]" value="{{$curso->id}}" id="curso_{{$curso->id}}">
                                                        <label class="form-check-label" for="curso_{{$curso->id}}">
                                                            {{$curso->nome}}
                                                        </label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="accordion" id="cotasHeading">
                            <div class="card">
                                <div class="card-header" id="headingTwo">
                                    <h2 class="mb-0">
                                        <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                                            Cotas
                                        </button>
                                    </h2>
                                </div>

                                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#cotasHeading">
                                    <div class="card-body">
                                        <div class="form-row">
                                            <label for="cota">{{__('Selecione a(s) cota(s)')}}</label>
                                            <div class="col-sm-12 form-group">
                                                <div class="form-check">
                                                    <input type="checkbox" id="chk_marcar_desmarcar_todas_cotas" onclick="marcar_desmarcar_todos_checkbox_por_classe(this, 'checkbox_cota')">
                                                    <label for="btn_marcar_desmarcar_todas_cotas"><b>Selecionar todas</b></label>
                                                </div>
                                            </div>
                                            @foreach ($cotas as $cota)
                                                <div class="col-sm-12 form-group">
                                                    <div class="form-check">
                                                        <input class="checkbox_cota" type="checkbox" name="cotas[]" value="{{$cota->id}}" id="cota_{{$cota->id}}">
                                                        <label class="form-check-label" for="cota_{{$cota->id}}">
                                                            {{$cota->nome}}
                                                        </label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Voltar</button>
                    <button type="submit" class="btn btn-success" form="criar-listagem-form">Publicar</button>
                </div>
            </div>
        </div>
    </div>
    @foreach ($listagens as $listagem)
        <!-- Modal deletar listagem -->
        <div class="modal fade" id="modalStaticDeletarListagem_{{$listagem->id}}" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header" style="background-color: #dc3545;">
                        <h5 class="modal-title" id="staticBackdropLabel" style="color: white;">Confirmação</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="deletar-listagem-form-{{$listagem->id}}" method="POST" action="{{route('listagems.destroy', ['listagem' => $listagem])}}">
                            @csrf
                            <input type="hidden" name="_method" value="DELETE">
                            Tem certeza que deseja deletar a listagem {{$listagem->titulo}}?
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger" form="deletar-listagem-form-{{$listagem->id}}">Sim</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</x-app-layout>
<script src="{{ asset('js/checkbox_marcar_todos.js') }}" defer></script>
