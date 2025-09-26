<x-app-layout>
    <div class="fundo2 px-5">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="row justify-content-center">
                    <div class="col-md-11 cabecalho p-2 px-3 align-items-center">
                        <div class="row ">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <img src="{{asset('img/Grupo 1662.svg')}}"
                                        alt="" width="40" class="img-flex">
                                    <span class="tituloTabelas ps-1">Cotas</span>
                                </div>
                                <div style="text-align: right">
                                    <button title="Adicionar nova cota" data-bs-toggle="modal" data-bs-target="#criar-cota" style="cursor: pointer;"><img width="35" src="{{asset('img/Grupo 1663.svg')}}" alt="Icone de adiconar nova cota"></button>
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
                                <th scope="col">Código</th>
                                <th class="text-center">Ações</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach ($cotas as $i => $cota)
                                <tr>
                                    <th class="align-middle">{{$i+1}}</th>
                                    <td class="align-middle">{{$cota->cod_novo}}</td>
                                    <td class="align-middle text-center">
                                    <button title="Deletar cota" id="criar-cota-btn" data-bs-toggle="modal" data-bs-target="#delete-cota-{{$cota->id}}" style="cursor: pointer;"><img class="m-1 " width="30" src="{{asset('img/Grupo 1664.svg')}}" alt="Icone de deletar cota"></button>
                                    <button title="Editar cota" onclick="editarCota({{$cota->id}})" data-bs-toggle="modal" data-bs-target="#editar-cota" style="cursor: pointer;"><img class="m-1" width="30" src="{{asset('img/Grupo 1675.svg')}}" alt="Icone de editar cota"></button>
                                    <a href="{{route('cotas.remanejamento', $cota->id)}}">
                                        <button title="Editar remanejamento" style="cursor: pointer;"><img class="m-1" width="30" src="{{asset('img/icons-document-blue.png')}}" alt="Icone de editar cota"></button>
                                    </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <a href="{{route('index')}}" class="btn botao my-2 py-1"> <span class="px-4">Voltar</span></a>
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
                                <div title="Deletar cota" class="d-flex align-items-center listagemLista my-1 pt-1 pb-1">
                                    <img class="aling-middle" width="33" src="{{asset('img/Grupo 1664.svg')}}" alt="Icone de deletar cota">
                                    <div style="font-size: 13px;" class="tituloLista aling-middle mx-3">
                                        Deletar cota
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div title="Editar cota" class="d-flex align-items-center listagemLista my-1 pt-1 pb-1">
                                    <img class="aling-middle" width="33" src="{{asset('img/Grupo 1665.svg')}}" alt="Icone de editar cota">
                                    <div style="font-size: 13px;" class="tituloLista aling-middle mx-3">
                                        Editar cota
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div title="Editar remanejamento" class="d-flex align-items-center listagemLista my-1 pt-1 pb-1">
                                    <img class="aling-middle" width="33" src="{{asset('img/icons-document-blue.png')}}" alt="Icone de editar remanejamento">
                                    <div style="font-size: 13px;" class="tituloLista aling-middle mx-3">
                                        Editar remanejamento
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        {{-- <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="row">
                    <div class="col-md-12 cabecalho p-2 px-3 align-items-center">
                        <div class="row justify-content-between">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <img src="{{asset('img/Grupo 1662.svg')}}"
                                        alt="" width="40" class="img-flex">
                                    <span class="tituloTabelas ps-1">Cotas</span>
                                </div>
                                <button title="Adicionar nova cota" data-bs-toggle="modal" data-bs-target="#criar-cota" style="cursor: pointer;"><img width="35" src="{{asset('img/Grupo 1663.svg')}}" alt="Icone de adiconar nova cota"></button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 corpo p-2 px-3">
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
                                    <button title="Deletar cota" id="criar-cota-btn" data-bs-toggle="modal" data-bs-target="#delete-cota-{{$cota->id}}" style="cursor: pointer;"><img class="m-1 " width="30" src="{{asset('img/Grupo 1664.svg')}}" alt="Icone de deletar cota"></button>
                                    <button title="Editar cota" onclick="editarCota({{$cota->id}})" data-bs-toggle="modal" data-bs-target="#editar-cota" style="cursor: pointer;"><img class="m-1" width="30" src="{{asset('img/Grupo 1675.svg')}}" alt="Icone de editar cota"></button>
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
                                <div title="Deletar analista" class="d-flex align-items-center listagemLista my-2 pt-1 pb-3">
                                    <img class="aling-middle" width="33" src="{{asset('img/Grupo 1664.svg')}}" alt="Icone de deletar analista">
                                    <div style="font-size: 13px;" class="tituloLista aling-middle mx-3">
                                        Deletar analista
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div title="Editar analista" class="d-flex align-items-center listagemLista my-2 pt-1 pb-3">
                                    <img class="aling-middle" width="33" src="{{asset('img/Grupo 1665.svg')}}" alt="Icone de editar analista">
                                    <div style="font-size: 13px;" class="tituloLista aling-middle mx-3">
                                        Editar analista
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div> --}}
    </div>

    <div class="modal fade" id="criar-cota" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-dialog">
                <div class="modal-content modalFundo p-3">
                    <div class="col-md-12 tituloModal">Insira uma nova cota</div>

                    <form id="criar-cota-form" method="POST" action="{{route('cotas.store')}}">
                        @csrf
                        <input type="hidden" name="cota" value="0">
                        <div class="pt-3 pb-2 textoModal">
                            <div class="row">
                                <div class="col-sm-12">
                                    <label class="pb-2" for="nome">{{__('Name')}}</label>
                                    <input type="text" class="form-control campoDeTexto @error('nome') is-invalid @enderror" id="nome" name="nome" value="{{old('nome')}}" placeholder="Insira o nome da cota">

                                    @error('nome')
                                        <div id="validationServer03Feedback" class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-12">
                                    <label class="pb-2 pt-2" for="cod_novo">Código da cota:</label>
                                    <input type="text" class="form-control campoDeTexto @error('cod_novo') is-invalid @enderror" id="cod_novo" name="cod_novo" value="{{old('cod_novo')}}" placeholder="Insira o código da cota">

                                    @error('cod_novo')
                                        <div id="validationServer03Feedback" class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <label class="pb-2 pt-2" for="cod_siga">{{__('Código do siga')}}</label>
                                    <input type="text" id="cod_siga" name="cod_siga" class="form-control campoDeTexto @error('cod_siga') is-invalid @enderror" value="{{old('cod_siga')}}" required>

                                    @error('cod_siga')
                                        <div id="validationServer03Feedback" class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-12">
                                    <label class="pb-2 pt-2" for="descricao">Descrição da cota</label>
                                    <textarea class="form-control campoDeTexto @error('descricao') is-invalid @enderror" id="descricao" name="descricao" rows="3">{{old('descricao')}}</textarea>

                                    @error('descricao')
                                        <div id="validationServer03Feedback" class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="pb-2 pt-2">Selecione o curso e digita a quantidade de vagas:</div>

                            @foreach ($cursos as $i => $curso)
                                <div class="row">
                                    <div class="col-sm-12">
                                        <input id="curso-input-{{$curso->id}}" type="hidden" name="cursos[]" value="{{old('cursos.'.$i)}}">
                                        <input id="curso-{{$curso->id}}" type="checkbox" onclick="alocarValue(this, {{$curso->id}})" class="form-check-input" data-bs-toggle="collapse" href="#curso_{{$curso->id}}" role="button" @if(old('cursos.'.$i) != null) checked aria-expanded="true" @else aria-expanded="false" @endif aria-controls="collapseExample">
                                        <label class="form-check-label" for="curso-{{$curso->id}}">
                                            {{$curso->nome}} (@switch($curso->turno)
                                                @case($turnos['Matutino']){{"Matutino"}}@break
                                                @case($turnos['Vespertino']){{"Vespertino"}}@break
                                                @case($turnos['Noturno']){{"Noturno"}}@break
                                                @case($turnos['Integral']){{"Integral"}}@break
                                            @endswitch)
                                        </label>
                                        <div class="collapse col-md-6 py-1 @if(old('cursos.'.$i) != null) show @endif" id="curso_{{$curso->id}}">
                                            <label for="quantidade-{{$curso->id}}">{{__('Quantidade de vagas')}}</label>
                                            <input type="number" name="quantidade[]" id="quantidade-{{$curso->id}}" class="form-control campoDeTexto @error('quantidade.'.$i) is-invalid @enderror" value="{{old('quantidade.'.$i)}}">

                                            @error('quantidade.'.$i)
                                                <div id="validationServer03Feedback" class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            <div class="row justify-content-between mt-4">
                                <div class="col-md-3">
                                    <button type="button" class="btn botao my-2 py-1" data-bs-dismiss="modal"> <span class="px-4">Voltar</span></button>
                                </div>
                                <div class="col-md-4">
                                    <button type="submit" class="btn botaoVerde my-2 py-1" form="criar-cota-form"><span class="px-4">Salvar</span></button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editar-cota" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-dialog">
                <div class="modal-content modalFundo p-3">
                    <div class="col-md-12 tituloModal">Editar cota</div>
                    <div class="pt-3 pb-2 textoModal">
                        <form id="edit-cota-form" method="POST" action="{{route('cotas.update.modal')}}">
                            @csrf
                            <input type="hidden" name="_method" value="PUT">
                            <input type="hidden" id="cota-edit" name="cota" value="">
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="pb-2" for="nome-edit">{{__('Name')}}</label>
                                    <input type="text" id="nome-edit" name="nome" class="form-control campoDeTexto @error('nome') is-invalid @enderror" value="{{old('nome')}}" autofocus required>

                                    @error('nome')
                                        <div id="validationServer03Feedback" class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="pb-2 pt-2" for="codigo-edit">{{__('Código da cota')}}</label>
                                    <input type="text" id="codigo-edit" name="cod_novo" class="form-control campoDeTexto @error('codigo') is-invalid @enderror" value="{{old('codigo')}}" required>

                                    @error('codigo')
                                        <div id="validationServer03Feedback" class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="pb-2 pt-2" for="cod_siga-edit">{{__('Código do siga')}}</label>
                                    <input type="text" id="cod_siga-edit" name="cod_siga" class="form-control campoDeTexto @error('cod_siga-edit') is-invalid @enderror" value="{{old('cod_siga-edit')}}" required>

                                    @error('cod_siga-edit')
                                        <div id="validationServer03Feedback" class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="pb-2 pt-2" for="descrição-edit">{{__('Descrição')}}</label>
                                    <textarea name="descricao" id="descrição-edit" cols="30" rows="3" class="form-control campoDeTexto @error('descricao') is-invalid @enderror" required>{{old('descricao')}}</textarea>

                                    @error('descricao')
                                        <div id="validationServer03Feedback" class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            @foreach ($cursos as $i => $curso)
                                <div class="row">
                                    <div class="col-sm-12">
                                        <input class="limpar" id="curso-input-edit-{{$curso->id}}" type="hidden" name="cursos[]" value="{{old('cursos.'.$i)}}">
                                        <input class="limpar form-check-input form-check-cursos" id="curso-edit-{{$curso->id}}" type="checkbox" onclick="alocarValueEdit(this, {{$curso->id}})" data-bs-toggle="collapse" href="#curso_edit_{{$curso->id}}" role="button" @if(old('cursos.'.$i) != null) checked aria-expanded="true" @else aria-expanded="false" @endif aria-controls="collapseExample">
                                        <label class="form-check-label" for="curso-edit-{{$curso->id}}">
                                            {{$curso->nome}} (@switch($curso->turno)
                                                @case($turnos['Matutino']){{"Matutino"}}@break
                                                @case($turnos['Vespertino']){{"Vespertino"}}@break
                                                @case($turnos['Noturno']){{"Noturno"}}@break
                                                @case($turnos['Integral']){{"Integral"}}@break
                                            @endswitch)
                                        </label>
                                        <div class="collapse collapse-edit col-md-6 py-1 @if(old('cursos.'.$i) != null) show @endif" id="curso_edit_{{$curso->id}}" idcurso="{{$curso->id}}">
                                            <label for="quantidade-edit-{{$curso->id}}">{{__('Quantidade de vagas')}}</label>
                                            <input type="number" name="quantidade[]" id="quantidade-edit-{{$curso->id}}" class="form-control campoDeTexto @error('quantidade.'.$i) is-invalid @enderror limpar form-cursos-number" value="{{old('quantidade.'.$i)}}" >

                                            @error('quantidade.'.$i)
                                                <div id="validationServer03Feedback" class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </form>
                        <div class="row justify-content-between mt-4">
                            <div class="col-md-3">
                                <button type="button" class="btn botao my-2 py-1" data-bs-dismiss="modal"> <span class="px-4">Voltar</span></button>
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn botaoVerde my-2 py-1" form="edit-cota-form"><span class="px-4">Salvar</span></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @foreach ($cotas as $cota)
        <!-- Modal -->
        <div class="modal fade" id="delete-cota-{{$cota->id}}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-dialog">
                    <div class="modal-content modalFundo p-3">
                        <div class="col-md-12 tituloModal">Deletar cota</div>
                            <div class="pt-3 pb-2 textoModal">
                                <form id="delete-cota-{{$cota->id}}-form" method="POST" action="{{route('cotas.destroy', ['cota' => $cota])}}">
                                    @csrf
                                    <input type="hidden" name="_method" value="DELETE">
                                    Tem certeza que deseja deletar essa cota?
                                </form>
                                <div class="row justify-content-between mt-4">
                                    <div class="col-md-3">
                                        <button type="button" class="btn botao my-2 py-1" data-bs-dismiss="modal"><span class="px-4">Cancelar</span></button>
                                    </div>
                                    <div class="col-md-4">
                                        <button type="submit" class="btn botaoVerde my-2 py-1" form="delete-cota-{{$cota->id}}-form" style="background-color: #FC605F;"><span class="px-4">Excluir</span></button>
                                    </div>
                                </div>
                            </div>
                        </div>
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
                    document.getElementById('codigo-edit').value = cota.cod_novo;
                    document.getElementById('descrição-edit').value = cota.descricao;
                    document.getElementById('cod_siga-edit').value = cota.cod_siga;

                    limpar(cota.cursos);

                    for(var i = 0; i < cota.cursos.length; i++) {
                        document.getElementById('curso-input-edit-'+cota.cursos[i].id).value = cota.cursos[i].id;
                        if (document.getElementById('curso-edit-'+cota.cursos[i].id).checked == false) {
                            $('#curso-edit-'+cota.cursos[i].id).click();
                        }
                        document.getElementById('quantidade-edit-'+cota.cursos[i].id).value = cota.cursos[i].quantidade;
                    }
                }
            });
        }

        function limpar(cursos) {
            var inputs = document.getElementsByClassName('limpar');
            var check_collapse = document.getElementsByClassName('form-check-cursos');
            var collapses = document.getElementsByClassName('collapse-edit');
            var inputs_numbers = document.getElementsByClassName('form-cursos-number');

            for (var i = 0; i < inputs.length; i++) {
                if (inputs[i].type == "hidden") {
                    inputs[i].value = "";
                }
            }

            for (var i = 0; i < collapses.length; i++) {
                if (!(idExiste(cursos, collapses[i].getAttribute("idcurso")))) {
                    if (check_collapse[i].checked) {
                        $("#"+check_collapse[i].id).click();
                    }
                    inputs_numbers[i].value = "";

                }
            }
        }

        function idExiste(cursos, id) {
            for (var i = 0; i < cursos.length; i++) {
                if (cursos[i].id == id) {
                    return true;
                }
            }
            return false;
        }
    </script>
</x-app-layout>


