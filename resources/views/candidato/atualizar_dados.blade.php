<x-app-layout>
    <style>
        label:has(+ :is(input, select, textarea)[required])::after ,
        legend.form-label.required::after {
        content: " *";
        color: red;
        }
    </style>
    <div class="container justify-content-center my-5 mx-auto p-4 shadow caixa">
        <div class="data bordinha">
            Atualização dos dados
        </div>
        @if (session('success'))
            <div class="col-md-12">
                <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
                    <symbol id="check-circle-fill" fill="currentColor" viewBox="0 0 16 16">
                        <path
                            d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
                    </symbol>
                </svg>

                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img"
                        aria-label="Success:">
                        <use xlink:href="#check-circle-fill" />
                    </svg>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        @endif
        <div class="mt-2 subtexto">
            Confira se seus dados cadastrais estão corretos. Caso tenha algum dado incorreto, pedimos que
            corrija, visto que essas são informações fundamentais para seu ingresso na Universidade.
            Solicitamos também, que preencha alguns dados adicionais.
        </div>
        <div class="row">
            <div class="col-md-12">
                <form id="primeiro-acesso-form" class="my-4" method="POST"
                    action="{{ route('candidato.atualizar', ['candidato' => $candidato, 'inscricao' => $inscricao]) }}"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row pt-2">
                        <div class="form-group col-md-3">
                            <label for="nu_rg" class="form-label">
                                {{ __('RG') }}</label>
                            <input id="nu_rg" class="form-control" type="text"
                                name="nu_rg" value="{{ old('nu_rg', $inscricao->nu_rg) }}" required>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="orgao_expedidor" class="form-label">
                                {{ __('Orgão expedidor (Sigla)') }}</label>
                            <input id="orgao_expedidor" class="form-control @error('orgao_expedidor') is-invalid @enderror"
                                type="text" placeholder="Sigla do orgão expedidor" name="orgao_expedidor"
                                value="{{ old('orgao_expedidor', $candidato->orgao_expedidor) }}" required>

                            @error('orgao_expedidor')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group col-md-3">
                            <label for="uf_rg" class="form-label">
                                {{ __('UF do orgão expedidor') }}</label>
                            <select id="uf_rg"
                                class="form-control @error('uf_rg') is-invalid @enderror"
                                name="uf_rg" required>
                                <option value="" selected disabled>-- Selecione a UF --</option>
                                <option value="AC" @selected (old('uf_rg', $candidato->uf_rg) == 'AC')>
                                    Acre</option>
                                <option value="AL" @selected (old('uf_rg', $candidato->uf_rg) == 'AL')>
                                    Alagoas</option>
                                <option value="AP" @selected (old('uf_rg', $candidato->uf_rg) == 'AP')>
                                    Amapá</option>
                                <option value="AM" @selected (old('uf_rg', $candidato->uf_rg) == 'AM')>
                                    Amazonas</option>
                                <option value="BA" @selected (old('uf_rg', $candidato->uf_rg) == 'BA')>
                                    Bahia</option>
                                <option value="CE" @selected (old('uf_rg', $candidato->uf_rg) == 'CE')>
                                    Ceará</option>
                                <option value="DF" @selected (old('uf_rg', $candidato->uf_rg) == 'DF')>
                                    Distrito Federal</option>
                                <option value="ES" @selected (old('uf_rg', $candidato->uf_rg) == 'ES')>
                                    Espírito Santo</option>
                                <option value="GO" @selected (old('uf_rg', $candidato->uf_rg) == 'GO')>
                                    Goiás</option>
                                <option value="MA" @selected (old('uf_rg', $candidato->uf_rg) == 'MA')>
                                    Maranhão</option>
                                <option value="MT" @selected (old('uf_rg', $candidato->uf_rg) == 'MT')>
                                    Mato Grosso</option>
                                <option value="MS" @selected (old('uf_rg', $candidato->uf_rg) == 'MS')>
                                    Mato Grosso do Sul</option>
                                <option value="MG" @selected (old('uf_rg', $candidato->uf_rg) == 'MG')>
                                    Minas Gerais</option>
                                <option value="PA" @selected (old('uf_rg', $candidato->uf_rg) == 'PA')>
                                    Pará</option>
                                <option value="PB" @selected (old('uf_rg', $candidato->uf_rg) == 'PB')>
                                    Paraíba</option>
                                <option value="PR" @selected (old('uf_rg', $candidato->uf_rg) == 'PR')>
                                    Paraná</option>
                                <option value="PE" @selected (old('uf_rg', $candidato->uf_rg) == 'PE')>
                                    Pernambuco</option>
                                <option value="PI" @selected (old('uf_rg', $candidato->uf_rg) == 'PI')>
                                    Piauí</option>
                                <option value="RJ" @selected (old('uf_rg', $candidato->uf_rg) == 'RJ')>
                                    Rio de Janeiro</option>
                                <option value="RN" @selected (old('uf_rg', $candidato->uf_rg) == 'RN')>
                                    Rio Grande do Norte</option>
                                <option value="RS" @selected (old('uf_rg', $candidato->uf_rg) == 'RS')>
                                    Rio Grande do Sul</option>
                                <option value="RO" @selected (old('uf_rg', $candidato->uf_rg) == 'RO')>
                                    Rondônia</option>
                                <option value="RR" @selected (old('uf_rg', $candidato->uf_rg) == 'RR')>
                                    Roraima</option>
                                <option value="SC" @selected (old('uf_rg', $candidato->uf_rg) == 'SC')>
                                    Santa Catarina</option>
                                <option value="SP" @selected (old('uf_rg', $candidato->uf_rg) == 'SP')>
                                    São Paulo</option>
                                <option value="SE" @selected (old('uf_rg', $candidato->uf_rg) == 'SE')>
                                    Sergipe</option>
                                <option value="TO" @selected (old('uf_rg', $candidato->uf_rg) == 'TO')>
                                    Tocantins</option>
                            </select>
                            @error('uf_rg')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group col-md-3">
                            <label for="data_expedicao" class="form-label">
                                {{ __('Data da expedição') }}</label>
                            <input id="data_expedicao"
                                class="form-control @error('data_expedicao') is-invalid @enderror"
                                type="date" name="data_expedicao"
                                value="{{ old('data_expedicao', $candidato->data_expedicao) }}" required>

                            @error('data_expedicao')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row pt-2">
                        <div class="form-group col-md-4">
                            <label for="titulo" class="form-label">{{ __('Título de eleitor') }}</label>
                            <input id="titulo"
                                class="form-control @error('titulo') is-invalid @enderror"
                                type="text" placeholder="Insira o número do título de eleitor" name="titulo"
                                value="{{ old('titulo', $candidato->titulo) }}">
                            @error('titulo')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group col-md-4">
                            <label for="zona_eleitoral" class="form-label">{{ __('Zona') }}</label>
                            <input id="zona_eleitoral"
                                class="form-control @error('zona_eleitoral') is-invalid @enderror"
                                type="text" placeholder="Insira a zona eleitoral" name="zona_eleitoral"
                                value="{{ old('zona_eleitoral', $candidato->zona_eleitoral) }}">
                            @error('zona_eleitoral')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group col-md-4">
                            <label for="secao_eleitoral" class="form-label">{{ __('Seção') }}</label>
                            <input id="secao_eleitoral"
                                class="form-control @error('secao_eleitoral') is-invalid @enderror"
                                type="text" placeholder="Insira a seção eleitoral" name="secao_eleitoral"
                                value="{{ old('secao_eleitoral', $candidato->secao_eleitoral) }}">
                            @error('secao_eleitoral')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row pt-2">
                        <div class="form-group col-md-4">
                            <label for="cidade_natal" class="form-label">
                                {{ __('Cidade natal') }}</label>
                            <input id="cidade_natal"
                                class="form-control @error('cidade_natal') is-invalid @enderror"
                                type="text" placeholder="Insira a cidade onde nasceu" name="cidade_natal"
                                value="{{ old('cidade_natal', $candidato->cidade_natal) }}" required>

                            @error('cidade_natal')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group col-md-4">
                            <label for="uf_natural" class="form-label">
                                {{ __('UF') }}</label>
                            <select id="uf_natural"
                                class="form-control @error('uf_natural') is-invalid @enderror"
                                name="uf_natural" required>
                                <option value="" selected disabled>-- Selecione a UF --</option>
                                <option value="AC" @selected (old('uf_natural', $candidato->uf_natural) == 'AC')>
                                    Acre</option>
                                <option value="AL" @selected (old('uf_natural', $candidato->uf_natural) == 'AL')>
                                    Alagoas</option>
                                <option value="AP" @selected (old('uf_natural', $candidato->uf_natural) == 'AP')>
                                    Amapá</option>
                                <option value="AM" @selected (old('uf_natural', $candidato->uf_natural) == 'AM')>
                                    Amazonas</option>
                                <option value="BA" @selected (old('uf_natural', $candidato->uf_natural) == 'BA')>
                                    Bahia</option>
                                <option value="CE" @selected (old('uf_natural', $candidato->uf_natural) == 'CE')>
                                    Ceará</option>
                                <option value="DF" @selected (old('uf_natural', $candidato->uf_natural) == 'DF')>
                                    Distrito Federal</option>
                                <option value="ES" @selected (old('uf_natural', $candidato->uf_natural) == 'ES')>
                                    Espírito Santo</option>
                                <option value="GO" @selected (old('uf_natural', $candidato->uf_natural) == 'GO')>
                                    Goiás</option>
                                <option value="MA" @selected (old('uf_natural', $candidato->uf_natural) == 'MA')>
                                    Maranhão</option>
                                <option value="MT" @selected (old('uf_natural', $candidato->uf_natural) == 'MT')>
                                    Mato Grosso</option>
                                <option value="MS" @selected (old('uf_natural', $candidato->uf_natural) == 'MS')>
                                    Mato Grosso do Sul</option>
                                <option value="MG" @selected (old('uf_natural', $candidato->uf_natural) == 'MG')>
                                    Minas Gerais</option>
                                <option value="PA" @selected (old('uf_natural', $candidato->uf_natural) == 'PA')>
                                    Pará</option>
                                <option value="PB" @selected (old('uf_natural', $candidato->uf_natural) == 'PB')>
                                    Paraíba</option>
                                <option value="PR" @selected (old('uf_natural', $candidato->uf_natural) == 'PR')>
                                    Paraná</option>
                                <option value="PE" @selected (old('uf_natural', $candidato->uf_natural) == 'PE')>
                                    Pernambuco</option>
                                <option value="PI" @selected (old('uf_natural', $candidato->uf_natural) == 'PI')>
                                    Piauí</option>
                                <option value="RJ" @selected (old('uf_natural', $candidato->uf_natural) == 'RJ')>
                                    Rio de Janeiro</option>
                                <option value="RN" @selected (old('uf_natural', $candidato->uf_natural) == 'RN')>
                                    Rio Grande do Norte</option>
                                <option value="RS" @selected (old('uf_natural', $candidato->uf_natural) == 'RS')>
                                    Rio Grande do Sul</option>
                                <option value="RO" @selected (old('uf_natural', $candidato->uf_natural) == 'RO')>
                                    Rondônia</option>
                                <option value="RR" @selected (old('uf_natural', $candidato->uf_natural) == 'RR')>
                                    Roraima</option>
                                <option value="SC" @selected (old('uf_natural', $candidato->uf_natural) == 'SC')>
                                    Santa Catarina</option>
                                <option value="SP" @selected (old('uf_natural', $candidato->uf_natural) == 'SP')>
                                    São Paulo</option>
                                <option value="SE" @selected (old('uf_natural', $candidato->uf_natural) == 'SE')>
                                    Sergipe</option>
                                <option value="TO" @selected (old('uf_natural', $candidato->uf_natural) == 'TO')>
                                    Tocantins</option>
                            </select>
                            @error('uf_natural')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group col-md-4">
                            <label for="pais_natural" class="form-label">
                                {{ __('Nacionalidade') }}</label>
                            <select id="pais_natural"
                                class="form-control @error('pais_natural') is-invalid @enderror"
                                name="pais_natural" required>
                                <option value="" selected disabled>-- Selecione o país onde nasceu --
                                </option>
                                <option value="BRA" @if (old('pais_natural', $candidato->pais_natural) == 'BRA') ) selected @endif>Brasil
                                </option>
                                <option disabled>────────────────────────────</option>
                                @foreach (\App\Models\Candidato::PAISES_ESTRANGEIROS as $valor => $pais_natural)
                                    <option value="{{ $valor }}"
                                        @if (old('pais_natural', $candidato->pais_natural) == $valor) ) selected @endif>
                                        {{ $pais_natural }}</option>
                                @endforeach
                            </select>
                            @error('pais_natural')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="form-group col-md-4">
                            <label for="estado_civil" class="form-label">
                                {{ __('Estado civil') }}</label>
                            <select id="estado_civil" class="form-control @error('estado_civil') is-invalid @enderror"
                                name="estado_civil" required>
                                <option value="" disabled selected>-- Selecione --</option>
                                @foreach (\App\Models\Candidato::ESTADO_CIVIL as $valor => $estado_civil)
                                    <option value="{{ $valor }}" @selected(old('estado_civil', $candidato->estado_civil) == $valor)>
                                        {{ $estado_civil }}</option>
                                @endforeach
                            </select>
                            @error('estado_civil')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group col-md-4">
                            <label for="tp_sexo" class="form-label">{{ __('Sexo') }}</label>
                            <select id="tp_sexo" name="tp_sexo" class="form-control" required>
                                <option value="M" @selected (old('tp_sexo', $inscricao->tp_sexo) == 'M')>
                                    Masculino</option>
                                <option value="F" @selected (old('tp_sexo', $inscricao->tp_sexo) == 'F')>
                                    Feminino</option>
                            </select>
                            @error('tp_sexo')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group col-md-4">
                            <label for="etnia_e_cor" class="form-label">
                                {{ __('Cor/Raça') }}</label>
                            <select id="etnia_e_cor"
                                class="form-control @error('etnia_e_cor') is-invalid @enderror"
                                name="etnia_e_cor" required>
                                <option value="" selected disabled>-- Selecione --</option>
                                <option value="1" @selected (old('etnia_e_cor', $candidato->etnia_e_cor) == 1)>
                                    {{ $cores_racas[0] }}</option>
                                <option value="4" @selected (old('etnia_e_cor', $candidato->etnia_e_cor) == 4)>
                                    {{ $cores_racas[3] }}</option>
                                <option value="2" @selected (old('etnia_e_cor', $candidato->etnia_e_cor) == 2)>
                                    {{ $cores_racas[1] }}</option>
                                <option value="3" @selected (old('etnia_e_cor', $candidato->etnia_e_cor) == 3)>
                                    {{ $cores_racas[2] }}</option>
                                <option value="5" @selected (old('etnia_e_cor', $candidato->etnia_e_cor) == 5)>
                                    {{ $cores_racas[4] }}</option>
                            </select>
                            @error('etnia_e_cor')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="form-group col-md-6">
                            <label for="quilombola" class="form-label">
                                {{ __('Quilombola') }}</label>
                            <select id="quilombola"
                                class="form-control @error('quilombola') is-invalid @enderror"
                                name="quilombola" required>
                                <option value="" selected disabled>-- Selecione --
                                </option>
                                <option value="1" @if ($candidato->quilombola == true) selected @endif>
                                    Sim
                                </option>
                                <option value="0" @if ($candidato->quilombola == false) selected @endif>
                                    Não
                                </option>
                            </select>
                            @error('quilombola')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="indigena" class="form-label">
                                {{ __('Indígena') }}</label>
                            <select id="indigena" class="form-control @error('indigena') is-invalid @enderror"
                                name="indigena" required>
                                <option value="" selected disabled>-- Selecione --
                                </option>
                                <option value="1" @if ($candidato->indigena == true) selected @endif>
                                    Sim
                                </option>
                                <option value="0" @if ($candidato->indigena == false) selected @endif>
                                    Não
                                </option>
                            </select>
                            @error('indigena')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row pt-2">
                        <div class="form-group col-md-6">
                            <label for="no_mae" class="form-label">
                                {{ __('Nome da mãe') }}</label>
                            <input id="no_mae" class="form-control" type="text"
                                value="{{ $inscricao->no_mae }}" required disabled>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="pai" class="form-label">{{ __('Nome do pai') }}</label>
                            <input id="pai" class="form-control @error('pai') is-invalid @enderror"
                                type="text" placeholder="Insira o nome do pai" name="pai"
                                value="{{ old('pai', $candidato->pai) }}">
                            @error('pai')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group mt-2">
                        <label for="ds_logradouro" class="form-label">
                            {{ __('Endereço') }}</label>
                        <input id="ds_logradouro"
                            class="form-control @error('ds_logradouro') is-invalid @enderror"
                            type="text" placeholder="Insira o endereço" name="ds_logradouro"
                            value="{{ old('ds_logradouro', $inscricao->ds_logradouro) }}" required>

                        @error('ds_logradouro')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="row pt-2">
                        <div class="form-group col-md-4">
                            <label for="nu_endereco" class="form-label">
                                {{ __('Número') }}</label>
                            <input id="nu_endereco"
                                class="form-control @error('nu_endereco') is-invalid @enderror"
                                type="text" placeholder="Insira o número do endereço" name="nu_endereco"
                                value="{{ old('nu_endereco', $inscricao->nu_endereco) }}" required>

                            @error('nu_endereco')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group col-md-4">
                            <label for="nu_cep" class="form-label">
                                {{ __('CEP') }}</label>
                            <input id="nu_cep"
                                class="form-control @error('nu_cep') is-invalid @enderror"
                                type="text" placeholder="Insira o nu_CEP" name="nu_cep"
                                value="{{ old('nu_cep', $inscricao->nu_cep) }}" required>

                            @error('nu_cep')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group col-md-4">
                            <label for="ds_complemento" class="form-label">{{ __('Complemento') }}</label>
                            <input id="ds_complemento"
                                class="form-control @error('ds_complemento') is-invalid @enderror"
                                type="text" placeholder="Insira o complemento, se houver" name="ds_complemento"
                                value="{{ old('ds_complemento', $inscricao->ds_complemento) }}" required>

                            @error('ds_complemento')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row pt-2">
                        <div class="form-group col-md-4">
                            <label for="no_bairro" class="form-label">
                                {{ __('Bairro') }}</label>
                            <input id="no_bairro"
                                class="form-control @error('no_bairro') is-invalid @enderror"
                                type="text" placeholder="Insira o bairro do endereço" name="no_bairro"
                                value="{{ old('no_bairro', $inscricao->no_bairro) }}" required>
                            @error('no_bairro')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group col-md-4">
                            <label for="no_municipio" class="form-label">
                                {{ __('Cidade') }}</label>
                            <input id="no_municipio"
                                class="form-control @error('no_municipio') is-invalid @enderror"
                                type="text" placeholder="Insira o municipio do endereço" name="no_municipio"
                                value="{{ old('no_municipio', $inscricao->no_municipio) }}" required>
                            @error('no_municipio')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group col-md-4">
                            <label for="sg_uf_inscrito" class="form-label">
                                {{ __('UF') }}</label>
                            <select id="sg_uf_inscrito"
                                class="form-control @error('sg_uf_inscrito') is-invalid @enderror"
                                name="sg_uf_inscrito" required>
                                <option value="" selected disabled>-- Selecione a UF --</option>
                                <option value="AC" @if (old('sg_uf_inscrito', $inscricao->sg_uf_inscrito) == 'AC') selected @endif>
                                    Acre</option>
                                <option value="AL" @if (old('sg_uf_inscrito', $inscricao->sg_uf_inscrito) == 'AL') selected @endif>
                                    Alagoas</option>
                                <option value="AP" @if (old('sg_uf_inscrito', $inscricao->sg_uf_inscrito) == 'AP') selected @endif>
                                    Amapá</option>
                                <option value="AM" @if (old('sg_uf_inscrito', $inscricao->sg_uf_inscrito) == 'AM') selected @endif>
                                    Amazonas</option>
                                <option value="BA" @if (old('sg_uf_inscrito', $inscricao->sg_uf_inscrito) == 'BA') selected @endif>
                                    Bahia</option>
                                <option value="CE" @if (old('sg_uf_inscrito', $inscricao->sg_uf_inscrito) == 'CE') selected @endif>
                                    Ceará</option>
                                <option value="DF" @if (old('sg_uf_inscrito', $inscricao->sg_uf_inscrito) == 'DF') selected @endif>
                                    Distrito Federal</option>
                                <option value="ES" @if (old('sg_uf_inscrito', $inscricao->sg_uf_inscrito) == 'ES') selected @endif>
                                    Espírito Santo</option>
                                <option value="GO" @if (old('sg_uf_inscrito', $inscricao->sg_uf_inscrito) == 'GO') selected @endif>
                                    Goiás</option>
                                <option value="MA" @if (old('sg_uf_inscrito', $inscricao->sg_uf_inscrito) == 'MA') selected @endif>
                                    Maranhão</option>
                                <option value="MT" @if (old('sg_uf_inscrito', $inscricao->sg_uf_inscrito) == 'MT') selected @endif>
                                    Mato Grosso</option>
                                <option value="MS" @if (old('sg_uf_inscrito', $inscricao->sg_uf_inscrito) == 'MS') selected @endif>
                                    Mato Grosso do Sul</option>
                                <option value="MG" @if (old('sg_uf_inscrito', $inscricao->sg_uf_inscrito) == 'MG') selected @endif>
                                    Minas Gerais</option>
                                <option value="PA" @if (old('sg_uf_inscrito', $inscricao->sg_uf_inscrito) == 'PA') selected @endif>
                                    Pará</option>
                                <option value="PB" @if (old('sg_uf_inscrito', $inscricao->sg_uf_inscrito) == 'PB') selected @endif>
                                    Paraíba</option>
                                <option value="PR" @if (old('sg_uf_inscrito', $inscricao->sg_uf_inscrito) == 'PR') selected @endif>
                                    Paraná</option>
                                <option value="PE" @if (old('sg_uf_inscrito', $inscricao->sg_uf_inscrito) == 'PE') selected @endif>
                                    Pernambuco</option>
                                <option value="PI" @if (old('sg_uf_inscrito', $inscricao->sg_uf_inscrito) == 'PI') selected @endif>
                                    Piauí</option>
                                <option value="RJ" @if (old('sg_uf_inscrito', $inscricao->sg_uf_inscrito) == 'RJ') selected @endif>
                                    Rio de Janeiro</option>
                                <option value="RN" @if (old('sg_uf_inscrito', $inscricao->sg_uf_inscrito) == 'RN') selected @endif>
                                    Rio Grande do Norte</option>
                                <option value="RS" @if (old('sg_uf_inscrito', $inscricao->sg_uf_inscrito) == 'RS') selected @endif>
                                    Rio Grande do Sul</option>
                                <option value="RO" @if (old('sg_uf_inscrito', $inscricao->sg_uf_inscrito) == 'RO') selected @endif>
                                    Rondônia</option>
                                <option value="RR" @if (old('sg_uf_inscrito', $inscricao->sg_uf_inscrito) == 'RR') selected @endif>
                                    Roraima</option>
                                <option value="SC" @if (old('sg_uf_inscrito', $inscricao->sg_uf_inscrito) == 'SC') selected @endif>Santa
                                    Catarina
                                </option>
                                <option value="SP" @if (old('sg_uf_inscrito', $inscricao->sg_uf_inscrito) == 'SP') selected @endif>São
                                    Paulo</option>
                                <option value="SE" @if (old('sg_uf_inscrito', $inscricao->sg_uf_inscrito) == 'SE') selected @endif>Sergipe
                                </option>
                                <option value="TO" @if (old('sg_uf_inscrito', $inscricao->sg_uf_inscrito) == 'TO') selected @endif>
                                    Tocantins</option>
                            </select>

                            @error('uf')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row pt-2">
                        <div class="form-group col-md-6">
                            <label for="reside" class="form-label">
                                {{ __('Qual a Cidade/Estado onde você reside atualmente?') }}</label>
                            <input id="reside"
                                class="form-control @error('reside') is-invalid @enderror"
                                type="text" placeholder="Cidade/UF" name="reside"
                                value="{{ old('reside', $candidato->reside) }}" required>
                            @error('reside')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="localidade" class="form-label">
                                {{ __('Qual a zona de sua moradia atual?') }}</label>
                            <select id="localidade"
                                class="form-control @error('localidade') is-invalid @enderror"
                                name="localidade" required>
                                <option value="" selected disabled>-- Selecione --</option>
                                <option value="zona_rural" @selected(old('localidade', $candidato->localidade) == 'zona_rural')>
                                    Zona Rural</option>
                                <option value="zona_urbana" @selected(old('localidade', $candidato->localidade) == 'zona_urbana')>
                                    Zona Urbana
                                </option>
                            </select>

                            @error('localidade')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row pt-2">
                        <div class="form-group col-md-6">
                            <label for="escola_ens_med" class="form-label">
                                {{ __('Estabelecimento que concluiu o Ensino Médio:') }}</label>
                            <input id="escola_ens_med"
                                class="form-control @error('escola_ens_med') is-invalid @enderror"
                                type="text" placeholder="Insira o nome da escola" name="escola_ens_med"
                                value="{{ old('escola_ens_med', $candidato->escola_ens_med) }}" required>
                            @error('escola_ens_med')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="concluiu_publica" class="form-label">
                                {{ __('Concluiu o Ensino Médio na rede pública?') }}</label>
                            <select id="concluiu_publica"
                                class="form-control @error('concluiu_publica') is-invalid @enderror"
                                name="concluiu_publica" required>
                                <option value="" selected disabled>-- Selecione --</option>
                                <option value="1" @selected(old('concluiu_publica', $candidato->concluiu_publica) == '1')>Sim
                                </option>
                                <option value="0" @selected(old('concluiu_publica', $candidato->concluiu_publica) == '0')>Não
                                </option>
                            </select>
                            @error('concluiu_publica')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row pt-2">
                        <div class="form-group col-md-4">
                            <label for="uf_escola" class="form-label">
                                {{ __('UF') }}</label>
                            <select id="uf_escola"
                                class="form-control @error('uf_escola') is-invalid @enderror"
                                name="uf_escola" required>
                                <option value="" selected disabled>-- Selecione a UF --</option>
                                <option value="AC" @if (old('uf_escola', $candidato->uf_escola) == 'AC') selected @endif>Acre
                                </option>
                                <option value="AL" @if (old('uf_escola', $candidato->uf_escola) == 'AL') selected @endif>Alagoas
                                </option>
                                <option value="AP" @if (old('uf_escola', $candidato->uf_escola) == 'AP') selected @endif>Amapá
                                </option>
                                <option value="AM" @if (old('uf_escola', $candidato->uf_escola) == 'AM') selected @endif>
                                    Amazonas</option>
                                <option value="BA" @if (old('uf_escola', $candidato->uf_escola) == 'BA') selected @endif>Bahia
                                </option>
                                <option value="CE" @if (old('uf_escola', $candidato->uf_escola) == 'CE') selected @endif>Ceará
                                </option>
                                <option value="DF" @if (old('uf_escola', $candidato->uf_escola) == 'DF') selected @endif>
                                    Distrito Federal
                                </option>
                                <option value="ES" @if (old('uf_escola', $candidato->uf_escola) == 'ES') selected @endif>
                                    Espírito Santo
                                </option>
                                <option value="GO" @if (old('uf_escola', $candidato->uf_escola) == 'GO') selected @endif>Goiás
                                </option>
                                <option value="MA" @if (old('uf_escola', $candidato->uf_escola) == 'MA') selected @endif>
                                    Maranhão</option>
                                <option value="MT" @if (old('uf_escola', $candidato->uf_escola) == 'MT') selected @endif>Mato
                                    Grosso
                                </option>
                                <option value="MS" @if (old('uf_escola', $candidato->uf_escola) == 'MS') selected @endif>Mato
                                    Grosso do Sul
                                </option>
                                <option value="MG" @if (old('uf_escola', $candidato->uf_escola) == 'MG') selected @endif>Minas
                                    Gerais
                                </option>
                                <option value="PA" @if (old('uf_escola', $candidato->uf_escola) == 'PA') selected @endif>Pará
                                </option>
                                <option value="PB" @if (old('uf_escola', $candidato->uf_escola) == 'PB') selected @endif>
                                    Paraíba</option>
                                <option value="PR" @if (old('uf_escola', $candidato->uf_escola) == 'PR') selected @endif>Paraná
                                </option>
                                <option value="PE" @if (old('uf_escola', $candidato->uf_escola) == 'PE') selected @endif>
                                    Pernambuco
                                </option>
                                <option value="PI" @if (old('uf_escola', $candidato->uf_escola) == 'PI') selected @endif>Piauí
                                </option>
                                <option value="RJ" @if (old('uf_escola', $candidato->uf_escola) == 'RJ') selected @endif>Rio de
                                    Janeiro
                                </option>
                                <option value="RN" @if (old('uf_escola', $candidato->uf_escola) == 'RN') selected @endif>Rio
                                    Grande do
                                    Norte</option>
                                <option value="RS" @if (old('uf_escola', $candidato->uf_escola) == 'RS') selected @endif>Rio
                                    Grande do Sul
                                </option>
                                <option value="RO" @if (old('uf_escola', $candidato->uf_escola) == 'RO') selected @endif>
                                    Rondônia</option>
                                <option value="RR" @if (old('uf_escola', $candidato->uf_escola) == 'RR') selected @endif>
                                    Roraima</option>
                                <option value="SC" @if (old('uf_escola', $candidato->uf_escola) == 'SC') selected @endif>Santa
                                    Catarina
                                </option>
                                <option value="SP" @if (old('uf_escola', $candidato->uf_escola) == 'SP') selected @endif>São
                                    Paulo</option>
                                <option value="SE" @if (old('uf_escola', $candidato->uf_escola) == 'SE') selected @endif>
                                    Sergipe</option>
                                <option value="TO" @if (old('uf_escola', $candidato->uf_escola) == 'TO') selected @endif>
                                    Tocantins</option>
                            </select>

                            @error('uf_escola')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group col-md-4">
                            <label for="ano_conclusao" class="form-label">
                                {{ __('Ano de Conclusão') }}</label>
                            <input id="ano_conclusao"
                                class="form-control @error('ano_conclusao') is-invalid @enderror"
                                type="text" placeholder="Insira o ano de conclusão do ensino médio"
                                name="ano_conclusao" value="{{ old('ano_conclusao', $candidato->ano_conclusao) }}" required>
                            @error('ano_conclusao')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group col-md-4">
                            <label for="modalidade" class="form-label">
                                {{ __('Modalidade') }}</label>
                            <select id="modalidade"
                                class="form-control @error('modalidade') is-invalid @enderror"
                                name="modalidade" required>
                                <option value="" disabled selected>-- Selecione --</option>
                                <option value="{{ old('modalidade', 'regular') }}"
                                    @if (old('modalidade', $candidato->modalidade) == 'regular') selected @endif>Regular</option>
                                <option value="{{ old('modalidade', 'tecnico_integrado') }}"
                                    @if (old('modalidade', $candidato->modalidade) == 'tecnico_integrado') selected @endif>Técnico Integrado
                                </option>
                                <option value="{{ old('modalidade', 'eja') }}"
                                    @if (old('modalidade', $candidato->modalidade) == 'eja') selected @endif>EJA</option>
                                <option value="{{ old('modalidade', 'certificacao_enem_encceja') }}"
                                    @if (old('modalidade', $candidato->modalidade) == 'certificacao_enem_encceja') selected @endif>Certificação
                                    Enem/Encceja</option>
                                <option value="{{ old('modalidade', 'outro') }}"
                                    @if (old('modalidade', $candidato->modalidade) == 'outro') selected @endif>Outro</option>
                            </select>

                            @error('modalidade')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row pt-2">
                        <div class="form-group">
                            <label for="concluiu_publica" class="form-label">
                                {{ __('Concluiu o Ensino Médio em escolas comunitárias que atuam no âmbito da educação do campo conveniadas com o poder público?') }}</label>
                            <select id="concluiu_comunitaria"
                                class="form-control @error('concluiu_comunitaria') is-invalid @enderror"
                                name="concluiu_comunitaria" required>
                                <option value="" selected disabled>-- Selecione --</option>
                                <option value="1" @if (old('concluiu_comunitaria', $candidato->concluiu_comunitaria) == '1') selected @endif>Sim
                                </option>
                                <option value="0" @if (old('concluiu_comunitaria', $candidato->concluiu_comunitaria) == '0') selected @endif>Não
                                </option>
                            </select>

                            @error('concluiu_comunitaria')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row pt-2">
                        <div class="form-group col-md-4">
                            <label for="nu_fone1" class="form-label">
                                {{ __('Celular') }}</label>
                            <input id="nu_fone1"
                                class="form-control @error('nu_fone1') is-invalid @enderror celular"
                                type="text" name="nu_fone1" value="{{ old('nu_fone1', $inscricao->nu_fone1) }}" required>
                            @error('nu_fone1')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group col-md-4">
                            <label for="nu_fone2" class="form-label">{{ __('Celular') }}</label>
                            <input id="nu_fone2"
                                class="form-control @error('nu_fone2') is-invalid @enderror celular"
                                type="text" name="nu_fone2" value="{{ old('nu_fone2', $inscricao->nu_fone2) }}">

                            @error('nu_fone2')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                    </div>
                    <div class="row pt-2">
                        <div class="form-group col-md-4">
                            <label for="nome_contato_emergencia" class="form-label">
                                {{ __('Nome do Contato de Emergência') }}</label>
                            <input id="nome_contato_emergencia"
                                class="form-control @error('nome_contato_emergencia') is-invalid @enderror"
                                type="text" name="nome_contato_emergencia"
                                value="{{ old('nome_contato_emergencia', $candidato->nome_contato_emergencia) }}" required>
                            @error('nome_contato_emergencia')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group col-md-4">
                            <label for="parentesco_contato_emergencia" class="form-label">
                                {{ __('Parentesco do Contato de Emergência') }} <span class="text-muted">(ex.: pai, mãe, irmão, amigo e outros)</span></label>
                            <input id="parentesco_contato_emergencia"
                                class="form-control @error('parentesco_contato_emergencia') is-invalid @enderror"
                                type="text" name="parentesco_contato_emergencia"
                                value="{{ old('parentesco_contato_emergencia', $candidato->parentesco_contato_emergencia) }}" required>

                                @error('parentesco_contato_emergencia')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group col-md-4">
                            <label for="nu_fone_emergencia" class="form-label">
                                {{ __('Contato de Emergência') }}</label>
                            <input id="nu_fone_emergencia"
                                class="form-control @error('nu_fone_emergencia') is-invalid @enderror celular"
                                type="text" name="nu_fone_emergencia"
                                value="{{ old('nu_fone_emergencia', $inscricao->nu_fone_emergencia) }}" required>
                            @error('nu_fone_emergencia')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row pt-2">
                        <fieldset class="form-group col-md-5">
                            <legend class="form-label required">
                                {{ __('Possui alguma deficiência?') }}
                            </legend>

                            @php
                                $desconsiderar = [2017, 2013, 2014, 2015, 2016]
                            @endphp
                            <div class="border p-2 rounded @error('necessidades') is-invalid @enderror"
                                style="max-height: 220px; overflow-y: auto;">
                                @foreach (\App\Models\Candidato::NECESSIDADES as $valor => $necessidade)
                                    @continue(in_array($valor, $desconsiderar, true))
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="{{ $valor }}"
                                            id="{{ 'checkbox' . $valor }}" name="necessidades[]"
                                            @checked (in_array($valor, old('necessidades', array_map('trim', explode(',', $candidato->necessidades)))))>
                                        <label class="form-check-label" for="{{ 'checkbox' . $valor }}">
                                            {{ $necessidade }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            @error('necessidades')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </fieldset>
                        <div class="col-md-7">
                            <div class="form-group">
                                <label for="trabalha" class="form-label">
                                    {{ __('Você exerce alguma atividade remunerada?') }}</label>
                                <select id="trabalha"
                                    class="form-control @error('trabalha') is-invalid @enderror"
                                    name="trabalha" required>
                                    <option value="" selected disabled>-- Selecione --
                                    </option>
                                    <option value="1" @if (old('trabalha', $candidato->trabalha) == '1') selected @endif>
                                        Sim
                                    </option>
                                    <option value="0" @if (old('trabalha', $candidato->trabalha) == '0') selected @endif>
                                        Não
                                    </option>
                                </select>
                                @error('trabalha')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group mt-2">
                                <label for="grupo_familiar" class="form-label">
                                    {{ __('Quantas pessoas fazem parte do seu grupo familiar?') }}</label>
                                <input id="grupo_familiar"
                                    class="form-control @error('grupo_familiar') is-invalid @enderror"
                                    type="number" name="grupo_familiar"
                                    value="{{ old('grupo_familiar', $candidato->grupo_familiar) }}">

                                @error('grupo_familiar')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="pt-2">
                                <div class="form-group">
                                    <label for="valor_renda" class="form-label">
                                        {{ __('Qual o valor da renda total (renda bruta) do seu grupo familiar?')}} <span class="text-muted">(Soma dos rendimentos de todo o grupo familiar, incluindo você) (Ex.: 1250,00)</span></label>
                                    <input id="valor_renda"
                                        class="form-control @error('valor_renda') is-invalid @enderror"
                                        type="number" step="0.1" placeholder="1250,00" name="valor_renda"
                                        value="{{ old('valor_renda', $candidato->valor_renda) }}" required>

                                    @error('valor_renda')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="row pt-2">
                        <fieldset class="col-md-6 form-group">
                            <legend class="form-label required">
                                Na sua moradia você dispõe de: <span class="text-muted">(Marcar mais de uma opção, se for o
                                    caso)</span>
                            </legend>

                            @php
                                $opcoes_moradia = [
                                    'banda_larga' => 'Internet banda larga',
                                    'internet_movel' => 'Internet móvel (4G)',
                                    'smartphone' => 'Celular/smartphone',
                                    'computador' => 'Computador/Notebook',
                                    'tablet' => 'Tablet',
                                    'nenhuma' => 'Não disponho de nenhuma das opções acima',
                                ];
                            @endphp

                            <div class="card card-body w-75 @error('dispositivos_moradia') is-invalid @enderror">
                                @foreach ($opcoes_moradia as $val => $label)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                            id="dispositivos_moradia_{{ $val }}" name="dispositivos_moradia[]"
                                            value="{{ $val }}"
                                            @checked(in_array($val, old('dispositivos_moradia', $candidato->dispositivos_moradia ?? [], true)))>
                                        <label class="form-check-label"
                                            for="dispositivos_moradia_{{ $val }}">{{ $label }}</label>
                                    </div>
                                @endforeach
                            </div>

                            @error('dispositivos_moradia')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </fieldset>

                        <div class="col-md-6">
                            <fieldset class="form-group">
                                <legend class="form-label required">Sua família está inscrita no CadÚnico?</legend>
                                <div class="d-flex gap-5 @error('cadunico') is-invalid @enderror">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="cadunico" id="cad_sim"
                                            value="sim" @checked(old('cadunico', $candidato->cadunico) === 'sim')>
                                        <label class="form-check-label" for="cad_sim">Sim</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="cadunico" id="cad_nao"
                                            value="nao" @checked(old('cadunico', $candidato->cadunico) === 'nao')>
                                        <label class="form-check-label" for="cad_nao">Não</label>
                                    </div>
                                </div>
                                @error('cadunico')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </fieldset>

                            <fieldset class="form-group pt-2">
                                <legend class="form-label required">
                                    Você tem filho/a(s) na primeira infância ou em idade escolar?
                                </legend>
                                @php
                                    $opcoes_filhos = [
                                        'primeira_infancia' => 'Sim, na primeira infância',
                                        'idade_escolar' => 'Sim, em idade escolar',
                                        'nao_tenho' => 'Não tenho',
                                    ];
                                @endphp
                                <div class="card card-body w-75 @error('filhos') is-invalid @enderror">
                                    @foreach ($opcoes_filhos as $val => $label)
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox"
                                                    id="filhos_{{ $val }}" name="filhos[]"
                                                    value="{{ $val }}"
                                                    @checked(in_array($val, old('filhos', $candidato->filhos ?? []), true))>
                                                <label class="form-check-label"
                                                    for="filhos_{{ $val }}">{{ $label }}</label>
                                            </div>
                                    @endforeach
                                </div>
                                @error('filhos')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </fieldset>
                        </div>
                    </div>

                    <fieldset class="form-group pt-2 d-none" id="div-gestante">
                        <legend class="form-label required">
                            Você está gestante? <span class="text-muted">(apenas para gênero feminino)</span>
                        </legend>

                        <div class="d-flex flex-column">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="gestante" id="gestante_sim"
                                    value="sim" @checked(old('gestante', $candidato->gestante) == 'sim')>
                                <label class="form-check-label" for="gestante_sim">Sim</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="gestante" id="gestante_nao"
                                    value="nao" @checked(old('gestante', $candidato->gestante) == 'nao')>
                                <label class="form-check-label" for="gestante_nao">Não</label>
                            </div>
                        </div>

                        @error('gestante')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </fieldset>

                    <div class="row pt-3">
                        <fieldset class="form-group col-md-6">
                            <legend class="form-label required">
                                Você é transgênero?
                            </legend>
                            <div class="row @error('transgenero') is-invalid @enderror">
                                @foreach (['sim' => 'Sim', 'nao' => 'Não', 'outro' => 'Outro', 'prefiro_nao_responder' => 'Prefiro não responder'] as $val => $label)
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio"
                                            name="transgenero" id="trans_{{ $val }}"
                                            value="{{ $val }}" @checked(old('transgenero', $candidato->transgenero) === $val)>
                                            <label class="form-check-label"
                                            for="trans_{{ $val }}">{{ $label }}</label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @error('transgenero')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </fieldset>
                        <fieldset class="form-group col-md-6">
                            <legend class="form-label required">
                                Você se reconhece no grupo LGBTQIAP+?
                            </legend>
                            <div class="row @error('lgbtqiap') is-invalid @enderror">
                                @foreach (['sim' => 'Sim', 'nao' => 'Não', 'outro' => 'Outro', 'prefiro_nao_responder' => 'Prefiro não responder'] as $val => $label)
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio"
                                            name="lgbtqiap" id="lgbt_{{ $val }}" value="{{ $val }}"
                                            @checked(old('lgbtqiap', $candidato->lgbtqiap) == $val)>
                                            <label class="form-check-label"
                                            for="lgbt_{{ $val }}">{{ $label }}</label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @error('lgbtqiap')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </fieldset>
                    </div>

                    <div class="row pt-2 d-none" id="div-nome-social">
                        <div class="form-group col-md-6">
                            <label for="no_social" class="form-label">{{ __('Nome social (para pessoas travestis ou transsexuais)') }}</label>
                            <input id="no_social" class="form-control" type="text"
                                name="no_social" value="{{ old('no_social', $candidato->no_social) }}">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="requerimento-nome-social" class="form-label">
                                </span>{{ __('Requerimento para inclusão de nome social') }} <a
                                    href="https://docs.google.com/document/d/1elnVkyCHGzaXuqaEWc1UxibqaMVtSxEz/edit"
                                    target="_blank"
                                    style="text-decoration: none">{{ __('(baixe o modelo aqui)') }}</a></label>
                            <input
                                class="form-control @error('requerimento_nome_social') is-invalid @enderror"
                                type="file" id="requerimento-nome-social" name="requerimento_nome_social"
                                value="{{ old('requerimento_nome_social') }}" accept=".pdf">

                            @error('requerimento_nome_social')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-5 alert alert-warning" role="alert">
                        <div class="form-check mt-2 @error('edital') is-invalid @enderror">
                            <input class="form-check-input" type="checkbox" value="true" id="checkEdital"
                                name="edital" @if (old('edital')) checked @endif>
                            <label class="form-check-label subtexto3" for="checkEdital">
                                DECLARO que li o Edital e estou de acordo com os termos e condições.
                            </label>
                        </div>
                        @error('edital')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        <div class="form-check mt-2 @error('vinculo') is-invalid @enderror">
                            <input class="form-check-input" type="checkbox" value="true" id="checkVinculo"
                                name="vinculo" @if (old('vinculo')) checked @endif>
                            <label class="form-check-label subtexto3" for="checkVinculo">
                                Estou ciente que não posso ter vínculo com mais de uma Instituição de Ensino
                                Superior pública.
                            </label>
                        </div>
                        @error('vinculo')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6" style="margin-bottom: 10px;">
                <div class="text-center">
                    <a @can('isAdmin', \App\Models\User::class) href="{{ route('inscricao.show.analisar.documentos', ['sisu_id' => $inscricao->chamada->sisu->id, 'chamada_id' => $inscricao->chamada->id, 'curso_id' => $inscricao->curso->id, 'inscricao_id' => $inscricao->id]) }}"
                                    @else href="{{ route('index') }}" @endcan
                        type="text" class="btn botaoEntrar col-md-10" style="width: 100%;">Voltar</a>
                </div>
            </div>
            <div class="col-md-6">
                <div class="text-center">
                    <button type="text" class="btn botaoEntrar col-md-10 submeterFormBotao"
                        form="primeiro-acesso-form" style="width: 100%;">Confirmar</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function($) {
            $('#cpf').mask('000.000.000-00');
            var SPMaskBehavior = function(val) {
                    return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
                },
                spOptions = {
                    onKeyPress: function(val, e, field, options) {
                        field.mask(SPMaskBehavior.apply({}, arguments), options);
                    }
                };
            $('.celular').mask(SPMaskBehavior, spOptions);
            $('#cep').mask('00000-000');
            $("#nome").mask("#", {
                maxlength: true,
                translation: {
                    '#': {
                        pattern: /^[A-Za-záâãéêíóôõúçÁÂÃÉÊÍÓÔÕÚÇ\s]+$/,
                        recursive: true
                    }
                }
            });
            $("#sobrenome").mask("#", {
                maxlength: true,
                translation: {
                    '#': {
                        pattern: /^[A-Za-záâãéêíóôõúçÁÂÃÉÊÍÓÔÕÚÇ\s]+$/,
                        recursive: true
                    }
                }
            });
            $("#nome_do_pai").mask("#", {
                maxlength: true,
                translation: {
                    '#': {
                        pattern: /^[A-Za-záâãéêíóôõúçÁÂÃÉÊÍÓÔÕÚÇ\s]+$/,
                        recursive: true
                    }
                }
            });
            $("#nome_do_mãe").mask("#", {
                maxlength: true,
                translation: {
                    '#': {
                        pattern: /^[A-Za-záâãéêíóôõúçÁÂÃÉÊÍÓÔÕÚÇ\s]+$/,
                        recursive: true
                    }
                }
            });
            $('#no_social').on('input', function() {
                let valor = $(this).val().trim();

                verificarNomeSocial(valor);
            });

            verificarNomeSocial($('#no_social').val().trim());


            function toggleNomeSocial() {
                const mostrar = $('#trans_sim').is(':checked') || $('#lgbt_sim').is(':checked');
                $('#div-nome-social').toggleClass('d-none', !mostrar);
            }

            function toggleGestante(){
                const mostrar = $('#tp_sexo').val() === 'F';
                if (mostrar) {
                    $('input[name="gestante"]').prop('disabled', false).prop('required', true);
                } else {
                    $('input[name="gestante"]').prop('required', false).prop('disabled', true);
                }
                $('#div-gestante').toggleClass('d-none', !mostrar);

            }

            $(document).ready(function () {
                toggleNomeSocial();
                toggleGestante();
                $(document).on('change', 'input[name="lgbtqiap"], input[name="transgenero"]', toggleNomeSocial);
                $(document).on('change', '#tp_sexo', toggleGestante);

            });
        });

        function verificarNomeSocial(valor) {
            let is_admin = {{ Auth::user()->role == App\Models\User::ROLE_ENUM['admin'] ? 'true' : 'false' }};
            let campo_requerimento = $('#requerimento-nome-social');
            let div_pai = campo_requerimento.parent();

            if (valor !== '' && !is_admin) {
                div_pai.show();
                campo_requerimento.prop('required', true);
            } else {
                div_pai.hide();
                campo_requerimento.prop('required', false);
                campo_requerimento.val('');
            }
        }
    </script>
</x-app-layout>
