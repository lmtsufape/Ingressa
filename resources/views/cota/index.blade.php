<x-app-layout>
    {{-- <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Cotas') }}
        </h2>
    </x-slot> --}}
    <div class="fundo2 px-5">
        <div class="row justify-content-center">
            <div class="col-md-4 cabecalho p-2 px-3 align-items-center">
              <div class="row justify-content-between">
                <div class="d-flex align-items-center justify-content-between">
                  <div class="d-flex align-items-center">
                    <img src="{{asset('img/Grupo 1662.svg')}}"
                          alt="" width="40" class="img-flex">
                      <span class="tituloTabelas ps-1">Cotas</span>
                  </div>
                    <a data-bs-toggle="modal" data-bs-target="#criar-cota" style="cursor: pointer;"><img width="35" src="{{asset('img/Grupo 1663.svg')}}"></a>
                </div>
              </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-4 corpo p-2 px-3">
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
                        <th scope="col">Código</th>
                        <th class="text-center">Ações</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach ($cotas as $i => $cota)
                        <tr>
                            <th class="align-middle">{{$i+1}}</th>
                            <td class="align-middle">{{$cota->cod_cota}}</td>
                            <td class="align-middle text-center">
                            <a id="criar-cota-btn" data-bs-toggle="modal" data-bs-target="#delete-cota-{{$cota->id}}" style="cursor: pointer;"><img class="m-1 " width="30" src="{{asset('img/Grupo 1664.svg')}}" alt="icone-busca"></a>
                            <a onclick="editarCota({{$cota->id}})" data-bs-toggle="modal" data-bs-target="#editar-cota" style="cursor: pointer;"><img class="m-1" width="30" src="{{asset('img/Grupo 1665.svg')}}" alt="icone-busca"></a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
              {{-- <button class="btn botao my-2 py-1" type="submit"> <span class="px-4">Voltar</span></button> --}}
            </div>
        </div>
    </div>

    <div class="modal fade" id="criar-cota" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #53F2C7;">
                    <h5 class="modal-title" id="staticBackdropLabel" style="color: white;">Criar cota</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="criar-cota-form" method="POST" action="{{route('cotas.store')}}">
                        @csrf
                        <input type="hidden" name="cota" value="0">
                        @csrf
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="nome">{{__('Name')}}</label>
                                    <input type="text" id="nome" name="nome" class="form-control @error('nome') is-invalid @enderror" value="{{old('nome')}}" autofocus required>

                                    @error('nome')
                                        <div id="validationServer03Feedback" class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="codigo">{{__('Código da cota')}}</label>
                                    <input type="text" id="codigo" name="codigo" class="form-control @error('codigo') is-invalid @enderror" value="{{old('codigo')}}" required>

                                    @error('codigo')
                                        <div id="validationServer03Feedback" class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="descrição">{{__('Descrição')}}</label>
                                    <textarea name="descrição" id="descrição" cols="30" rows="3" class="form-control @error('descrição') is-invalid @enderror" required>{{old('descrição')}}</textarea>

                                    @error('descrição')
                                        <div id="validationServer03Feedback" class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            @foreach ($cursos as $i => $curso)
                                <div class="row" style="border: 1px solid rgb(156, 156, 156); border-radius: 5px; margin-top: 10px; margin-left: 0px; margin-right: 0px; padding:10px;">
                                    <div class="col-md-6 mb-3">
                                        <input id="curso-input-{{$curso->id}}" type="hidden" name="cursos[]" value="{{old('cursos.'.$i)}}">
                                        <input id="curso-{{$curso->id}}" type="checkbox" onclick="alocarValue(this, {{$curso->id}})" @if(old('cursos.'.$i) != null) checked @endif>
                                        <label for="curso-{{$curso->id}}">{{$curso->nome}} (@switch($curso->turno)
                                            @case($turnos['matutino']){{"Manhã"}}@break
                                            @case($turnos['vespertino']){{"Tarde"}}@break
                                            @case($turnos['noturno']){{"Noturno"}}@break
                                            @case($turnos['integral']){{"Integral"}}@break
                                        @endswitch)</label>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="percentual-{{$curso->id}}">{{__('Quantidade de vagas')}}</label>
                                        <input type="number" name="percentual[]" id="percentual-{{$curso->id}}" class="form-control @error('percentual.'.$i) is-invalid @enderror" value="{{old('percentual.'.$i)}}">

                                        @error('percentual.'.$i)
                                            <div id="validationServer03Feedback" class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            @endforeach
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success btn-cota" form="criar-cota-form">Criar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editar-cota" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #53F2C7;">
                    <h5 class="modal-title" id="staticBackdropLabel" style="color: white;">Editar cota</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="edit-cota-form" method="POST" action="{{route('cotas.update.modal')}}">
                        @csrf
                        <input type="hidden" name="_method" value="PUT">
                        <input type="hidden" id="cota-edit" name="cota" value="">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nome">{{__('Name')}}</label>
                                <input type="text" id="nome-edit" name="nome" class="form-control @error('nome') is-invalid @enderror" value="{{old('nome')}}" autofocus required>

                                @error('nome')
                                    <div id="validationServer03Feedback" class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="codigo">{{__('Código da cota')}}</label>
                                <input type="text" id="codigo-edit" name="codigo" class="form-control @error('codigo') is-invalid @enderror" value="{{old('codigo')}}" required>

                                @error('codigo')
                                    <div id="validationServer03Feedback" class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="descrição">{{__('Descrição')}}</label>
                                <textarea name="descrição" id="descrição-edit" cols="30" rows="3" class="form-control @error('descrição') is-invalid @enderror" required>{{old('descrição')}}</textarea>

                                @error('descrição')
                                    <div id="validationServer03Feedback" class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        @foreach ($cursos as $i => $curso)
                            <div class="row" style="border: 1px solid rgb(156, 156, 156); border-radius: 5px; margin-top: 10px; margin-left: 0px; margin-right: 0px; padding:10px;">
                                <div class="col-md-6 mb-3">
                                    <input class="limpar" id="curso-input-edit-{{$curso->id}}" type="hidden" name="cursos[]" value="{{old('cursos.'.$i)}}">
                                    <input class="limpar" id="curso-edit-{{$curso->id}}" type="checkbox" onclick="alocarValueEdit(this, {{$curso->id}})" @if(old('cursos.'.$i) != null) checked @endif>
                                    <label for="curso-{{$curso->id}}">{{$curso->nome}} (@switch($curso->turno)
                                        @case($turnos['matutino']){{"Manhã"}}@break
                                        @case($turnos['vespertino']){{"Tarde"}}@break
                                        @case($turnos['noturno']){{"Noturno"}}@break
                                        @case($turnos['integral']){{"Integral"}}@break
                                    @endswitch)</label>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="percentual-{{$curso->id}}">{{__('Quantidade de vagas')}}</label>
                                    <input type="number" name="percentual[]" id="percentual-edit-{{$curso->id}}" class="form-control limpar @error('percentual.'.$i) is-invalid @enderror" value="{{old('percentual.'.$i)}}">

                                    @error('percentual.'.$i)
                                        <div id="validationServer03Feedback" class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        @endforeach
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success btn-cota" form="edit-cota-form">Sim</button>
                </div>
            </div>
        </div>
    </div>

    @foreach ($cotas as $cota)
        <!-- Modal -->
        <div class="modal fade" id="delete-cota-{{$cota->id}}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header" style="background-color: #dc3545;">
                        <h5 class="modal-title" id="staticBackdropLabel" style="color: white;">Deletar cota</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="delete-cota-{{$cota->id}}-form" method="POST" action="{{route('cotas.destroy', ['cota' => $cota])}}">
                            @csrf
                            <input type="hidden" name="_method" value="DELETE">
                            Tem certeza que deseja deletar essa cota?
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger" form="delete-cota-{{$cota->id}}-form">Sim</button>
                    </div>
                </div>
            </div>
        </div>

    @endforeach
    @if(old('cota') != null && old('cota') == 0)
        <script>
            $(document).ready(function() {
                $('#criar-cota').modal('show');
            });
        </script>
    @elseif(old('cota') != null && old('cota') > 0)
        <script>
            $(document).ready(function() {
                $('#editar-cota').modal('show');
            });
        </script>
    @endif
    <script>
        function alocarValue(checkbox, id){
            if(checkbox.checked) {
                document.getElementById('curso-input-'+id).value = id;
            } else {
                document.getElementById('curso-input-'+id).value = null;
            }
        }

        function alocarValueEdit(checkbox, id){
            if(checkbox.checked) {
                document.getElementById('curso-input-edit-'+id).value = id;
            } else {
                document.getElementById('curso-input-edit-'+id).value = null;
            }
        }

        function editarCota(id) {
            $.ajax({
                url:"{{route('cota.info.ajax')}}",
                type:"get",
                data: {"cota_id": id},
                dataType:'json',
                success: function(cota) {
                    document.getElementById('cota-edit').value = cota.id;
                    document.getElementById('nome-edit').value = cota.nome;
                    document.getElementById('codigo-edit').value = cota.cod_cota;
                    document.getElementById('descrição-edit').value = cota.descricao;

                    limpar();

                    for(var i = 0; i < cota.cursos.length; i++) {
                        document.getElementById('curso-input-edit-'+cota.cursos[i].id).value = cota.cursos[i].id;
                        document.getElementById('curso-edit-'+cota.cursos[i].id).checked = true;
                        document.getElementById('percentual-edit-'+cota.cursos[i].id).value = cota.cursos[i].percentual;
                    }
                }
            });
        }

        function limpar() {
            var inputs = document.getElementsByClassName('limpar');

            for (var i = 0; i < inputs.length; i++) {
                if (inputs[i].type == "hidden") {
                    inputs[i].value = "";
                } else if (inputs[i].type == "checkbox") {
                    inputs[i].checked = false;
                } else if (inputs[i].type == "number") {
                    inputs[i].value = "";
                }
            }
        }
    </script>
</x-app-layout>


