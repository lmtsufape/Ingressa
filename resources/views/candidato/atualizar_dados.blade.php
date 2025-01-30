<x-app-layout>
    <div class="fundo px-5 py-5">
        <div class="container">
            <div class="py-3 px-4 row ms-0 justify-content-center">
                <div class="col-md-10 caixa shadow p-3 bg-white">
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
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
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
                                action="{{ route('candidato.atualizar', ['candidato' => $candidato, 'inscricao' => $inscricao]) }}">
                                @csrf
                                @method('PUT')
                                <div class="form-group mt-2 textoInput">
                                    <label for="no_social">{{ __('Nome social (para pessoas travestis ou transsexuais)') }}</label>
                                    <input id="no_social" class="form-control form-control-sm caixaDeTexto"
                                        type="text" name="no_social" value="{{ old('no_social', $candidato->no_social) }}">
                                </div>
                                <div class="row pt-2">
                                    <div class="form-group col-md-3 textoInput">
                                        <label for="nu_rg"><span style="color: red; font-weight: bold;">*</span>
                                            {{ __('RG') }}</label>
                                        <input id="nu_rg" class="form-control form-control-sm caixaDeTexto"
                                            type="text" name="nu_rg" value="{{ old('nu_rg', $inscricao->nu_rg) }}">
                                    </div>
                                    <div class="form-group col-md-3 textoInput">
                                        <label for="orgao_expedidor"><span
                                                style="color: red; font-weight: bold;">*</span>
                                            {{ __('Orgão expedidor (Sigla)') }}</label>
                                        <input id="orgao_expedidor"
                                            class="form-control form-control-sm caixaDeTexto @error('orgao_expedidor') is-invalid @enderror"
                                            type="text" placeholder="Sigla do orgão expedidor" name="orgao_expedidor"
                                            value="{{ old('orgao_expedidor', $candidato->orgao_expedidor) }}">
                                        @error('orgao_expedidor')
                                            <div id="validationServer03Feedback" class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-3 textoInput">
                                        <label for="uf_rg"><span style="color: red; font-weight: bold;">*</span>
                                            {{ __('UF do orgão expedidor') }}</label>
                                        <select id="uf_rg"
                                            class="form-control form-control-sm caixaDeTexto @error('uf_rg') is-invalid @enderror"
                                            name="uf_rg">
                                            <option value="" selected disabled>-- Selecione a UF --</option>
                                            <option value="AC" @if (old('uf_rg', $candidato->uf_rg) == 'AC') selected @endif>
                                                Acre</option>
                                            <option value="AL" @if (old('uf_rg', $candidato->uf_rg) == 'AL') selected @endif>
                                                Alagoas</option>
                                            <option value="AP" @if (old('uf_rg', $candidato->uf_rg) == 'AP') selected @endif>
                                                Amapá</option>
                                            <option value="AM" @if (old('uf_rg', $candidato->uf_rg) == 'AM') selected @endif>
                                                Amazonas</option>
                                            <option value="BA" @if (old('uf_rg', $candidato->uf_rg) == 'BA') selected @endif>
                                                Bahia</option>
                                            <option value="CE" @if (old('uf_rg', $candidato->uf_rg) == 'CE') selected @endif>
                                                Ceará</option>
                                            <option value="DF" @if (old('uf_rg', $candidato->uf_rg) == 'DF') selected @endif>
                                                Distrito Federal</option>
                                            <option value="ES" @if (old('uf_rg', $candidato->uf_rg) == 'ES') selected @endif>
                                                Espírito Santo</option>
                                            <option value="GO" @if (old('uf_rg', $candidato->uf_rg) == 'GO') selected @endif>
                                                Goiás</option>
                                            <option value="MA" @if (old('uf_rg', $candidato->uf_rg) == 'MA') selected @endif>
                                                Maranhão</option>
                                            <option value="MT" @if (old('uf_rg', $candidato->uf_rg) == 'MT') selected @endif>
                                                Mato Grosso</option>
                                            <option value="MS" @if (old('uf_rg', $candidato->uf_rg) == 'MS') selected @endif>
                                                Mato Grosso do Sul</option>
                                            <option value="MG" @if (old('uf_rg', $candidato->uf_rg) == 'MG') selected @endif>
                                                Minas Gerais</option>
                                            <option value="PA" @if (old('uf_rg', $candidato->uf_rg) == 'PA') selected @endif>
                                                Pará</option>
                                            <option value="PB" @if (old('uf_rg', $candidato->uf_rg) == 'PB') selected @endif>
                                                Paraíba</option>
                                            <option value="PR" @if (old('uf_rg', $candidato->uf_rg) == 'PR') selected @endif>
                                                Paraná</option>
                                            <option value="PE" @if (old('uf_rg', $candidato->uf_rg) == 'PE') selected @endif>
                                                Pernambuco</option>
                                            <option value="PI" @if (old('uf_rg', $candidato->uf_rg) == 'PI') selected @endif>
                                                Piauí</option>
                                            <option value="RJ" @if (old('uf_rg', $candidato->uf_rg) == 'RJ') selected @endif>
                                                Rio de Janeiro</option>
                                            <option value="RN" @if (old('uf_rg', $candidato->uf_rg) == 'RN') selected @endif>
                                                Rio Grande do Norte</option>
                                            <option value="RS" @if (old('uf_rg', $candidato->uf_rg) == 'RS') selected @endif>
                                                Rio Grande do Sul</option>
                                            <option value="RO" @if (old('uf_rg', $candidato->uf_rg) == 'RO') selected @endif>
                                                Rondônia</option>
                                            <option value="RR" @if (old('uf_rg', $candidato->uf_rg) == 'RR') selected @endif>
                                                Roraima</option>
                                            <option value="SC" @if (old('uf_rg', $candidato->uf_rg) == 'SC') selected @endif>
                                                Santa Catarina</option>
                                            <option value="SP" @if (old('uf_rg', $candidato->uf_rg) == 'SP') selected @endif>
                                                São Paulo</option>
                                            <option value="SE" @if (old('uf_rg', $candidato->uf_rg) == 'SE') selected @endif>
                                                Sergipe</option>
                                            <option value="TO" @if (old('uf_rg', $candidato->uf_rg) == 'TO') selected @endif>
                                                Tocantins</option>
                                        </select>
                                        @error('uf_rg')
                                            <div id="validationServer03Feedback" class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-3 textoInput">
                                        <label for="data_expedicao"><span
                                                style="color: red; font-weight: bold;">*</span>
                                            {{ __('Data da expedição') }}</label>
                                        <input id="data_expedicao"
                                            class="form-control form-control-sm caixaDeTexto @error('data_expedicao') is-invalid @enderror"
                                            type="date" name="data_expedicao"
                                            value="{{ old('data_expedicao', $candidato->data_expedicao) }}">
                                        @error('data_expedicao')
                                            <div id="validationServer03Feedback" class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row pt-2">
                                    <div class="form-group col-md-4 textoInput">
                                        <label for="titulo">{{ __('Título de eleitor') }}</label>
                                        <input id="titulo"
                                            class="form-control form-control-sm caixaDeTexto @error('titulo') is-invalid @enderror"
                                            type="text" placeholder="Insira o número do título de eleitor"
                                            name="titulo" value="{{ old('titulo', $candidato->titulo) }}">
                                        @error('titulo')
                                            <div id="validationServer03Feedback" class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-4 textoInput">
                                        <label for="zona_eleitoral">{{ __('Zona') }}</label>
                                        <input id="zona_eleitoral"
                                            class="form-control form-control-sm caixaDeTexto @error('zona_eleitoral') is-invalid @enderror"
                                            type="text" placeholder="Insira a zona eleitoral"
                                            name="zona_eleitoral"
                                            value="{{ old('zona_eleitoral', $candidato->zona_eleitoral) }}">
                                        @error('zona_eleitoral')
                                            <div id="validationServer03Feedback" class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-4 textoInput">
                                        <label for="secao_eleitoral">{{ __('Seção') }}</label>
                                        <input id="secao_eleitoral"
                                            class="form-control form-control-sm caixaDeTexto @error('secao_eleitoral') is-invalid @enderror"
                                            type="text" placeholder="Insira a seção eleitoral"
                                            name="secao_eleitoral"
                                            value="{{ old('secao_eleitoral', $candidato->secao_eleitoral) }}">
                                        @error('secao_eleitoral')
                                            <div id="validationServer03Feedback" class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row pt-2">
                                    <div class="form-group col-md-4 textoInput">
                                        <label for="cidade_natal"><span
                                                style="color: red; font-weight: bold;">*</span>
                                            {{ __('Cidade natal') }}</label>
                                        <input id="cidade_natal"
                                            class="form-control form-control-sm caixaDeTexto @error('cidade_natal') is-invalid @enderror"
                                            type="text" placeholder="Insira a cidade onde nasceu"
                                            name="cidade_natal"
                                            value="{{ old('cidade_natal', $candidato->cidade_natal) }}">
                                        @error('cidade_natal')
                                            <div id="validationServer03Feedback" class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-4 textoInput">
                                        <label for="uf_natural"><span style="color: red; font-weight: bold;">*</span>
                                            {{ __('UF') }}</label>
                                        <select id="uf_natural"
                                            class="form-control form-control-sm caixaDeTexto @error('uf_natural') is-invalid @enderror"
                                            name="uf_natural">
                                            <option value="" selected disabled>-- Selecione a UF --</option>
                                            <option value="AC" @if (old('uf_natural', $candidato->uf_natural) == 'AC') selected @endif>
                                                Acre</option>
                                            <option value="AL" @if (old('uf_natural', $candidato->uf_natural) == 'AL') selected @endif>
                                                Alagoas</option>
                                            <option value="AP" @if (old('uf_natural', $candidato->uf_natural) == 'AP') selected @endif>
                                                Amapá</option>
                                            <option value="AM" @if (old('uf_natural', $candidato->uf_natural) == 'AM') selected @endif>
                                                Amazonas</option>
                                            <option value="BA" @if (old('uf_natural', $candidato->uf_natural) == 'BA') selected @endif>
                                                Bahia</option>
                                            <option value="CE" @if (old('uf_natural', $candidato->uf_natural) == 'CE') selected @endif>
                                                Ceará</option>
                                            <option value="DF" @if (old('uf_natural', $candidato->uf_natural) == 'DF') selected @endif>
                                                Distrito Federal</option>
                                            <option value="ES" @if (old('uf_natural', $candidato->uf_natural) == 'ES') selected @endif>
                                                Espírito Santo</option>
                                            <option value="GO" @if (old('uf_natural', $candidato->uf_natural) == 'GO') selected @endif>
                                                Goiás</option>
                                            <option value="MA" @if (old('uf_natural', $candidato->uf_natural) == 'MA') selected @endif>
                                                Maranhão</option>
                                            <option value="MT" @if (old('uf_natural', $candidato->uf_natural) == 'MT') selected @endif>
                                                Mato Grosso</option>
                                            <option value="MS" @if (old('uf_natural', $candidato->uf_natural) == 'MS') selected @endif>
                                                Mato Grosso do Sul</option>
                                            <option value="MG" @if (old('uf_natural', $candidato->uf_natural) == 'MG') selected @endif>
                                                Minas Gerais</option>
                                            <option value="PA" @if (old('uf_natural', $candidato->uf_natural) == 'PA') selected @endif>
                                                Pará</option>
                                            <option value="PB" @if (old('uf_natural', $candidato->uf_natural) == 'PB') selected @endif>
                                                Paraíba</option>
                                            <option value="PR" @if (old('uf_natural', $candidato->uf_natural) == 'PR') selected @endif>
                                                Paraná</option>
                                            <option value="PE" @if (old('uf_natural', $candidato->uf_natural) == 'PE') selected @endif>
                                                Pernambuco</option>
                                            <option value="PI" @if (old('uf_natural', $candidato->uf_natural) == 'PI') selected @endif>
                                                Piauí</option>
                                            <option value="RJ" @if (old('uf_natural', $candidato->uf_natural) == 'RJ') selected @endif>
                                                Rio de Janeiro</option>
                                            <option value="RN" @if (old('uf_natural', $candidato->uf_natural) == 'RN') selected @endif>
                                                Rio Grande do Norte</option>
                                            <option value="RS" @if (old('uf_natural', $candidato->uf_natural) == 'RS') selected @endif>
                                                Rio Grande do Sul</option>
                                            <option value="RO" @if (old('uf_natural', $candidato->uf_natural) == 'RO') selected @endif>
                                                Rondônia</option>
                                            <option value="RR" @if (old('uf_natural', $candidato->uf_natural) == 'RR') selected @endif>
                                                Roraima</option>
                                            <option value="SC" @if (old('uf_natural', $candidato->uf_natural) == 'SC') selected @endif>
                                                Santa Catarina</option>
                                            <option value="SP" @if (old('uf_natural', $candidato->uf_natural) == 'SP') selected @endif>
                                                São Paulo</option>
                                            <option value="SE" @if (old('uf_natural', $candidato->uf_natural) == 'SE') selected @endif>
                                                Sergipe</option>
                                            <option value="TO" @if (old('uf_natural', $candidato->uf_natural) == 'TO') selected @endif>
                                                Tocantins</option>
                                        </select>
                                        @error('uf_natural')
                                            <div id="validationServer03Feedback" class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-4 textoInput">
                                        <label for="pais_natural"><span
                                                style="color: red; font-weight: bold;">*</span>
                                            {{ __('Nacionalidade') }}</label>
                                        <select id="pais_natural"
                                            class="form-control form-control-sm caixaDeTexto @error('pais_natural') is-invalid @enderror"
                                            name="pais_natural">
                                            <option value="" selected disabled>-- Selecione o país onde nasceu --
                                            </option>
                                            <option value="BRA"
                                                @if (old('pais_natural', $candidato->pais_natural) == 'BRA') ) selected @endif>Brasil</option>
                                            <option disabled>────────────────────────────</option>
                                            @foreach (\App\Models\Candidato::PAISES_ESTRANGEIROS as $valor => $pais_natural)
                                                <option value="{{ $valor }}"
                                                    @if (old('pais_natural', $candidato->pais_natural) == $valor) ) selected @endif>
                                                    {{ $pais_natural }}</option>
                                            @endforeach
                                        </select>
                                        @error('pais_natural')
                                            <div id="validationServer03Feedback" class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="form-group col-md-4 textoInput">
                                        <label for="estado_civil"><span
                                                style="color: red; font-weight: bold;">*</span>
                                            {{ __('Estado civil') }}</label>
                                        <select id="estado_civil"
                                            class="form-control form-control-sm caixaDeTexto @error('estado_civil') is-invalid @enderror"
                                            name="estado_civil">
                                            <option value="" disabled selected>-- Selecione --</option>
                                            @foreach (\App\Models\Candidato::ESTADO_CIVIL as $valor => $estado_civil)
                                                <option value="{{ $valor }}"
                                                    @if (old('estado_civil', $candidato->estado_civil) == $valor) ) selected @endif>
                                                    {{ $estado_civil }}</option>
                                            @endforeach
                                        </select>
                                        @error('estado_civil')
                                            <div id="validationServer03Feedback" class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-4 textoInput">
                                        <label for="tp_sexo">{{ __('Sexo') }}</label>
                                        <select id="tp_sexo" name="tp_sexo"
                                            class="form-control form-control-sm caixaDeTexto">
                                            <option value="M" @if ($inscricao->tp_sexo == 'M') selected @endif>
                                                Masculino</option>
                                            <option value="F" @if ($inscricao->tp_sexo == 'F') selected @endif>
                                                Feminino</option>
                                        </select>
                                        @error('tp_sexo')
                                            <div id="validationServer03Feedback" class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group mt-2 textoInput">
                                    <label for="no_mae"><span style="color: red; font-weight: bold;">*</span>
                                        {{ __('Nome da mãe') }}</label>
                                    <input id="no_mae" class="form-control form-control-sm caixaDeTexto"
                                        type="text" value="{{ $inscricao->no_mae }}" disabled>
                                </div>
                                <div class="form-group mt-2 textoInput">
                                    <label for="pai">{{ __('Nome do pai') }}</label>
                                    <input id="pai"
                                        class="form-control form-control-sm caixaDeTexto @error('pai') is-invalid @enderror"
                                        type="text" placeholder="Insira o nome do pai" name="pai"
                                        value="{{ old('pai', $candidato->pai) }}">
                                    @error('pai')
                                        <div id="validationServer03Feedback" class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="form-group mt-2 textoInput">
                                    <label for="ds_logradouro"><span style="color: red; font-weight: bold;">*</span>
                                        {{ __('Endereço') }}</label>
                                    <input id="ds_logradouro"
                                        class="form-control form-control-sm caixaDeTexto @error('ds_logradouro') is-invalid @enderror"
                                        type="text" placeholder="Insira o endereço" name="ds_logradouro"
                                        value="{{ old('ds_logradouro', $inscricao->ds_logradouro) }}">
                                    @error('ds_logradouro')
                                        <div id="validationServer03Feedback" class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="row pt-2">
                                    <div class="form-group col-md-4 textoInput">
                                        <label for="nu_endereco"><span style="color: red; font-weight: bold;">*</span>
                                            {{ __('Número') }}</label>
                                        <input id="nu_endereco"
                                            class="form-control form-control-sm caixaDeTexto @error('nu_endereco') is-invalid @enderror"
                                            type="text" placeholder="Insira o número do endereço"
                                            name="nu_endereco"
                                            value="{{ old('nu_endereco', $inscricao->nu_endereco) }}">
                                        @error('nu_endereco')
                                            <div id="validationServer03Feedback" class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-4 textoInput">
                                        <label for="nu_cep"><span style="color: red; font-weight: bold;">*</span>
                                            {{ __('CEP') }}</label>
                                        <input id="nu_cep"
                                            class="form-control form-control-sm caixaDeTexto @error('nu_cep') is-invalid @enderror"
                                            type="text" placeholder="Insira o nu_CEP" name="nu_cep"
                                            value="{{ old('nu_cep', $inscricao->nu_cep) }}">
                                        @error('nu_cep')
                                            <div id="validationServer03Feedback" class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-4 textoInput">
                                        <label for="ds_complemento">{{ __('Complemento') }}</label>
                                        <input id="ds_complemento"
                                            class="form-control form-control-sm caixaDeTexto @error('ds_complemento') is-invalid @enderror"
                                            type="text" placeholder="Insira o complemento, se houver"
                                            name="ds_complemento"
                                            value="{{ old('ds_complemento', $inscricao->ds_complemento) }}">
                                        @error('ds_complemento')
                                            <div id="validationServer03Feedback" class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row pt-2">
                                    <div class="form-group col-md-4 textoInput">
                                        <label for="no_bairro"><span style="color: red; font-weight: bold;">*</span>
                                            {{ __('Bairro') }}</label>
                                        <input id="no_bairro"
                                            class="form-control form-control-sm caixaDeTexto @error('no_bairro') is-invalid @enderror"
                                            type="text" placeholder="Insira o bairro do endereço" name="no_bairro"
                                            value="{{ old('no_bairro', $inscricao->no_bairro) }}">
                                        @error('no_bairro')
                                            <div id="validationServer03Feedback" class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-4 textoInput">
                                        <label for="no_municipio"><span
                                                style="color: red; font-weight: bold;">*</span>
                                            {{ __('Cidade') }}</label>
                                        <input id="no_municipio"
                                            class="form-control form-control-sm caixaDeTexto @error('no_municipio') is-invalid @enderror"
                                            type="text" placeholder="Insira o municipio do endereço"
                                            name="no_municipio"
                                            value="{{ old('no_municipio', $inscricao->no_municipio) }}">
                                        @error('no_municipio')
                                            <div id="validationServer03Feedback" class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-4 textoInput">
                                        <label for="sg_uf_inscrito"><span
                                                style="color: red; font-weight: bold;">*</span>
                                            {{ __('UF') }}</label>
                                        <select id="sg_uf_inscrito"
                                            class="form-control form-control-sm caixaDeTexto @error('sg_uf_inscrito') is-invalid @enderror"
                                            name="sg_uf_inscrito">
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
                                            <option value="SC"
                                                @if (old('sg_uf_inscrito', $inscricao->sg_uf_inscrito) == 'SC') selected @endif>Santa Catarina
                                            </option>
                                            <option value="SP"
                                                @if (old('sg_uf_inscrito', $inscricao->sg_uf_inscrito) == 'SP') selected @endif>São Paulo</option>
                                            <option value="SE"
                                                @if (old('sg_uf_inscrito', $inscricao->sg_uf_inscrito) == 'SE') selected @endif>Sergipe</option>
                                            <option value="TO"
                                                @if (old('sg_uf_inscrito', $inscricao->sg_uf_inscrito) == 'TO') selected @endif>Tocantins</option>
                                        </select>
                                        @error('uf')
                                            <div id="validationServer03Feedback" class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row pt-2">
                                    <div class="form-group col-md-6 textoInput">
                                        <label for="reside"><span style="color: red; font-weight: bold;">*</span>
                                            {{ __('Qual a Cidade/Estado onde você reside atualmente?') }}</label>
                                        <input id="reside"
                                            class="form-control form-control-sm caixaDeTexto @error('reside') is-invalid @enderror"
                                            type="text" placeholder="Cidade/UF" name="reside"
                                            value="{{ old('reside', $candidato->reside) }}">
                                        @error('reside')
                                            <div id="validationServer03Feedback" class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-6 textoInput">
                                        <label for="localidade"><span style="color: red; font-weight: bold;">*</span>
                                            {{ __('Qual a zona de sua moradia atual?') }}</label>
                                        <select id="localidade"
                                            class="form-control form-control-sm caixaDeTexto @error('localidade') is-invalid @enderror"
                                            name="localidade">
                                            <option value="" selected disabled>-- Selecione --</option>
                                            <option value="zona_rural"
                                                @if (old('localidade', $candidato->localidade) == 'zona_rural') selected @endif>Zona Rural</option>
                                            <option value="zona_urbana"
                                                @if (old('localidade', $candidato->localidade) == 'zona_urbana') selected @endif>Zona Urbana
                                            </option>
                                        </select>
                                        @error('localidade')
                                            <div id="validationServer03Feedback" class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group mt-2 textoInput">
                                    <label for="escola_ens_med"><span style="color: red; font-weight: bold;">*</span>
                                        {{ __('Estabelecimento que concluiu o Ensino Médio:') }}</label>
                                    <input id="escola_ens_med"
                                        class="form-control form-control-sm caixaDeTexto @error('escola_ens_med') is-invalid @enderror"
                                        type="text" placeholder="Insira o nome da escola" name="escola_ens_med"
                                        value="{{ old('escola_ens_med', $candidato->escola_ens_med) }}">
                                    @error('escola_ens_med')
                                        <div id="validationServer03Feedback" class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="row pt-2">
                                    <div class="form-group col-md-4 textoInput">
                                        <label for="uf_escola"><span style="color: red; font-weight: bold;">*</span>
                                            {{ __('UF') }}</label>
                                        <select id="uf_escola"
                                            class="form-control form-control-sm caixaDeTexto @error('uf_escola') is-invalid @enderror"
                                            name="uf_escola">
                                            <option value="" selected disabled>-- Selecione a UF --</option>
                                            <option value="AC"
                                                @if (old('uf_escola', $candidato->uf_escola) == 'AC') selected @endif>Acre</option>
                                            <option value="AL"
                                                @if (old('uf_escola', $candidato->uf_escola) == 'AL') selected @endif>Alagoas</option>
                                            <option value="AP"
                                                @if (old('uf_escola', $candidato->uf_escola) == 'AP') selected @endif>Amapá</option>
                                            <option value="AM"
                                                @if (old('uf_escola', $candidato->uf_escola) == 'AM') selected @endif>Amazonas</option>
                                            <option value="BA"
                                                @if (old('uf_escola', $candidato->uf_escola) == 'BA') selected @endif>Bahia</option>
                                            <option value="CE"
                                                @if (old('uf_escola', $candidato->uf_escola) == 'CE') selected @endif>Ceará</option>
                                            <option value="DF"
                                                @if (old('uf_escola', $candidato->uf_escola) == 'DF') selected @endif>Distrito Federal
                                            </option>
                                            <option value="ES"
                                                @if (old('uf_escola', $candidato->uf_escola) == 'ES') selected @endif>Espírito Santo
                                            </option>
                                            <option value="GO"
                                                @if (old('uf_escola', $candidato->uf_escola) == 'GO') selected @endif>Goiás</option>
                                            <option value="MA"
                                                @if (old('uf_escola', $candidato->uf_escola) == 'MA') selected @endif>Maranhão</option>
                                            <option value="MT"
                                                @if (old('uf_escola', $candidato->uf_escola) == 'MT') selected @endif>Mato Grosso
                                            </option>
                                            <option value="MS"
                                                @if (old('uf_escola', $candidato->uf_escola) == 'MS') selected @endif>Mato Grosso do Sul
                                            </option>
                                            <option value="MG"
                                                @if (old('uf_escola', $candidato->uf_escola) == 'MG') selected @endif>Minas Gerais
                                            </option>
                                            <option value="PA"
                                                @if (old('uf_escola', $candidato->uf_escola) == 'PA') selected @endif>Pará</option>
                                            <option value="PB"
                                                @if (old('uf_escola', $candidato->uf_escola) == 'PB') selected @endif>Paraíba</option>
                                            <option value="PR"
                                                @if (old('uf_escola', $candidato->uf_escola) == 'PR') selected @endif>Paraná</option>
                                            <option value="PE"
                                                @if (old('uf_escola', $candidato->uf_escola) == 'PE') selected @endif>Pernambuco
                                            </option>
                                            <option value="PI"
                                                @if (old('uf_escola', $candidato->uf_escola) == 'PI') selected @endif>Piauí</option>
                                            <option value="RJ"
                                                @if (old('uf_escola', $candidato->uf_escola) == 'RJ') selected @endif>Rio de Janeiro
                                            </option>
                                            <option value="RN"
                                                @if (old('uf_escola', $candidato->uf_escola) == 'RN') selected @endif>Rio Grande do
                                                Norte</option>
                                            <option value="RS"
                                                @if (old('uf_escola', $candidato->uf_escola) == 'RS') selected @endif>Rio Grande do Sul
                                            </option>
                                            <option value="RO"
                                                @if (old('uf_escola', $candidato->uf_escola) == 'RO') selected @endif>Rondônia</option>
                                            <option value="RR"
                                                @if (old('uf_escola', $candidato->uf_escola) == 'RR') selected @endif>Roraima</option>
                                            <option value="SC"
                                                @if (old('uf_escola', $candidato->uf_escola) == 'SC') selected @endif>Santa Catarina
                                            </option>
                                            <option value="SP"
                                                @if (old('uf_escola', $candidato->uf_escola) == 'SP') selected @endif>São Paulo</option>
                                            <option value="SE"
                                                @if (old('uf_escola', $candidato->uf_escola) == 'SE') selected @endif>Sergipe</option>
                                            <option value="TO"
                                                @if (old('uf_escola', $candidato->uf_escola) == 'TO') selected @endif>Tocantins</option>
                                        </select>
                                        @error('uf_escola')
                                            <div id="validationServer03Feedback" class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-4 textoInput">
                                        <label for="ano_conclusao"><span
                                                style="color: red; font-weight: bold;">*</span>
                                            {{ __('Ano de Conclusão') }}</label>
                                        <input id="ano_conclusao"
                                            class="form-control form-control-sm caixaDeTexto @error('ano_conclusao') is-invalid @enderror"
                                            type="text" placeholder="Insira o ano de conclusão do ensino médio"
                                            name="ano_conclusao"
                                            value="{{ old('ano_conclusao', $candidato->ano_conclusao) }}">
                                        @error('ano_conclusao')
                                            <div id="validationServer03Feedback" class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-4 textoInput">
                                        <label for="modalidade"><span style="color: red; font-weight: bold;">*</span>
                                            {{ __('Modalidade') }}</label>
                                        <select id="modalidade"
                                            class="form-control form-control-sm caixaDeTexto @error('modalidade') is-invalid @enderror"
                                            name="modalidade">
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
                                            <div id="validationServer03Feedback" class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row pt-2">
                                    <div class="form-group col-md-4 textoInput">
                                        <label for="concluiu_publica"><span
                                                style="color: red; font-weight: bold;">*</span>
                                            {{ __('Concluiu o Ensino Médio na rede pública?') }}</label>
                                        <select id="concluiu_publica"
                                            class="form-control form-control-sm caixaDeTexto @error('concluiu_publica') is-invalid @enderror"
                                            name="concluiu_publica">
                                            <option value="" selected disabled>-- Selecione --</option>
                                            <option value="1"
                                                @if (old('concluiu_publica', $candidato->concluiu_publica) == '1') selected @endif>Sim</option>
                                            <option value="0"
                                                @if (old('concluiu_publica', $candidato->concluiu_publica) == '0') selected @endif>Não</option>
                                        </select>
                                        @error('concluiu_publica')
                                            <div id="validationServer03Feedback" class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-4 textoInput">
                                        <label for="concluiu_publica"><span
                                                style="color: red; font-weight: bold;">*</span>
                                            {{ __('Concluiu o Ensino Médio em escolas comunitárias que atuam no âmbito da educação do campo conveniadas com o poder público') }}</label>
                                        <select id="concluiu_comunitaria"
                                            class="form-control form-control-sm caixaDeTexto @error('concluiu_comunitaria') is-invalid @enderror"
                                            name="concluiu_comunitaria">
                                            <option value="" selected disabled>-- Selecione --</option>
                                            <option value="1"
                                                @if (old('concluiu_comunitaria', $candidato->concluiu_comunitaria) == '1') selected @endif>Sim</option>
                                            <option value="0"
                                                @if (old('concluiu_comunitaria', $candidato->concluiu_comunitaria) == '0') selected @endif>Não</option>
                                        </select>
                                        @error('concluiu_comunitaria')
                                            <div id="validationServer03Feedback" class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="col-md-8">
                                        <label for="necessidades"><span
                                                style="color: red; font-weight: bold;">*</span>
                                            {{ __('Deficiências/transtornos') }}</label>
                                        <select id="necessidades"
                                            class="form-control form-control-sm caixaDeTexto selectpicker @error('necessidades') is-invalid @enderror"
                                            name="necessidades[]" title="-- Selecione --" required multiple>
                                            @foreach (\App\Models\Candidato::NECESSIDADES as $valor => $necessidade)
                                                <option value="{{ $valor }}"
                                                    @if (in_array($valor, old('necessidades', explode(',', $candidato->necessidades)))) selected @endif>
                                                    {{ $necessidade }}</option>
                                            @endforeach
                                        </select>
                                        @error('necessidades')
                                            <div id="validationServer03Feedback" class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row pt-2">
                                    <div class="form-group col-md-4 textoInput">
                                        <label for="nu_fone1"><span style="color: red; font-weight: bold;">*</span>
                                            {{ __('Celular') }}</label>
                                        <input id="nu_fone1"
                                            class="form-control form-control-sm caixaDeTexto @error('nu_fone1') is-invalid @enderror celular"
                                            type="text" name="nu_fone1"
                                            value="{{ old('nu_fone1', $inscricao->nu_fone1) }}">
                                        @error('nu_fone1')
                                            <div id="validationServer03Feedback" class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-4 textoInput">
                                        <label for="nu_fone2">{{ __('Celular') }}</label>
                                        <input id="nu_fone2"
                                            class="form-control form-control-sm caixaDeTexto @error('nu_fone2') is-invalid @enderror celular"
                                            type="text" name="nu_fone2"
                                            value="{{ old('nu_fone2', $inscricao->nu_fone2) }}">
                                        @error('nu_fone2')
                                            <div id="validationServer03Feedback" class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-4 textoInput">
                                        <label for="nu_fone_emergencia"><span style="color: red; font-weight: bold;">*</span>
                                        {{ __('Contato de Emergência') }}</label>
                                        <input id="nu_fone_emergencia"
                                            class="form-control form-control-sm caixaDeTexto @error('nu_fone_emergencia') is-invalid @enderror celular"
                                            type="text" name="nu_fone_emergencia"
                                            value="{{ old('nu_fone_emergencia', $inscricao->nu_fone_emergencia) }}">
                                        @error('nu_fone_emergencia')
                                            <div id="validationServer03Feedback" class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row pt-2">
                                    <div class="form-group col-md-4 textoInput">
                                        <label for="cor_raca"><span style="color: red; font-weight: bold;">*</span>
                                            {{ __('Cor/Raça') }}</label>
                                        <select id="cor_raca"
                                            class="form-control form-control-sm caixaDeTexto @error('cor_raca') is-invalid @enderror"
                                            name="etnia_e_cor">
                                            <option value="" selected disabled>-- Selecione --</option>
                                            <option value="1"
                                                @if (old('cor_raca', $candidato->etnia_e_cor) == 1) selected @endif>
                                                {{ $cores_racas[0] }}</option>
                                            <option value="4"
                                                @if (old('cor_raca', $candidato->etnia_e_cor) == 4) selected @endif>
                                                {{ $cores_racas[3] }}</option>
                                            <option value="2"
                                                @if (old('cor_raca', $candidato->etnia_e_cor) == 2) selected @endif>
                                                {{ $cores_racas[1] }}</option>
                                            <option value="3"
                                                @if (old('cor_raca', $candidato->etnia_e_cor) == 3) selected @endif>
                                                {{ $cores_racas[2] }}</option>
                                            <option value="5"
                                                @if (old('cor_raca', $candidato->etnia_e_cor) == 5) selected @endif>
                                                {{ $cores_racas[4] }}</option>
                                        </select>
                                        @error('cor_raca')
                                            <div id="validationServer03Feedback" class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="form-group col-md-4 textoInput">
                                        <label for="quilombola"><span style="color: red; font-weight: bold;">*</span>
                                            {{ __('Quilombola') }}</label>
                                        <select id="quilombola"
                                            class="form-control form-control-sm caixaDeTexto @error('quilombola') is-invalid @enderror"
                                            name="quilombola">
                                            <option value="" selected disabled>-- Selecione --</option>
                                            <option value="1" @if ($candidato->quilombola == true) selected @endif>Sim</option>
                                            <option value="0" @if ($candidato->quilombola == false) selected @endif>Não</option>
                                        </select>
                                        @error('quilombola')
                                            <div id="validationServer03Feedback" class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="form-group col-md-4 textoInput">
                                        <label for="indigena"><span style="color: red; font-weight: bold;">*</span>
                                            {{ __('Indígena') }}</label>
                                        <select id="indigena"
                                            class="form-control form-control-sm caixaDeTexto @error('indigena') is-invalid @enderror"
                                            name="indigena">
                                            <option value="" selected disabled>-- Selecione --</option>
                                            <option value="1" @if ($candidato->indigena == true) selected @endif>Sim</option>
                                            <option value="0" @if ($candidato->indigena == false) selected @endif>Não</option>
                                        </select>
                                        @error('indigena')
                                            <div id="validationServer03Feedback" class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row pt-2">
                                    <div class="form-group col-md-6 textoInput">
                                        <label for="trabalha"><span style="color: red; font-weight: bold;">*</span>
                                            {{ __('Você exerce alguma atividade remunerada?') }}</label>
                                        <select id="trabalha"
                                            class="form-control form-control-sm caixaDeTexto @error('trabalha') is-invalid @enderror"
                                            name="trabalha">
                                            <option value="" selected disabled>-- Selecione --</option>
                                            <option value="1"
                                                @if (old('trabalha', $candidato->trabalha) == '1') selected @endif>Sim</option>
                                            <option value="0"
                                                @if (old('trabalha', $candidato->trabalha) == '0') selected @endif>Não</option>
                                        </select>
                                        @error('trabalha')
                                            <div id="validationServer03Feedback" class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-6 textoInput">
                                        <label for="grupo_familiar"><span
                                                style="color: red; font-weight: bold;">*</span>
                                            {{ __('Quantas pessoas fazem parte do seu grupo familiar?') }}</label>
                                        <input id="grupo_familiar"
                                            class="form-control form-control-sm caixaDeTexto @error('grupo_familiar') is-invalid @enderror"
                                            type="number" name="grupo_familiar"
                                            value="{{ old('grupo_familiar', $candidato->grupo_familiar) }}">
                                        @error('grupo_familiar')
                                            <div id="validationServer03Feedback" class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group mt-2 textoInput">
                                    <label for="valor_renda"><span style="color: red; font-weight: bold;">*</span>
                                        {{ __('Qual o valor da renda total (renda bruta) do seu grupo familiar? (Soma dos rendimentos de todo o grupo familiar, incluindo você) (Ex.: 1250,00)') }}</label>
                                    <input id="valor_renda"
                                        class="form-control form-control-sm caixaDeTexto @error('valor_renda') is-invalid @enderror"
                                        type="number" step="0.1" placeholder="1250,00" name="valor_renda"
                                        value="{{ old('valor_renda', $candidato->valor_renda) }}">
                                    @error('valor_renda')
                                        <div id="validationServer03Feedback" class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="form-check mt-2 @error('edital') is-invalid @enderror">
                                    <input class="form-check-input" type="checkbox" value="true" id="checkEdital"
                                        name="edital" @if (old('edital')) checked @endif>
                                    <label class="form-check-label subtexto3" for="checkEdital">
                                        DECLARO que li o Edital e estou de acordo com os termos e condições.
                                    </label>
                                </div>
                                <div class="invalid-feedback">
                                    @error('edital')
                                        {{ $message }}
                                    @enderror
                                </div>

                                <div class="form-check mt-2 @error('vinculo') is-invalid @enderror">
                                    <input class="form-check-input" type="checkbox" value="true" id="checkVinculo"
                                        name="vinculo" @if (old('vinculo')) checked @endif>
                                    <label class="form-check-label subtexto3" for="checkVinculo">
                                        Estou ciente que não posso ter vínculo com mais de uma Instituição de Ensino
                                        Superior pública.
                                    </label>
                                </div>
                                <div class="invalid-feedback">
                                    @error('vinculo')
                                        {{ $message }}
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
        });
    </script>
</x-app-layout>
