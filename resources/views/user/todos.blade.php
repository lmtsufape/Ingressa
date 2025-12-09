<x-app-layout>
    <div class="fundo2 px-5">
        <div class="row justify-content-center">
            <div class="col-md-8">

                {{-- Cabeçalho --}}
                <div class="row justify-content-center">
                    <div class="col-md-11 cabecalho p-2 px-3 align-items-center">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <img src="{{ asset('img/Grupo 1662.svg') }}" alt="" width="40">
                                <span class="tituloTabelas ps-1">Usuários</span>
                            </div>
                            <div class="col-md-4" style="text-align: right">
                                <button title="Criar usuário" id="criar-user-btn" data-bs-toggle="modal" data-bs-target="#criar-user-modal" style="cursor: pointer;"><img width="35" src="{{ asset('img/Grupo 1663.svg') }}" alt="Icone de adicionar usuário"></button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Corpo --}}
                <div class="row justify-content-center" style="margin-bottom: 20px;">
                    <div class="col-md-11 corpo p-2 px-3">

                        {{-- Mensagem de sucesso --}}
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show mt-3">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        {{-- Tabela de Usuários --}}
                        @if ($usuarios->count() > 0)
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nome</th>
                                        <th>E-mail</th>
                                        <th>Função</th>
                                        <th>CPF</th>
                                        <th class="text-center">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($usuarios as $i => $user)
                                        <tr>
                                            <th class="align-middle">{{ $i + 1 }}</th>
                                            <td class="align-middle">{{ $user->name }}</td>
                                            <td class="align-middle">{{ $user->email }}</td>
                                            <td class="align-middle">
                                                @if($user->role == \App\Models\User::ROLE_ENUM['analista'])
                                                    Analista
                                                @elseif($user->role == \App\Models\User::ROLE_ENUM['admin'])
                                                    Admin
                                                @elseif($user->role == \App\Models\User::ROLE_ENUM['candidato'])
                                                    Candidato
                                                @else
                                                    Usuário
                                                @endif
                                            </td>
                                            <td class="align-middle">
                                                @if(method_exists($user, 'candidato') && $user->candidato)
                                                    {{ $user->candidato->nu_cpf_inscrito ?? '-' }}
                                                @else
                                                    -
                                                @endif
                                            </td>

                                            <td class="text-center align-middle">
                                                <button title="Deletar usuário" data-bs-toggle="modal"
                                                    data-bs-target="#modalStaticDeletarUser_{{ $user->id }}"
                                                    style="cursor: pointer;"><img class="m-1 " width="30"
                                                        src="{{ asset('img/Grupo 1664.svg') }}"
                                                        alt="Icone de deletar usuário"></button>
                                                <button title="Editar usuário" onclick="setUserId({{ $user->id }})"
                                                    data-bs-toggle="modal" data-bs-target="#editar-user-modal"
                                                    style="cursor: pointer;"><img class="m-1 " width="30"
                                                        src="{{ asset('img/Grupo 1665.svg') }}"
                                                        alt="Icone de editar usuário"></button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            {{ $usuarios->links() }}
                        @else
                            <div class="pt-3 pb-3">Nenhum usuário encontrado.</div>
                        @endif

                        <a href="{{ route('index') }}" class="btn botao my-2 py-1">
                            <span class="px-4">Voltar</span>
                        </a>
                    </div>
                </div>
            </div>

            

            <!-- Modal Editar Usuário -->
            <div class="modal fade" id="editar-user-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content modalFundo p-3">
                        <div class="col-md-12 tituloModal">Editar usuário</div>

                        <form method="POST" id="editar-user-form" action="{{ route('usuarios.updateUser') }}">
                            @csrf
                            <input type="hidden" id="user-edit" name="user_id" value="{{ old('user_id') }}">
                            <div class="form-row">
                                <div class="col-md-12 pt-3 textoModal">
                                    <label class="pb-2" for="name-edit">Nome completo:</label>
                                    <input id="name-edit" class="form-control apenas_letras campoDeTexto @error('name') is-invalid @enderror" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="Insira o nome completo do usuário">

                                    @error('name')
                                        <div id="validationServer03Feedback" class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-md-12 pt-3 textoModal">
                                    <label class="pb-2 pt-2" for="email-edit">E-mail:</label>
                                    <input id="email-edit" class="form-control campoDeTexto @error('email') is-invalid @enderror" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email" placeholder="Insira o e-mail de acesso do usuário">

                                    <div class="col-md-12 pt-3 textoModal">
                                        <label class="pb-2 pt-2" for="role-edit">Função:</label>
                                        <select id="role-edit" name="role" class="form-control">
                                            <option value="{{ \App\Models\User::ROLE_ENUM['analista'] }}" selected>Analista</option>
                                            <option value="{{ \App\Models\User::ROLE_ENUM['admin'] }}">Admin</option>
                                            <option value="{{ \App\Models\User::ROLE_ENUM['candidato'] }}">Candidato</option>
                                        </select>
                                    </div>

                                    @error('email')
                                        <div id="validationServer03Feedback" class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div id="analyst-fields-edit" style="display: none;">
                                    <div class="col-md-12 pt-3 textoModal">
                                        <label class="pb-2 pt-2" for="tipo">{{ __('Selecione o(s) cargo(s) do usuário:') }}</label>
                                        <input type="hidden" class="checkbox_tipo @error('tipos_analista_edit') is-invalid @enderror">
                                        @foreach ($tipos as $tipo)
                                            <div class="form-check">
                                                <input class="form-check-input form-check-tipos" type="checkbox" name="tipos_analista_edit[]" value="{{ $tipo->id }}" id="tipo-editar-user-modal-{{ $tipo->id }}" data-tipo-name="@if($tipo->tipo == \App\Models\TipoAnalista::TIPO_ENUM['geral']) geral @elseif($tipo->tipo == \App\Models\TipoAnalista::TIPO_ENUM['heteroidentificacao']) heteroidentificacao @elseif($tipo->tipo == \App\Models\TipoAnalista::TIPO_ENUM['medico']) medico @endif">
                                                <label class="form-check-label" for="tipo_edit_{{ $tipo->id }}">
                                                    @if ($tipo->tipo == \App\Models\TipoAnalista::TIPO_ENUM['geral'])
                                                        Geral
                                                    @elseif($tipo->tipo == \App\Models\TipoAnalista::TIPO_ENUM['heteroidentificacao'])
                                                        Heteroidentificação
                                                    @elseif($tipo->tipo == \App\Models\TipoAnalista::TIPO_ENUM['medico'])
                                                        Seção médica
                                                    @endif
                                                </label>
                                            </div>
                                        @endforeach
                                        @error('tipos_analista_edit')
                                            <div id="validationServer03Feedback" class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="col-md-12 pt-3 textoModal">
                                        <label class="pb-2 pt-2" for="tipo">{{ __('Selecione o(s) curso(s) do usuário:') }}</label>
                                        <input type="hidden" class="checkbox_tipo @error('cursos_analista_edit') is-invalid @enderror">
                                        @foreach ($cursos as $curso => $codigo)
                                            <div class="form-check">
                                                <input class="form-check-input form-check-cursos" type="checkbox" name="cursos_analista_edit[]" value="{{ $codigo }}" id="cursos_analista_{{ $codigo }}">
                                                <label class="form-check-label" for="cursos_analista_{{ $codigo }}">{{ $curso }}</label>
                                            </div>
                                        @endforeach
                                        @error('cursos_analista_edit')
                                            <div id="validationServer03Feedback" class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="col-md-12 pt-3 textoModal" id="cota-checkboxes-editar-user-modal">
                                        <label class="pb-2 pt-2" for="tipo">{{ __('Selecione a(s) cota(s) do usuário:') }}</label>
                                        <input type="hidden" class="checkbox_tipo @error('cotas_analista_edit') is-invalid @enderror">
                                        @foreach ($cotas as $cota)
                                            <div class="form-check">
                                                <input class="form-check-input form-check-cotas" type="checkbox" name="cotas_analista_edit[]" value="{{ $cota->id }}" id="cotas_analista_{{ $cota->id }}">
                                                <label class="form-check-label" for="cotas_analista_{{ $cota->id }}">{{ $cota->cod_novo }}</label>
                                            </div>
                                        @endforeach
                                        @error('cotas_analista_edit')
                                            <div id="validationServer03Feedback" class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="row justify-content-between mt-4">
                            <div class="col-md-3">
                                <button type="button" class="btn botao my-2 py-1" data-bs-dismiss="modal"> <span class="px-4" style="font-weight: bolder;">Voltar</span></button>
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn botaoVerde my-2 py-1 submeterFormBotao" form="editar-user-form"><span class="px-4" style="font-weight: bolder;">Salvar</span></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Criar Usuário -->
            <div class="modal fade" id="criar-user-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content modalFundo p-3">
                        <div class="col-md-12 tituloModal">Criar usuário</div>

                        <form method="POST" id="criar-user-form" action="{{ route('usuarios.storeUser') }}">
                            @csrf
                            <div class="form-row">
                                <div class="col-md-12 pt-3 textoModal">
                                    <label class="pb-2" for="name">Nome completo:</label>
                                    <input id="name" class="form-control apenas_letras campoDeTexto @error('name') is-invalid @enderror" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="Insira o nome completo do usuário">

                                    @error('name')
                                        <div id="validationServer03Feedback" class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-12 pt-3 textoModal">
                                    <label class="pb-2 pt-2" for="email">E-mail:</label>
                                    <input id="email" class="form-control campoDeTexto @error('email') is-invalid @enderror" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="Insira o e-mail de acesso do usuário">

                                    @error('email')
                                        <div id="validationServer03Feedback" class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-12 pt-3 textoModal">
                                        <label class="pb-2 pt-2" for="role-create">Função:</label>
                                        <select id="role-create" name="role" class="form-control">
                                            <option value="{{ \App\Models\User::ROLE_ENUM['analista'] }}" {{ old('role') == \App\Models\User::ROLE_ENUM['analista'] ? 'selected' : '' }}>Analista</option>
                                            <option value="{{ \App\Models\User::ROLE_ENUM['admin'] }}" {{ old('role') == \App\Models\User::ROLE_ENUM['admin'] ? 'selected' : '' }}>Admin</option>
                                            <option value="{{ \App\Models\User::ROLE_ENUM['candidato'] }}" {{ old('role') == \App\Models\User::ROLE_ENUM['candidato'] ? 'selected' : '' }}>Candidato</option>
                                        </select>
                                </div>

                                <div class="col-md-12 pt-3 textoModal">
                                    <div class="row justify-content-between">
                                        <div class="col-md-6">
                                            <label class="pb-2 pt-2" for="password">Senha:</label>
                                            <input id="password" class="form-control campoDeTexto @error('password') is-invalid @enderror" type="password" name="password" required>

                                            @error('password')
                                                <div id="validationServer03Feedback" class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="pb-2 pt-2" for="password_confirmation">Confirme a senha:</label>
                                            <input id="password_confirmation" class="form-control campoDeTexto" type="password" name="password_confirmation" required>
                                        </div>
                                    </div>
                                </div>

                                <div id="analyst-fields-create" style="display: none;">
                                    <div class="col-md-12 pt-3 textoModal">
                                        <label class="pb-2 pt-2" for="tipo">{{ __('Selecione o(s) cargo(s) do usuário (opcional):') }}</label>
                                        @foreach ($tipos as $tipo)
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="tipos_analista[]" value="{{ $tipo->id }}" id="tipo-criar-user-modal-{{ $tipo->id }}">
                                                <label class="form-check-label" for="tipo_criar_{{ $tipo->id }}">{{ $tipo->getTipo() }}</label>
                                            </div>
                                        @endforeach
                                    </div>

                                    <div class="col-md-12 pt-3 textoModal">
                                        <label class="pb-2 pt-2" for="tipo">{{ __('Selecione o(s) curso(s) do usuário (opcional):') }}</label>
                                        @foreach ($cursos as $curso => $codigo)
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="cursos_analista[]" value="{{ $codigo }}" id="cursos_criar_{{ $codigo }}">
                                                <label class="form-check-label" for="cursos_criar_{{ $codigo }}">{{ $curso }}</label>
                                            </div>
                                        @endforeach
                                    </div>

                                    <div class="col-md-12 pt-3 textoModal" id="cota-checkboxes-criar-user-modal">
                                        <label class="pb-2 pt-2" for="tipo">{{ __('Selecione a(s) cota(s) do usuário (opcional):') }}</label>
                                        @foreach ($cotas as $cota)
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="cotas_analista[]" value="{{ $cota->id }}" id="cotas_criar_{{ $cota->id }}">
                                                <label class="form-check-label" for="cotas_criar_{{ $cota->id }}">{{ $cota->cod_novo }}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="row justify-content-between mt-4">
                            <div class="col-md-3">
                                <button type="button" class="btn botao my-2 py-1" data-bs-dismiss="modal"> <span class="px-4" style="font-weight: bolder;">Voltar</span></button>
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn botaoVerde my-2 py-1 submeterFormBotao" form="criar-user-form"><span class="px-4" style="font-weight: bolder;">Salvar</span></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @foreach ($usuarios as $user)
                <!-- Modal deletar usuário -->
                <div class="modal fade" id="modalStaticDeletarUser_{{ $user->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content modalFundo p-3">
                            <div class="col-md-12 tituloModal">Deletar usuário</div>

                            <form id="deletar-user-form-{{ $user->id }}" method="POST" action="{{ route('usuarios.destroy', ['usuario' => $user]) }}">
                                @csrf
                                <div class="pt-3">
                                    <input type="hidden" name="_method" value="DELETE">
                                    Tem certeza que deseja deletar o usuário {{ $user->name }}?
                                </div>
                            </form>
                            <div class="row justify-content-between mt-4">
                                <div class="col-md-3">
                                    <button type="button" class="btn botao my-2 py-1" data-bs-dismiss="modal"> <span class="px-4" style="font-weight: bolder;">Voltar</span></button>
                                </div>
                                <div class="col-md-4">
                                    <button type="submit" class="btn botaoVerde my-2 py-1 submeterFormBotao" form="deletar-user-form-{{ $user->id }}" style="background-color: #FC605F;"><span class="px-4" style="font-weight: bolder;">Deletar</span></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            <div class="col-md-3">
                <div class="row justify-content-start">
                    <div class="col-md-12 shadow-sm p-2 px-3" style="background-color: white; border-radius: 00.5rem;">
                        <div style="font-size: 21px;" class="tituloModal">
                            Legenda
                        </div>
                        <ul class="list-group list-unstyled">
                            <li>
                                <div title="Deletar usuário"
                                    class="d-flex align-items-center listagemLista my-1 pt-1 pb-1">
                                    <img class="aling-middle" width="33" src="{{ asset('img/Grupo 1664.svg') }}"
                                        alt="Icone de deletar usuário">
                                    <div style="font-size: 13px;" class="tituloLista aling-middle mx-3">
                                        Deletar usuário
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div title="Editar usuário"
                                    class="d-flex align-items-center listagemLista my-1 pt-1 pb-1">
                                    <img class="aling-middle" width="33" src="{{ asset('img/Grupo 1665.svg') }}"
                                        alt="Icone de editar usuário">
                                    <div style="font-size: 13px;" class="tituloLista aling-middle mx-3">
                                        Editar usuário
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

<input type="hidden" id="tipos" value='{{ $tipos->pluck("id") }}'>

<script>
    function setUserId(id) {
        document.getElementById('user-edit').value = id;
    }

    function atualizarVisibilidadeCotas(modalSelector) {
        var modal = document.querySelector('#' + modalSelector);
        if (!modal) return;

        var hasHeteroOrMedico = false;
        modal.querySelectorAll('.form-check-tipos').forEach(function(cb) {
            var tipoName = cb.getAttribute('data-tipo-name');
            if ((tipoName === 'heteroidentificacao' || tipoName === 'medico') && cb.checked) {
                hasHeteroOrMedico = true;
            }
        });

        var cotasContainer = document.getElementById('cota-checkboxes-editar-user-modal');
        if (cotasContainer) {
            cotasContainer.style.display = hasHeteroOrMedico ? 'none' : 'block';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        var editarModal = document.getElementById('editar-user-modal');
        if (editarModal) {
            $('#editar-user-modal').on('shown.bs.modal', function() {
                atualizarDadosModal();
            });
        }
    });

    function atualizarDadosModal() {
        var id = document.getElementById('user-edit').value;
        if (!id) return;

        document.querySelectorAll('#editar-user-modal .form-check-tipos').forEach(function(cb){ cb.checked = false; });
        document.querySelectorAll('#editar-user-modal .form-check-cursos').forEach(function(cb){ cb.checked = false; });
        document.querySelectorAll('#editar-user-modal .form-check-cotas').forEach(function(cb){ cb.checked = false; });

        $.ajax({
            url: "{{ route('usuario.info.ajax') }}",
            type: "get",
            data: { "user_id": id },
            dataType: 'json',
            success: function(user) {
                document.getElementById('user-edit').value = user.id;
                document.getElementById('name-edit').value = user.name;
                document.getElementById('email-edit').value = user.email;

                for (var i = 0; i < user.cargos.length; i++) {
                    var el = document.getElementById('tipo-editar-user-modal-' + user.cargos[i].id);
                    if (el) el.checked = true;
                }

                for (var i = 0; i < user.cursos.length; i++) {
                    var el = document.getElementById('cursos_analista_' + user.cursos[i].cod_curso);
                    if (el) el.checked = true;
                }

                for (var i = 0; i < user.cotas.length; i++) {
                    var el = document.getElementById('cotas_analista_' + user.cotas[i].id);
                    if (el) el.checked = true;
                }

                if (document.getElementById('role-edit')) {
                    document.getElementById('role-edit').value = user.role;
                }
                updateEditFieldsVisibility();
            }
        });
    }

    function updateCreateFieldsVisibility() {
        var roleCreate = document.getElementById('role-create');
        var container = document.getElementById('analyst-fields-create');
        if (!roleCreate || !container) return;
        var analistaValue = parseInt({{ json_encode(\App\Models\User::ROLE_ENUM['analista']) }});
        var show = (parseInt(roleCreate.value) === analistaValue);
        container.style.display = show ? 'block' : 'none';
        container.classList.toggle('d-none', !show);
    }

    function updateEditFieldsVisibility() {
        var roleEdit = document.getElementById('role-edit');
        var container = document.getElementById('analyst-fields-edit');
        if (!roleEdit || !container) return;
        var analistaValue = parseInt({{ json_encode(\App\Models\User::ROLE_ENUM['analista']) }});
        var show = (parseInt(roleEdit.value) === analistaValue);
        container.style.display = show ? 'block' : 'none';
        container.classList.toggle('d-none', !show);
    }

    document.addEventListener('DOMContentLoaded', function() {
        var editarModal = document.getElementById('editar-user-modal');
        if (editarModal) {
            $('#editar-user-modal').on('shown.bs.modal', function() {
                atualizarDadosModal();
                setTimeout(updateEditFieldsVisibility, 100);
            });
        }

        var roleCreate = document.getElementById('role-create');
        if (roleCreate) {
            roleCreate.addEventListener('change', updateCreateFieldsVisibility);
            updateCreateFieldsVisibility();
        }

        var roleEdit = document.getElementById('role-edit');
        if (roleEdit) {
            roleEdit.addEventListener('change', updateEditFieldsVisibility);
            updateEditFieldsVisibility();
        }
    });
</script>
