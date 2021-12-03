<x-app-layout>
    {{-- <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Cursos') }}
        </h2>
    </x-slot> --}}

<div class="fundo px-5 py-5">
    <div class="row justify-content-center">
        <div class="col-md-11 pt-0">
            <div class="row tituloBorda justify-content-between">
                <div class="d-flex align-items-center justify-content-between mx-0 px-0">
                    <span class="align-middle titulo">Cursos</span>
                    <span class="aling-middle">
                        <a onclick="limparValidacao()" data-bs-toggle="modal" data-bs-target="#criar-curso" style="cursor: pointer;"><img class="m-1 " width="40" src="{{asset('img/Grupo 1666.svg')}}" alt="Criar um novo curso"></a>
                        <a onclick="limparValidacao()" data-bs-toggle="modal" data-bs-target="#editarCurso" style="cursor: pointer;"><img class="m-1" width="40" src="{{asset('img/Grupo 1667.svg')}}"alt="icone-busca"> </a>
                    </span>
                </div>
            </div>
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
            @php
                $count = 0;
                $controle = true;
            @endphp
            @while ($controle)
                @if(($count+1) % 4 == 1)
                    <div class="row justify-content-between">
                @endif
                @if (array_key_exists($count, $cursos->toArray()))
                    <div class="col-md-2 caixa mt-5 shadow p-3 py-4 text-center">
                        <img src="{{asset('storage/'.$cursos[$count]->icone)}}" width="100" class="img-fluid">
                        <div class="textoagronomia" style="color: {{$cursos[$count]->cor_padrao != null ? $cursos[$count]->cor_padrao : 'black'}}">{{$cursos[$count]->nome}}</div>
                        <div class="subtitulo">(@switch($cursos[$count]->grau_academico)
                            @case($graus['bacharelado']){{"Bacharelado"}}@break
                            @case($graus['licenciatura']){{"Licenciatura"}}@break
                            @case($graus['tecnologo']){{"Tecnólogo"}}@break
                        @endswitch -
                        @switch($cursos[$count]->turno)
                            @case($turnos['matutino']){{"Matutino"}}@break
                            @case($turnos['vespertino']){{"Vespertino"}}@break
                            @case($turnos['noturno']){{"Noturno"}}@break
                            @case($turnos['integral']){{"Integral"}}@break
                        @endswitch)
                        </div>
                    </div>
                @else
                    <div class="col-md-2  mt-4  p-3 text-center"></div>
                @endif
                @if(($count+1) % 4 == 0)
                    </div>
                    @if (!(array_key_exists($count, $cursos->toArray())))
                        @php
                            $controle = false;
                        @endphp
                    @endif
                @endif
                @php
                    $count++;
                @endphp
            @endwhile
        </div>
    </div>
</div>

<div class="modal fade" id="criar-curso" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content modalFundo p-3">
            <div class="col-md-12 tituloModal">Inserir um novo curso</div>

            <div class="col-md-12 pt-3 pb-2 textoModal">
                <form id="criar-curso-form" method="POST" action="{{route('cursos.store')}}" enctype="multipart/form-data">
                    @csrf
                        <input type="hidden" id="curso" name="curso" value="0">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="nome" class="form-label" >{{__('Name')}}</label>
                                <input type="text" id="nome" name="nome" class="form-control campoDeTexto @error('nome') is-invalid @enderror" value="{{old('nome')}}" autofocus required placeholder="Insira o nome completo do analista">

                                @error('nome')
                                    <div id="validationServer03Feedback" class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="turno" class="form-label">{{__('Turno')}}</label>
                                <select name="turno" id="turno" class="form-control campoDeTexto @error('turno') is-invalid @enderror" required>
                                    <option value="" selected disabled>-- Selecione o turno do curso --</option>
                                    <option @if(old('turno') == $turnos['matutino']) selected @endif value="{{$turnos['matutino']}}">Matutino</option>
                                    <option @if(old('turno') == $turnos['vespertino']) selected @endif value="{{$turnos['vespertino']}}">Vespertino</option>
                                    <option @if(old('turno') == $turnos['noturno']) selected @endif value="{{$turnos['noturno']}}">Noturno</option>
                                    <option @if(old('turno') == $turnos['integral']) selected @endif value="{{$turnos['integral']}}">Integral</option>
                                </select>

                                @error('turno')
                                    <div id="validationServer03Feedback" class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="grau_acadêmico" class="form-label">{{__('Grau acadêmico')}}</label>
                                <select name="grau_acadêmico" id="grau_acadêmico" class="form-control campoDeTexto @error('grau_acadêmico') is-invalid @enderror" required>
                                    <option value="" selected disabled>-- Selecione o grau do curso --</option>
                                    <option @if(old('grau_acadêmico') == $graus['bacharelado']) selected @endif value="{{$graus['bacharelado']}}">Bacharelado</option>
                                    <option @if(old('grau_acadêmico') == $graus['licenciatura']) selected @endif value="{{$graus['licenciatura']}}">Licenciatura</option>
                                    <option @if(old('grau_acadêmico') == $graus['tecnologo']) selected @endif value="{{$graus['tecnologo']}}">Tecnólogo</option>
                                </select>

                                @error('grau_acadêmico')
                                    <div id="validationServer03Feedback" class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="codigo" class="form-label">{{__('Código do curso')}}</label>
                                <input type="text" id="codigo" name="codigo" class="form-control campoDeTexto @error('codigo') is-invalid @enderror" value="{{old('codigo')}}" required>

                                @error('codigo')
                                    <div id="validationServer03Feedback" class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="vagas" class="form-label">{{__('Quantidade de vagas')}}</label>
                                <input type="number" id="vagas" name="quantidade_de_vagas" class="form-control campoDeTexto @error('quantidade_de_vagas') is-invalid @enderror" value="{{old('quantidade_de_vagas')}}" required>

                                @error('quantidade_de_vagas')
                                    <div id="validationServer03Feedback" class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label for="formFile" class="form-label">{{__('Icone do curso')}}</label>
                                <input class="form-control campoDeTexto @error('icone') is-invalid @enderror" type="file" id="icone" name="icone" class="icone" accept=".png">

                                @error('icone')
                                    <div id="validationServer03Feedback" class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="cor" class="form-label">{{__('Cor do curso')}}</label>
                                <input type="color" class="form-control form-control-color campoDeTexto @error('cor') is-invalid @enderror" value="#000000" id="cor" name="cor" title="Escolha a cor padrão do curso" style="width: 100%;">

                                @error('cor')
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
                <div class="col-md-4" style="text-align: right;">
                    <button type="submit" class="btn botaoVerde my-2 py-1" form="criar-curso-form"><span class="px-4">Salvar</span></button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editarCurso" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content modalFundo pt-3 px-3 pb-1">
            <div class="col-md-12 tituloModal">Cursos</div>

            <div class="col-md-12 pt-1 textoModal">
                <ul class="list-group list-unstyled">
                    <li>
                        <div class="d-flex align-items-center my-2 pt-1">
                            <table class="table mt-0">
                                <tbody>
                                    @foreach ($cursos as $curso)
                                        <tr>
                                            <th scope="aling-middle pe-0">
                                                <img class="aling-middle" width="45" src="{{asset('storage/'.$curso->icone)}}" alt="icone-busca">
                                            </th>
                                            <td class="align-middle p-0">
                                                <div class="">
                                                    <div class="tituloLista aling-middle">
                                                        {{$curso->nome}} - @switch($curso->turno)
                                                                                @case($turnos['matutino']){{"Matutino"}}@break
                                                                                @case($turnos['vespertino']){{"Vespertino"}}@break
                                                                                @case($turnos['noturno']){{"Noturno"}}@break
                                                                                @case($turnos['integral']){{"Integral"}}@break
                                                                           @endswitch
                                                    </div>
                                                    <div class="aling-middle datinha">
                                                        {{$curso->cod_curso}}
                                                    </div>
                                                </div>
                                            </td>
                                            <td style="text-align: right;" class="aling-middle">
                                                <button data-bs-toggle="modal" data-bs-target="#delete-curso-{{$curso->id}}"><img class="m-1" width="35" src="{{asset('img/Grupo 1664.svg')}}" alt="Icone excluir" style="cursor: pointer;"></button>
                                                <button data-bs-toggle="modal" data-bs-target="#editar-curso" onclick="carregarInformacoes({{$curso->id}})"><img class="m-1" width="35" src="{{asset('img/Grupo 1665.svg')}}"alt="Icone editar" style="cursor: pointer;"></button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </li>
                </ul>
            </div>

            <div class="row justify-content-between mt-4">
                <div class="col-md-3">
                    <button type="button" class="btn botao my-2 py-1" data-bs-dismiss="modal"> <span class="px-4">Voltar</span></button>
                </div>
                <div class="col-md-4">
                    {{-- <button type="button" class="btn botaoVerde my-2 py-1"><span class="px-4">Publicar</span></button> --}}
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editar-curso" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content modalFundo p-3">
            <div class="col-md-12 tituloModal">Editar curso</div>

            <div class="col-md-12 pt-3 pb-2 textoModal">
                <form id="editar-curso-form" method="POST" action="{{route('cursos.update.ajax')}}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="_method" value="PUT">
                    <input type="hidden" id="curso-edit" name="curso" value="">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="nome-edit" class="form-label">{{__('Name')}}</label>
                            <input type="text" id="nome-edit" name="nome" class="form-control campoDeTexto @error('nome') is-invalid @enderror" value="{{old('nome')}}" autofocus required placeholder="Insira o nome completo do analista">

                            @error('nome')
                                <div id="validationServer03Feedback" class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="turno-edit" class="form-label">{{__('Turno')}}</label>
                            <select name="turno" id="turno-edit" class="form-control campoDeTexto @error('turno') is-invalid @enderror" required>
                                <option value="" selected disabled>-- Selecione o turno do curso --</option>
                                <option @if(old('turno') == $turnos['matutino']) selected @endif value="{{$turnos['matutino']}}">Matutino</option>
                                <option @if(old('turno') == $turnos['vespertino']) selected @endif value="{{$turnos['vespertino']}}">Vespertino</option>
                                <option @if(old('turno') == $turnos['noturno']) selected @endif value="{{$turnos['noturno']}}">Noturno</option>
                                <option @if(old('turno') == $turnos['integral']) selected @endif value="{{$turnos['integral']}}">Integral</option>
                            </select>

                            @error('turno')
                                <div id="validationServer03Feedback" class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="grau_acadêmico-edit" class="form-label">{{__('Grau acadêmico')}}</label>
                            <select name="grau_acadêmico" id="grau_acadêmico-edit" class="form-control campoDeTexto @error('grau_acadêmico') is-invalid @enderror" required>
                                <option value="" selected disabled>-- Selecione o grau do curso --</option>
                                <option @if(old('grau_acadêmico') == $graus['bacharelado']) selected @endif value="{{$graus['bacharelado']}}">Bacharelado</option>
                                <option @if(old('grau_acadêmico') == $graus['licenciatura']) selected @endif value="{{$graus['licenciatura']}}">Licenciatura</option>
                                <option @if(old('grau_acadêmico') == $graus['tecnologo']) selected @endif value="{{$graus['tecnologo']}}">Tecnólogo</option>
                            </select>

                            @error('grau_acadêmico')
                                <div id="validationServer03Feedback" class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="codigo-edit" class="form-label">{{__('Código do curso')}}</label>
                            <input type="text" id="codigo-edit" name="codigo" class="form-control campoDeTexto @error('codigo') is-invalid @enderror" value="{{old('codigo')}}" required>

                            @error('codigo')
                                <div id="validationServer03Feedback" class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="vagas-edit" class="form-label">{{__('Quantidade de vagas')}}</label>
                            <input type="number" id="vagas-edit" name="quantidade_de_vagas" class="form-control campoDeTexto @error('quantidade_de_vagas') is-invalid @enderror" value="{{old('quantidade_de_vagas')}}" required>

                            @error('quantidade_de_vagas')
                                <div id="validationServer03Feedback" class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label for="formFile" class="form-label">{{__('Icone do curso')}}</label>
                            <input class="form-control campoDeTexto @error('icone') is-invalid @enderror" type="file" id="icone" name="icone" class="icone" accept=".png">
                            <small id="aviso-icone" style="display: none;">Para trocar o icone basta enviar o novo</small>

                            @error('icone')
                                <div id="validationServer03Feedback" class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="cor-edit" class="form-label">{{__('Cor do curso')}}</label>
                            <input type="color" class="form-control form-control-color campoDeTexto @error('cor') is-invalid @enderror" value="#000000" id="cor-edit" name="cor" title="Escolha a cor padrão do curso" style="width: 100%;">

                            @error('cor')
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
                    <button type="button" class="btn botao my-2 py-1" data-bs-toggle="modal" data-bs-target="#editarCurso"> <span class="px-4">Voltar</span></button>
                </div>
                <div class="col-md-4" style="text-align: right;">
                    <button type="submit" class="btn botaoVerde my-2 py-1" form="editar-curso-form"><span class="px-4">Salvar</span></button>
                </div>
            </div>
        </div>
    </div>
</div>

@foreach ($cursos as $curso)
    <!-- Modal -->
    <div class="modal fade" id="delete-curso-{{$curso->id}}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content modalFundo p-3">
                <div class="col-md-12 tituloModal">Excluir curso</div>

                <div class="col-md-12 pt-3 pb-2 textoModal">
                    <form id="delete-curso-{{$curso->id}}-form" method="POST" action="{{route('cursos.destroy', ['curso' => $curso])}}">
                        @csrf
                        <input type="hidden" name="_method" value="DELETE">
                        Tem certeza que deseja deletar esse curso?
                    </form>

                    <div class="row justify-content-between mt-4">
                        <div class="col-md-3">
                            <button type="button" class="btn botao my-2 py-1" data-bs-toggle="modal" data-bs-target="#editarCurso"> <span class="px-4">Voltar</span></button>
                        </div>
                        <div class="col-md-4" style="text-align: right;">
                            <button type="submit" class="btn botaoVerde my-2 py-1" form="delete-curso-{{$curso->id}}-form" style="background-color: #FC605F;"><span class="px-4">Excluir</span></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endforeach

@if (old('curso') != null && old('curso') == 0)
    <script>
        $(document).ready(function() {
            $('#criar-curso').modal('show');
        });
    </script>
@elseif (old('curso') != null && old('curso') > 0)
    <script>
        $(document).ready(function() {
            $('#editar-curso').modal('show');
        });
    </script>
@endif

<script>
    function carregarInformacoes(id) {
        $.ajax({
            url:"{{route('cursos.info.ajax')}}",
            type:"get",
            data: {"curso_id": id},
            dataType:'json',
            success: function(curso) {
                document.getElementById('curso-edit').value = curso.id;
                document.getElementById('nome-edit').value = curso.nome;
                document.getElementById('turno-edit').value = curso.turno;
                document.getElementById('grau_acadêmico-edit').value = curso.grau_academico;
                document.getElementById('codigo-edit').value = curso.cod_curso;
                document.getElementById('vagas-edit').value = curso.vagas;
                document.getElementById('cor-edit').value = curso.cor_padrao;

                if (curso.icone != null) {
                    document.getElementById('aviso-icone').style.display = "block";
                } else {
                    document.getElementById('aviso-icone').style.display = "none";
                }
            }
        });
    }

    function limparValidacao() {
        var textos = document.getElementsByClassName('is-invalid');

        for (var i = 0; i < textos.length; i++) {
            textos[i].className = "form-control";
        }
    }
</script>
</x-app-layout>
