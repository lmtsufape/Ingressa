<div>
    <div clss="mb-5">
        <form id="enviar-documentos"
            wire:submit.prevent="submit"
            enctype="multipart/form-data">
            <ul class="timeline">
                <li class="px-1 align-middle">
                    <div class="col-md-12">
                        <div class="tituloEnvio"> Documentação básica </div>
                        <div class="subtexto2 my-1">A documentação básica corresponde a documentação que o(a) candidato(a) deve enviar.</div>
                    </div>
                    @if ($documentos->contains('declaracao_veracidade'))
                        <div class="mt-2">
                            <label for="docDeclaracaoVeracidade"
                                title="Enviar documento"
                                style="cursor: pointer;">
                                <input wire:model="arquivos.declaracao_veracidade"
                                    id="docDeclaracaoVeracidade"
                                    type="file"
                                    class="d-none">
                                @can('dataEnvio', $inscricao->chamada)
                                    @if ($inscricao->isDocumentosRequeridos() || ($inscricao->isArquivoRecusadoOuReenviado('declaracao_veracidade') && $inscricao->isDocumentosInvalidados()))
                                        <img src="{{ asset('img/upload2.svg') }}"
                                            width="30">
                                    @endif
                                @endcan
                            </label>
                            @if ($inscricao->arquivo('declaracao_veracidade'))
                                <a wire:click="baixar('declaracao_veracidade')"
                                    title="Baixar documento"
                                    target="_blank"
                                    style="cursor:pointer;">
                                    <img src="{{asset('img/download2.svg')}}"
                                        alt="arquivo atual"
                                        width="30"
                                        class="img-flex"></a>
                                @can('periodoEnvio', $inscricao->chamada)
                                    @if ($inscricao->isDocumentosRequeridos() || ($inscricao->isArquivoRecusadoOuReenviado('declaracao_veracidade') && $inscricao->isDocumentosInvalidados()))
                                        <button type="button" title="Deletar documento enviado" data-bs-toggle="modal" data-bs-target="#deletar-arquivo-declaracao_veracidade" style="cursor: pointer;"><img width="30" src="{{asset('img/Grupo 1664.svg')}}" alt="Icone de Deletar documento enviado"></button>
                                    @endif
                                @endcan
                            @else
                                <img src="{{ asset('img/download3.svg') }}"
                                    width="30">
                            @endif
                            <span class="subtexto3 @error('arquivos.declaracao_veracidade') is-invalid text-danger @enderror">
                                Declaração de Veracidade;
                            </span>
                            <div class="invalid-feedback">@error('arquivos.declaracao_veracidade'){{$message}}@enderror</div>
                        </div>
                        @if($inscricao->isArquivoRecusado('declaracao_veracidade'))
                            <div class="mt-2">
                                <div class="alert alert-danger " role="alert">
                                    <h6 style="font-family: Verdana, Geneva, Tahoma, sans-serif; font-size: 15px;" class="alert-heading">Documento recusado!</h6>
                                    <span class="p-zero" style="font-family: Verdana, Geneva, Tahoma, sans-serif; font-size: 13px;" style="color: rgb(197, 0, 0)"><strong>Motivo: </strong>{!!$inscricao->arquivo('declaracao_veracidade')->avaliacao->comentario!!}</span>
                                </div>
                            </div>
                        @elseif($inscricao->isArquivoAceito('declaracao_veracidade'))
                            <div class="mt-2">
                                <div class="alert alert-success " role="alert">
                                    <h6 style="font-family: Verdana, Geneva, Tahoma, sans-serif; font-size: 14px;" class="mb-0 alert-heading">Documento validado!</h6>
                                </div>
                            </div>
                        @elseif($inscricao->isArquivoReenviado('declaracao_veracidade'))
                        <div class="mt-2">
                            <div class="alert alert-primary " role="alert">
                                <h6 style="font-family: Verdana, Geneva, Tahoma, sans-serif; font-size: 14px;" class="mb-0 alert-heading">Documento reenviado!</h6>
                            </div>
                        </div>
                        @endif
                    @endif
                    @if ($documentos->contains('certificado_conclusao'))
                        <div class="mt-2">
                            <label for="docConclusaoMedio"
                                title="Enviar documento"
                                style="cursor:pointer;">
                                <input wire:model="arquivos.certificado_conclusao"
                                    type="file"
                                    class="d-none"
                                    id="docConclusaoMedio">
                                    @can('dataEnvio', $inscricao->chamada)
                                        @if ($inscricao->isDocumentosRequeridos() || ($inscricao->isArquivoRecusadoOuReenviado('certificado_conclusao') && $inscricao->isDocumentosInvalidados()))
                                            <img src="{{ asset('img/upload2.svg') }}"
                                            width="30">
                                        @endif
                                    @endcan
                            </label>
                            @if ($inscricao->arquivo('certificado_conclusao'))
                                <a wire:click="baixar('certificado_conclusao')"
                                    title="Baixar documento"
                                    target="_blank"
                                    style="cursor:pointer;">
                                    <img src="{{asset('img/download2.svg')}}"
                                        alt="arquivo atual"
                                        width="30"
                                        class="img-flex"></a>

                                @can('periodoEnvio', $inscricao->chamada)
                                    @if ($inscricao->isDocumentosRequeridos() || ($inscricao->isArquivoRecusadoOuReenviado('certificado_conclusao') && $inscricao->isDocumentosInvalidados()))
                                        <button type="button" title="Deletar documento enviado" data-bs-toggle="modal" data-bs-target="#deletar-arquivo-certificado_conclusao" style="cursor: pointer;"><img width="30" src="{{asset('img/Grupo 1664.svg')}}" alt="Icone de Deletar documento enviado"></button>
                                    @endif
                                @endcan
                            @else
                                <img src="{{ asset('img/download3.svg') }}"
                                    width="30">
                            @endif
                            <span class="subtexto3 @error('arquivos.certificado_conclusao') is-invalid text-danger @enderror">
                                Certificado de Conclusão do Ensino Médio ou Certidão de Exame Supletivo do Ensino Médio ou
                                Certificação de Ensino Médio através do ENEM ou documento equivalente [pode estar junto com
                                o Histórico Escolar (escanear frente e verso da Ficha 19), neste caso anexar o arquivo nos
                                dois campos];
                            </span>
                            <div class="invalid-feedback">@error('arquivos.certificado_conclusao'){{$message}}@enderror</div>
                        </div>
                        @if($inscricao->isArquivoRecusado('certificado_conclusao'))
                            <div class="mt-2">
                                <div class="alert alert-danger " role="alert">
                                    <h6 style="font-family: Verdana, Geneva, Tahoma, sans-serif; font-size: 15px;" class="alert-heading">Documento recusado!</h6>
                                    <span class="p-zero" style="font-family: Verdana, Geneva, Tahoma, sans-serif; font-size: 13px;" style="color: rgb(197, 0, 0)"><strong>Motivo: </strong>{!!$inscricao->arquivo('certificado_conclusao')->avaliacao->comentario!!}</span>
                                </div>
                            </div>
                        @elseif($inscricao->isArquivoAceito('certificado_conclusao'))
                            <div class="mt-2">
                                <div class="alert alert-success " role="alert">
                                    <h6 style="font-family: Verdana, Geneva, Tahoma, sans-serif; font-size: 14px;" class="mb-0 alert-heading">Documento validado!</h6>
                                </div>
                            </div>
                        @elseif($inscricao->isArquivoReenviado('certificado_conclusao'))
                        <div class="mt-2">
                            <div class="alert alert-primary " role="alert">
                                <h6 style="font-family: Verdana, Geneva, Tahoma, sans-serif; font-size: 14px;" class="mb-0 alert-heading">Documento reenviado!</h6>
                            </div>
                        </div>
                        @endif
                    @endif
                    @if($documentos->contains('historico'))
                        <div class="mt-2">
                            <label for="docHistorico"
                                title="Enviar documento"
                                style="cursor:pointer;">
                                <input wire:model="arquivos.historico"
                                    type="file"
                                    class="d-none"
                                    id="docHistorico">
                                @can('dataEnvio', $inscricao->chamada)
                                    @if ($inscricao->isDocumentosRequeridos() || ($inscricao->isArquivoRecusadoOuReenviado('historico') && $inscricao->isDocumentosInvalidados()))
                                        @if (in_array($declaracoes['historico'], [null, '']))
                                            <img src="{{ asset('img/upload2.svg') }}" width="30">
                                        @endif
                                    @endif
                                @endcan
                            </label>
                            @if ($inscricao->arquivo('historico'))
                                <a wire:click="baixar('historico')"
                                    title="Baixar documento"
                                    target="_blank"
                                    style="cursor:pointer;">
                                    <img src="{{asset('img/download2.svg')}}"
                                        alt="arquivo atual"
                                        width="30"
                                        class="img-flex"></a>

                                @can('periodoEnvio', $inscricao->chamada)
                                    @if ($inscricao->isDocumentosRequeridos() || ($inscricao->isArquivoRecusadoOuReenviado('historico') && $inscricao->isDocumentosInvalidados()))
                                        <button type="button" title="Deletar documento enviado" data-bs-toggle="modal" data-bs-target="#deletar-arquivo-historico" style="cursor: pointer;"><img width="30" src="{{asset('img/Grupo 1664.svg')}}" alt="Icone de Deletar documento enviado"></button>
                                    @endif
                                @endcan
                            @else
                                <img src="{{ asset('img/download3.svg') }}"
                                    width="30">
                            @endif
                            <span class="subtexto3 @error('arquivos.historico') is-invalid text-danger @enderror">
                                Histórico Escolar do Ensino Médio ou Equivalente [pode estar junto com
                                o Certificado de Conclusão do Ensino Médio (escanear frente e verso da Ficha 19), neste caso anexar
                                o arquivo nos dois campos];
                            </span>
                            <div class="invalid-feedback">@error('arquivos.historico'){{$message}}@enderror</div>
                            @can('dataEnvio', $inscricao->chamada)
                                @if(!$inscricao->arquivo('historico'))
                                    @if ($inscricao->isDocumentosRequeridos() || ($inscricao->isArquivoRecusadoOuReenviado('historico') && $inscricao->isDocumentosInvalidados()))
                                        <div class="form-check mt-2">
                                            <input class="form-check-input" type="checkbox" name="declaracoes.historico" value="true" id="checkHistorico" wire:model="declaracoes.historico">
                                            <label class="form-check-label subtexto3" for="checkHistorico">
                                                Comprometo-me a entregar junto ao DRCA/UFAPE o Histórico Escolar do Ensino Médio ou Equivalente, na
                                                primeira semana de aula.
                                            </label>
                                        </div>
                                    @endif
                                @endif
                            @endcan
                        </div>
                        @if($inscricao->isArquivoRecusado('historico'))
                            <div class="mt-2">
                                <div class="alert alert-danger " role="alert">
                                    <h6 style="font-family: Verdana, Geneva, Tahoma, sans-serif; font-size: 15px;" class="alert-heading">Documento recusado!</h6>
                                    <span class="p-zero" style="font-family: Verdana, Geneva, Tahoma, sans-serif; font-size: 13px;" style="color: rgb(197, 0, 0)"><strong>Motivo: </strong>{!!$inscricao->arquivo('historico')->avaliacao->comentario!!}</span>
                                </div>
                            </div>
                        @elseif($inscricao->isArquivoAceito('historico'))
                            <div class="mt-2">
                                <div class="alert alert-success " role="alert">
                                    <h6 style="font-family: Verdana, Geneva, Tahoma, sans-serif; font-size: 14px;" class="mb-0 alert-heading">Documento validado!</h6>
                                </div>
                            </div>
                        @elseif($inscricao->isArquivoReenviado('historico'))
                        <div class="mt-2">
                            <div class="alert alert-primary " role="alert">
                                <h6 style="font-family: Verdana, Geneva, Tahoma, sans-serif; font-size: 14px;" class="mb-0 alert-heading">Documento reenviado!</h6>
                            </div>
                        </div>
                        @endif
                    @endif
                    @if($documentos->contains('nascimento_ou_casamento'))
                        <div class="mt-2">
                            <label for="docNascimento"
                                title="Enviar documento"
                                style="cursor:pointer;">
                                <input wire:model="arquivos.nascimento_ou_casamento"
                                    type="file"
                                    class="d-none"
                                    id="docNascimento">
                                @can('dataEnvio', $inscricao->chamada)
                                    @if ($inscricao->isDocumentosRequeridos() || ($inscricao->isArquivoRecusadoOuReenviado('nascimento_ou_casamento') && $inscricao->isDocumentosInvalidados()))
                                        @if (in_array($declaracoes['nascimento_ou_casamento'], [null, '']))
                                            <img src="{{ asset('img/upload2.svg') }}" width="30">
                                        @endif
                                    @endif
                                @endcan
                            </label>
                            @if ($inscricao->arquivo('nascimento_ou_casamento'))
                                <a wire:click="baixar('nascimento_ou_casamento')"
                                    title="Baixar documento"
                                    target="_blank"
                                    style="cursor:pointer;">
                                    <img src="{{asset('img/download2.svg')}}"
                                        alt="arquivo atual"
                                        width="30"
                                        class="img-flex"></a>

                                @can('periodoEnvio', $inscricao->chamada)
                                    @if ($inscricao->isDocumentosRequeridos() || ($inscricao->isArquivoRecusadoOuReenviado('nascimento_ou_casamento') && $inscricao->isDocumentosInvalidados()))
                                        <button type="button" title="Deletar documento enviado" data-bs-toggle="modal" data-bs-target="#deletar-arquivo-nascimento_ou_casamento" style="cursor: pointer;"><img width="30" src="{{asset('img/Grupo 1664.svg')}}" alt="Icone de Deletar documento enviado"></button>
                                    @endif
                                @endcan
                            @else
                                <img src="{{ asset('img/download3.svg') }}"
                                    width="30">
                            @endif
                            <span class="subtexto3 @error('arquivos.nascimento_ou_casamento') is-invalid text-danger @enderror">
                                Registro de Nascimento ou Certidão de Casamento;
                            </span>
                            <div class="invalid-feedback">@error('arquivos.nascimento_ou_casamento'){{$message}}@enderror</div>
                            @can('dataEnvio', $inscricao->chamada)
                                @if(!$inscricao->arquivo('nascimento_ou_casamento'))
                                    @if ($inscricao->isDocumentosRequeridos() || ($inscricao->isArquivoRecusadoOuReenviado('nascimento_ou_casamento') && $inscricao->isDocumentosInvalidados()))
                                        <div class="form-check mt-2">
                                            <input class="form-check-input" type="checkbox" value="true" id="checkNascimento_casamento" wire:model="declaracoes.nascimento_ou_casamento">
                                            <label class="form-check-label subtexto3" for="checkNascimento_casamento">
                                                Comprometo-me a entregar junto ao DRCA/UFAPE o Registro de Nascimento ou Certidão de Casamento, na
                                                primeira semana de aula.
                                            </label>
                                        </div>
                                    @endif
                                @endif
                            @endcan
                        </div>
                        @if($inscricao->isArquivoRecusado('nascimento_ou_casamento'))
                            <div class="mt-2">
                                <div class="alert alert-danger " role="alert">
                                    <h6 style="font-family: Verdana, Geneva, Tahoma, sans-serif; font-size: 15px;" class="alert-heading">Documento recusado!</h6>
                                    <span class="p-zero" style="font-family: Verdana, Geneva, Tahoma, sans-serif; font-size: 13px;" style="color: rgb(197, 0, 0)"><strong>Motivo: </strong>{!!$inscricao->arquivo('nascimento_ou_casamento')->avaliacao->comentario!!}</span>
                                </div>
                            </div>
                        @elseif($inscricao->isArquivoAceito('nascimento_ou_casamento'))
                            <div class="mt-2">
                                <div class="alert alert-success " role="alert">
                                    <h6 style="font-family: Verdana, Geneva, Tahoma, sans-serif; font-size: 14px;" class="mb-0 alert-heading">Documento validado!</h6>
                                </div>
                            </div>
                        @elseif($inscricao->isArquivoReenviado('nascimento_ou_casamento'))
                        <div class="mt-2">
                            <div class="alert alert-primary " role="alert">
                                <h6 style="font-family: Verdana, Geneva, Tahoma, sans-serif; font-size: 14px;" class="mb-0 alert-heading">Documento reenviado!</h6>
                            </div>
                        </div>
                        @endif
                    @endif
                    @if($documentos->contains('rg'))
                        <div class="mt-2">
                            <label for="docRG"
                                title="Enviar documento"
                                style="cursor:pointer;">
                                <input wire:model="arquivos.rg"
                                    type="file"
                                    class="d-none"
                                    id="docRG">
                                @can('dataEnvio', $inscricao->chamada)
                                    @if ($inscricao->isDocumentosRequeridos() || ($inscricao->isArquivoRecusadoOuReenviado('rg') && $inscricao->isDocumentosInvalidados()))
                                        <img src="{{ asset('img/upload2.svg') }}"
                                        width="30">
                                    @endif
                                @endcan
                            </label>
                            @if ($inscricao->arquivo('rg'))
                                <a wire:click="baixar('rg')"
                                    title="Baixar documento"
                                    target="_blank"
                                    style="cursor:pointer;">
                                    <img src="{{asset('img/download2.svg')}}"
                                        alt="arquivo atual"
                                        width="30"
                                        class="img-flex"></a>

                                @can('periodoEnvio', $inscricao->chamada)
                                    @if ($inscricao->isDocumentosRequeridos() || ($inscricao->isArquivoRecusadoOuReenviado('rg') && $inscricao->isDocumentosInvalidados()))
                                        <button type="button" title="Deletar documento enviado" data-bs-toggle="modal" data-bs-target="#deletar-arquivo-rg" style="cursor: pointer;"><img width="30" src="{{asset('img/Grupo 1664.svg')}}" alt="Icone de Deletar documento enviado"></button>
                                    @endif
                                @endcan
                            @else
                                <img src="{{ asset('img/download3.svg') }}"
                                    width="30">
                            @endif
                            <span class="subtexto3 @error('arquivos.rg') is-invalid text-danger @enderror">
                                Carteira de Identidade válida e com foto recente (RG), frente e verso. Caso tenha perdido ou sido
                                roubado, anexar um Boletim de Ocorrência e algum outro documento com foto. A Carteira
                                Nacional de Habilitação pode ser utilizado como documento com foto, mas não será aceita em
                                substituição ao RG e ao CPF;
                            </span>
                            <div class="invalid-feedback">@error('arquivos.rg'){{$message}}@enderror</div>
                        </div>
                        @if($inscricao->isArquivoRecusado('rg'))
                            <div class="mt-2">
                                <div class="alert alert-danger " role="alert">
                                    <h6 style="font-family: Verdana, Geneva, Tahoma, sans-serif; font-size: 15px;" class="alert-heading">Documento recusado!</h6>
                                    <span class="p-zero" style="font-family: Verdana, Geneva, Tahoma, sans-serif; font-size: 13px;" style="color: rgb(197, 0, 0)"><strong>Motivo: </strong>{!!$inscricao->arquivo('rg')->avaliacao->comentario!!}</span>
                                </div>
                            </div>
                        @elseif($inscricao->isArquivoAceito('rg'))
                            <div class="mt-2">
                                <div class="alert alert-success " role="alert">
                                    <h6 style="font-family: Verdana, Geneva, Tahoma, sans-serif; font-size: 14px;" class="mb-0 alert-heading">Documento validado!</h6>
                                </div>
                            </div>
                        @elseif($inscricao->isArquivoReenviado('rg'))
                        <div class="mt-2">
                            <div class="alert alert-primary " role="alert">
                                <h6 style="font-family: Verdana, Geneva, Tahoma, sans-serif; font-size: 14px;" class="mb-0 alert-heading">Documento reenviado!</h6>
                            </div>
                        </div>
                        @endif
                    @endif
                    @if($documentos->contains('cpf'))
                        <div class="mt-2">
                            <label for="docCPF"
                                title="Enviar documento"
                                style="cursor:pointer;">
                                <input wire:model="arquivos.cpf"
                                    type="file"
                                    class="d-none"
                                    id="docCPF">
                                @can('dataEnvio', $inscricao->chamada)
                                    @if ($inscricao->isDocumentosRequeridos() || ($inscricao->isArquivoRecusadoOuReenviado('cpf') && $inscricao->isDocumentosInvalidados()))
                                        <img src="{{ asset('img/upload2.svg') }}"
                                        width="30">
                                    @endif
                                @endcan
                            </label>
                            @if ($inscricao->arquivo('cpf'))
                                <a wire:click="baixar('cpf')"
                                    title="Baixar documento"
                                    target="_blank"
                                    style="cursor:pointer;">
                                    <img src="{{asset('img/download2.svg')}}"
                                        alt="arquivo atual"
                                        width="30"
                                        class="img-flex"></a>

                                @can('periodoEnvio', $inscricao->chamada)
                                    @if ($inscricao->isDocumentosRequeridos() || ($inscricao->isArquivoRecusadoOuReenviado('cpf') && $inscricao->isDocumentosInvalidados()))
                                        <button type="button" title="Deletar documento enviado" data-bs-toggle="modal" data-bs-target="#deletar-arquivo-cpf" style="cursor: pointer;"><img width="30" src="{{asset('img/Grupo 1664.svg')}}" alt="Icone de Deletar documento enviado"></button>
                                    @endif
                                @endcan
                            @else
                                <img src="{{ asset('img/download3.svg') }}"
                                    width="30">
                            @endif
                            <span class="subtexto3 @error('arquivos.cpf') is-invalid text-danger @enderror">
                                Cadastro de Pessoa Física (CPF). Caso conste o número do CPF na identidade (RG),
                                anexar cópia da identidade, frente e verso. Caso tenha perdido ou sido
                                roubado, emitir Comprovante de Situação Cadastral no CPF, através do
                            </span>
                            <a href="https://servicos.receita.fazenda.gov.br/servicos/cpf/consultasituacao/consultapublica.asp" target="_blank" rel="noopener noreferrer">site da Receita Federal</a>;
                            <div class="invalid-feedback">@error('arquivos.cpf'){{$message}}@enderror</div>
                        </div>
                        @if($inscricao->isArquivoRecusado('cpf'))
                            <div class="mt-2">
                                <div class="alert alert-danger " role="alert">
                                    <h6 style="font-family: Verdana, Geneva, Tahoma, sans-serif; font-size: 15px;" class="alert-heading">Documento recusado!</h6>
                                    <span class="p-zero" style="font-family: Verdana, Geneva, Tahoma, sans-serif; font-size: 13px;" style="color: rgb(197, 0, 0)"><strong>Motivo: </strong>{!!$inscricao->arquivo('cpf')->avaliacao->comentario!!}</span>
                                </div>
                            </div>
                        @elseif($inscricao->isArquivoAceito('cpf'))
                            <div class="mt-2">
                                <div class="alert alert-success " role="alert">
                                    <h6 style="font-family: Verdana, Geneva, Tahoma, sans-serif; font-size: 14px;" class="mb-0 alert-heading">Documento validado!</h6>
                                </div>
                            </div>
                        @elseif($inscricao->isArquivoReenviado('cpf'))
                        <div class="mt-2">
                            <div class="alert alert-primary " role="alert">
                                <h6 style="font-family: Verdana, Geneva, Tahoma, sans-serif; font-size: 14px;" class="mb-0 alert-heading">Documento reenviado!</h6>
                            </div>
                        </div>
                        @endif
                    @endif
                    @if($documentos->contains('quitacao_eleitoral'))
                        <div class="mt-2">
                            <label for="docEleitoral"
                                title="Enviar documento"
                                style="cursor:pointer;">
                                <input wire:model="arquivos.quitacao_eleitoral"
                                    type="file"
                                    class="d-none"
                                    id="docEleitoral">
                                @can('dataEnvio', $inscricao->chamada)
                                    @if ($inscricao->isDocumentosRequeridos() || ($inscricao->isArquivoRecusadoOuReenviado('quitacao_eleitoral') && $inscricao->isDocumentosInvalidados()))
                                        @if (in_array($declaracoes['quitacao_eleitoral'], [null, '']))
                                            <img src="{{ asset('img/upload2.svg') }}" width="30">
                                        @endif
                                    @endif
                                @endcan
                            </label>
                            @if ($inscricao->arquivo('quitacao_eleitoral'))
                                <a wire:click="baixar('quitacao_eleitoral')"
                                    title="Baixar documento"
                                    target="_blank"
                                    style="cursor:pointer;">
                                    <img src="{{asset('img/download2.svg')}}"
                                        alt="arquivo atual"
                                        width="30"
                                        class="img-flex"></a>

                                @can('periodoEnvio', $inscricao->chamada)
                                    @if ($inscricao->isDocumentosRequeridos() || ($inscricao->isArquivoRecusadoOuReenviado('quitacao_eleitoral') && $inscricao->isDocumentosInvalidados()))
                                        <button type="button" title="Deletar documento enviado" data-bs-toggle="modal" data-bs-target="#deletar-arquivo-quitacao_eleitoral" style="cursor: pointer;"><img width="30" src="{{asset('img/Grupo 1664.svg')}}" alt="Icone de Deletar documento enviado"></button>
                                    @endif
                                @endcan
                            @else
                                <img src="{{ asset('img/download3.svg') }}"
                                    width="30">
                            @endif
                            <span class="subtexto3 @error('arquivos.quitacao_eleitoral') is-invalid text-danger @enderror">
                                Comprovante de quitação com o Serviço Eleitoral no último turno de votação ou Certidão de
                                quitação eleitoral. Essa certidão poderá ser emitida no
                                <a href="https://www.tse.jus.br/eleitor/certidoes/certidao-de-quitacao-eleitoral" target="_blank" rel="noopener noreferrer">
                                site do Tribunal Superior Eleitoral.</a> Caso a certidão de quitação eleitoral não possa ser emitida em função de
                                pagamento de multas eleitorais, poderá ser apresentada cópia (captura da
                                tela) do relatório de quitação de débitos do eleitor (quitação de multas,
                                disponível no
                                <a href="https://www.tse.jus.br/" target="_blank" rel="noopener noreferrer">site do Tribunal Superior Eleitoral</a>);
                            </span>
                            <div class="invalid-feedback">@error('arquivos.quitacao_eleitoral'){{$message}}@enderror</div>
                            @can('dataEnvio', $inscricao->chamada)
                                @if(!$inscricao->arquivo('quitacao_eleitoral'))
                                    @if ($inscricao->isDocumentosRequeridos() || ($inscricao->isArquivoRecusadoOuReenviado('quitacao_eleitoral') && $inscricao->isDocumentosInvalidados()))
                                        <div class="form-check mt-2">
                                            <input class="form-check-input" type="checkbox" name="declaracoes.quitacao_eleitoral " value="true" id="checkquitacao_eleitoral" wire:model="declaracoes.quitacao_eleitoral">
                                            <label class="form-check-label subtexto3" for="checkquitacao_eleitoral">
                                                Comprometo-me a entregar junto ao DRCA/UFAPE o Comprovante de quitação com o Serviço Eleitoral, na
                                                primeira semana de aula.
                                            </label>
                                        </div>
                                    @endif
                                @endif
                            @endcan
                        </div>
                        @if($inscricao->isArquivoRecusado('quitacao_eleitoral'))
                            <div class="mt-2">
                                <div class="alert alert-danger " role="alert">
                                    <h6 style="font-family: Verdana, Geneva, Tahoma, sans-serif; font-size: 15px;" class="alert-heading">Documento recusado!</h6>
                                    <span class="p-zero" style="font-family: Verdana, Geneva, Tahoma, sans-serif; font-size: 13px;" style="color: rgb(197, 0, 0)"><strong>Motivo: </strong>{!!$inscricao->arquivo('quitacao_eleitoral')->avaliacao->comentario!!}</span>
                                </div>
                            </div>
                        @elseif($inscricao->isArquivoAceito('quitacao_eleitoral'))
                            <div class="mt-2">
                                <div class="alert alert-success " role="alert">
                                    <h6 style="font-family: Verdana, Geneva, Tahoma, sans-serif; font-size: 14px;" class="mb-0 alert-heading">Documento validado!</h6>
                                </div>
                            </div>
                        @elseif($inscricao->isArquivoReenviado('quitacao_eleitoral'))
                        <div class="mt-2">
                            <div class="alert alert-primary " role="alert">
                                <h6 style="font-family: Verdana, Geneva, Tahoma, sans-serif; font-size: 14px;" class="mb-0 alert-heading">Documento reenviado!</h6>
                            </div>
                        </div>
                        @endif
                    @endif
                    @if($documentos->contains('quitacao_militar'))
                        <div class="mt-2">
                            <label for="docMilitar"
                                title="Enviar documento"
                                style="cursor:pointer;">
                                <input wire:model="arquivos.quitacao_militar"
                                    type="file"
                                    class="d-none"
                                    id="docMilitar">
                                @can('dataEnvio', $inscricao->chamada)
                                    @if ($inscricao->isDocumentosRequeridos() || ($inscricao->isArquivoRecusadoOuReenviado('quitacao_militar') && $inscricao->isDocumentosInvalidados()))
                                        @if (in_array($declaracoes['quitacao_militar'], [null, '']))
                                            <img src="{{ asset('img/upload2.svg') }}" width="30">
                                        @endif
                                    @endif
                                @endcan
                            </label>
                            @if ($inscricao->arquivo('quitacao_militar'))
                                <a wire:click="baixar('quitacao_militar')"
                                    title="Baixar documento"
                                    target="_blank"
                                    style="cursor:pointer;">
                                    <img src="{{asset('img/download2.svg')}}"
                                        alt="arquivo atual"
                                        width="30"
                                        class="img-flex"></a>

                                @can('periodoEnvio', $inscricao->chamada)
                                    @if ($inscricao->isDocumentosRequeridos() || ($inscricao->isArquivoRecusadoOuReenviado('quitacao_militar') && $inscricao->isDocumentosInvalidados()))
                                        <button type="button" title="Deletar documento enviado" data-bs-toggle="modal" data-bs-target="#deletar-arquivo-quitacao_militar" style="cursor: pointer;"><img width="30" src="{{asset('img/Grupo 1664.svg')}}" alt="Icone de Deletar documento enviado"></button>
                                    @endif
                                @endcan
                            @else
                                <img src="{{ asset('img/download3.svg') }}"
                                    width="30">
                            @endif
                            <span class="subtexto3 @error('arquivos.quitacao_militar') is-invalid text-danger @enderror">
                                Comprovante de quitação com o Serviço Militar, para candidatos
                                do sexo masculino que tenham de 18 a 45 anos - Frente e verso. Para os militares, apresentar cópia frente e verso da carteira de identidade
                                militar;
                            </span>
                            <div class="invalid-feedback">@error('arquivos.quitacao_militar'){{$message}}@enderror</div>
                            @can('dataEnvio', $inscricao->chamada)
                                @if(!$inscricao->arquivo('quitacao_militar'))
                                    @if ($inscricao->isDocumentosRequeridos() || ($inscricao->isArquivoRecusadoOuReenviado('quitacao_militar') && $inscricao->isDocumentosInvalidados()))
                                        <div class="form-check mt-2">
                                            <input class="form-check-input" type="checkbox" name="declaracoes.quitacao_militar " value="true" id="checkquitacao_militar" wire:model="declaracoes.quitacao_militar">
                                            <label class="form-check-label subtexto3" for="checkquitacao_militar">
                                                Comprometo-me a entregar junto ao DRCA/UFAPE o Comprovante de quitação com o Serviço Militar, na
                                                primeira semana de aula.
                                            </label>
                                        </div>
                                    @endif
                                @endif
                            @endcan
                        </div>
                        @if($inscricao->isArquivoRecusado('quitacao_militar'))
                            <div class="mt-2">
                                <div class="alert alert-danger " role="alert">
                                    <h6 style="font-family: Verdana, Geneva, Tahoma, sans-serif; font-size: 15px;" class="alert-heading">Documento recusado!</h6>
                                    <span class="p-zero" style="font-family: Verdana, Geneva, Tahoma, sans-serif; font-size: 13px;" style="color: rgb(197, 0, 0)"><strong>Motivo: </strong>{!!$inscricao->arquivo('quitacao_militar')->avaliacao->comentario!!}</span>
                                </div>
                            </div>
                        @elseif($inscricao->isArquivoAceito('quitacao_militar'))
                            <div class="mt-2">
                                <div class="alert alert-success " role="alert">
                                    <h6 style="font-family: Verdana, Geneva, Tahoma, sans-serif; font-size: 14px;" class="mb-0 alert-heading">Documento validado!</h6>
                                </div>
                            </div>
                        @elseif($inscricao->isArquivoReenviado('quitacao_militar'))
                        <div class="mt-2">
                            <div class="alert alert-primary " role="alert">
                                <h6 style="font-family: Verdana, Geneva, Tahoma, sans-serif; font-size: 14px;" class="mb-0 alert-heading">Documento reenviado!</h6>
                            </div>
                        </div>
                        @endif
                    @endif
                    @if($documentos->contains('foto'))
                        <div class="mt-2">
                            <label for="docFoto"
                                title="Enviar documento"
                                style="cursor:pointer;">
                                <input wire:model="arquivos.foto"
                                    type="file"
                                    class="d-none"
                                    id="docFoto">
                                @can('dataEnvio', $inscricao->chamada)
                                    @if ($inscricao->isDocumentosRequeridos() || ($inscricao->isArquivoRecusadoOuReenviado('foto') && $inscricao->isDocumentosInvalidados()))
                                        <img src="{{ asset('img/upload2.svg') }}"
                                        width="30">
                                    @endif
                                @endcan
                            </label>
                            @if ($inscricao->arquivo('foto'))
                                <a wire:click="baixar('foto')"
                                    title="Baixar documento"
                                    target="_blank"
                                    style="cursor:pointer;">
                                    <img src="{{asset('img/download2.svg')}}"
                                        alt="arquivo atual"
                                        width="30"
                                        class="img-flex"></a>

                                @can('periodoEnvio', $inscricao->chamada)
                                    @if ($inscricao->isDocumentosRequeridos() || ($inscricao->isArquivoRecusadoOuReenviado('foto') && $inscricao->isDocumentosInvalidados()))
                                        <button type="button" title="Deletar documento enviado" data-bs-toggle="modal" data-bs-target="#deletar-arquivo-foto" style="cursor: pointer;"><img width="30" src="{{asset('img/Grupo 1664.svg')}}" alt="Icone de Deletar documento enviado"></button>
                                    @endif
                                @endcan
                            @else
                                <img src="{{ asset('img/download3.svg') }}"
                                    width="30">
                            @endif
                            <span class="subtexto3 @error('arquivos.foto') is-invalid text-danger @enderror">Uma foto 3x4 atual;</span>
                            <div class="invalid-feedback">@error('arquivos.foto'){{$message}}@enderror</div>
                        </div>
                        @if($inscricao->isArquivoRecusado('foto'))
                            <div class="mt-2">
                                <div class="alert alert-danger " role="alert">
                                    <h6 style="font-family: Verdana, Geneva, Tahoma, sans-serif; font-size: 15px;" class="alert-heading">Documento recusado!</h6>
                                    <span class="p-zero" style="font-family: Verdana, Geneva, Tahoma, sans-serif; font-size: 13px;" style="color: rgb(197, 0, 0)"><strong>Motivo: </strong>{!!$inscricao->arquivo('foto')->avaliacao->comentario!!}</span>
                                </div>
                            </div>
                        @elseif($inscricao->isArquivoAceito('foto'))
                            <div class="mt-2">
                                <div class="alert alert-success " role="alert">
                                    <h6 style="font-family: Verdana, Geneva, Tahoma, sans-serif; font-size: 14px;" class="mb-0 alert-heading">Documento validado!</h6>
                                </div>
                            </div>
                        @elseif($inscricao->isArquivoReenviado('foto'))
                        <div class="mt-2">
                            <div class="alert alert-primary " role="alert">
                                <h6 style="font-family: Verdana, Geneva, Tahoma, sans-serif; font-size: 14px;" class="mb-0 alert-heading">Documento reenviado!</h6>
                            </div>
                        </div>
                        @endif
                    @endif
                </li>
                @if ($documentos->contains('declaracao_cotista'))
                    <li class="mt-4 px-1 align-middle">
                        <div class="col-md-12">
                            <div class="tituloEnvio"> Candidato inscrito em cota</div>
                            <div class="subtexto2 my-1">Para concorrer a uma vaga nas cotas, também é necessário o envio destes documentos.</div>
                        </div>
                        <div class="mt-2">
                            <label for="cotista"
                                title="Enviar documento"
                                style="cursor:pointer;">
                                <input wire:model="arquivos.declaracao_cotista"
                                    type="file"
                                    class="d-none"
                                    id="cotista">
                                @can('dataEnvio', $inscricao->chamada)
                                    @if ($inscricao->isDocumentosRequeridos() || ($inscricao->isArquivoRecusadoOuReenviado('declaracao_cotista') && $inscricao->isDocumentosInvalidados()))
                                        <img src="{{ asset('img/upload2.svg') }}"
                                        width="30">
                                    @endif
                                @endcan
                            </label>
                            @if ($inscricao->arquivo('declaracao_cotista'))
                                <a wire:click="baixar('declaracao_cotista')"
                                    title="Baixar documento"
                                    target="_blank"
                                    style="cursor:pointer;">
                                    <img src="{{asset('img/download2.svg')}}"
                                        alt="arquivo atual"
                                        width="30"
                                        class="img-flex"></a>

                                @can('periodoEnvio', $inscricao->chamada)
                                    @if ($inscricao->isDocumentosRequeridos() || ($inscricao->isArquivoRecusadoOuReenviado('declaracao_cotista') && $inscricao->isDocumentosInvalidados()))
                                        <button type="button" title="Deletar documento enviado" data-bs-toggle="modal" data-bs-target="#deletar-arquivo-declaracao_cotista" style="cursor: pointer;"><img width="30" src="{{asset('img/Grupo 1664.svg')}}" alt="Icone de Deletar documento enviado"></button>
                                    @endif
                                @endcan
                            @else
                                <img src="{{ asset('img/download3.svg') }}"
                                    width="30">
                            @endif
                            <span class="subtexto3 @error('arquivos.declaracao_cotista') is-invalid text-danger @enderror">
                                Autodeclaração como candidato participante de reserva de vaga
                                prevista pela Lei nº 12.711/2012, alterada pela Lei nº 13.409/2016,
                                devidamente assinada e preenchida, conforme a modalidade de
                                concorrência;
                            </span>
                            <div class="invalid-feedback">@error('arquivos.declaracao_cotista'){{$message}}@enderror</div>
                        </div>
                    </li>
                    @if($inscricao->isArquivoRecusado('declaracao_cotista'))
                        <div class="mt-2">
                            <div class="alert alert-danger " role="alert">
                                <h6 style="font-family: Verdana, Geneva, Tahoma, sans-serif; font-size: 15px;" class="alert-heading">Documento recusado!</h6>
                                <span class="p-zero" style="font-family: Verdana, Geneva, Tahoma, sans-serif; font-size: 13px;" style="color: rgb(197, 0, 0)"><strong>Motivo: </strong>{!!$inscricao->arquivo('declaracao_cotista')->avaliacao->comentario!!}</span>
                            </div>
                        </div>
                    @elseif($inscricao->isArquivoAceito('declaracao_cotista'))
                        <div class="mt-2">
                            <div class="alert alert-success " role="alert">
                                <h6 style="font-family: Verdana, Geneva, Tahoma, sans-serif; font-size: 14px;" class="mb-0 alert-heading">Documento validado!</h6>
                            </div>
                        </div>
                    @elseif($inscricao->isArquivoReenviado('declaracao_cotista'))
                        <div class="mt-2">
                            <div class="alert alert-primary " role="alert">
                                <h6 style="font-family: Verdana, Geneva, Tahoma, sans-serif; font-size: 14px;" class="mb-0 alert-heading">Documento reenviado!</h6>
                            </div>
                        </div>
                    @endif
                @endif
                @if ($documentos->contains('heteroidentificacao'))
                    <li class="mt-4 px-1 align-middle">
                        <div class="col-md-12">
                            <div class="tituloEnvio"> Comprovação da condição de beneficiário da reserva de
                                vaga para candidato autodeclarado negro (preto ou
                                pardo) </div>
                            <div class="subtexto2 my-1">
                                Você está concorrendo a uma vaga de cota de candidato autodeclarado negro (preto ou pardo), portanto deve enviar o respectivo comprovante.</div>
                        </div>

                        <div class="mt-2">
                            <label for="docHeteroidentificacao"
                                title="Enviar documento"
                                style="cursor:pointer;">
                                <input wire:model="arquivos.heteroidentificacao"
                                    type="file"
                                    class="d-none"
                                    id="docHeteroidentificacao">
                                @can('dataEnvio', $inscricao->chamada)
                                    @if ($inscricao->isDocumentosRequeridos() || ($inscricao->isArquivoRecusadoOuReenviado('heteroidentificacao') && $inscricao->isDocumentosInvalidados()))
                                        <img src="{{ asset('img/upload2.svg') }}"
                                        width="30">
                                    @endif
                                @endcan
                            </label>
                            @if ($inscricao->arquivo('heteroidentificacao'))
                                <a wire:click="baixar('heteroidentificacao')"
                                    title="Baixar documento"
                                    target="_blank"
                                    style="cursor:pointer;">
                                    <img src="{{asset('img/download2.svg')}}"
                                        alt="arquivo atual"
                                        width="30"
                                        class="img-flex"></a>

                                @can('periodoEnvio', $inscricao->chamada)
                                    @if ($inscricao->isDocumentosRequeridos() || ($inscricao->isArquivoRecusadoOuReenviado('heteroidentificacao') && $inscricao->isDocumentosInvalidados()))
                                        <button type="button" title="Deletar documento enviado" data-bs-toggle="modal" data-bs-target="#deletar-arquivo-heteroidentificacao" style="cursor: pointer;"><img width="30" src="{{asset('img/Grupo 1664.svg')}}" alt="Icone de Deletar documento enviado"></button>
                                    @endif
                                @endcan
                            @else
                                <img src="{{ asset('img/download3.svg') }}"
                                    width="30">
                            @endif
                            <span class="subtexto3 @error('arquivos.heteroidentificacao') is-invalid text-danger @enderror">
                                Vídeo individual e recente para procedimento de heteroidentificação.
                                De acordo com as especificações e o roteiro descritos no edital do
                                processo de seleção SISU 2022 da UFAPE;</span>
                            <div class="invalid-feedback">@error('arquivos.heteroidentificacao'){{$message}}@enderror</div>
                            @if($inscricao->isArquivoRecusado('heteroidentificacao'))
                                <div class="mt-2">
                                    <div class="alert alert-danger " role="alert">
                                        <h6 style="font-family: Verdana, Geneva, Tahoma, sans-serif; font-size: 15px;" class="alert-heading">Documento recusado!</h6>
                                        <span class="p-zero" style="font-family: Verdana, Geneva, Tahoma, sans-serif; font-size: 13px;" style="color: rgb(197, 0, 0)"><strong>Motivo: </strong>{!!$inscricao->arquivo('heteroidentificacao')->avaliacao->comentario!!}</span>
                                    </div>
                                </div>
                            @elseif($inscricao->isArquivoAceito('heteroidentificacao'))
                                <div class="mt-2">
                                    <div class="alert alert-success " role="alert">
                                        <h6 style="font-family: Verdana, Geneva, Tahoma, sans-serif; font-size: 14px;" class="mb-0 alert-heading">Documento validado!</h6>
                                    </div>
                                </div>
                            @elseif($inscricao->isArquivoReenviado('heteroidentificacao'))
                        <div class="mt-2">
                            <div class="alert alert-primary " role="alert">
                                <h6 style="font-family: Verdana, Geneva, Tahoma, sans-serif; font-size: 14px;" class="mb-0 alert-heading">Documento reenviado!</h6>
                            </div>
                        </div>
                            @endif
                        </div>
                        <div class="mt-2">
                            <label for="docFotografia"
                                title="Enviar documento"
                                style="cursor:pointer;">
                                <input wire:model="arquivos.fotografia"
                                    type="file"
                                    class="d-none"
                                    id="docFotografia">
                                @can('dataEnvio', $inscricao->chamada)
                                    @if ($inscricao->isDocumentosRequeridos() || ($inscricao->isArquivoRecusadoOuReenviado('fotografia') && $inscricao->isDocumentosInvalidados()))
                                        <img src="{{ asset('img/upload2.svg') }}"
                                        width="30">
                                    @endif
                                @endcan
                            </label>
                            @if ($inscricao->arquivo('fotografia'))
                                <a wire:click="baixar('fotografia')"
                                    title="Baixar documento"
                                    target="_blank"
                                    style="cursor:pointer;">
                                    <img src="{{asset('img/download2.svg')}}"
                                        alt="arquivo atual"
                                        width="30"
                                        class="img-flex"></a>

                                @can('periodoEnvio', $inscricao->chamada)
                                    @if ($inscricao->isDocumentosRequeridos() || ($inscricao->isArquivoRecusadoOuReenviado('fotografia') && $inscricao->isDocumentosInvalidados()))
                                        <button type="button" title="Deletar documento enviado" data-bs-toggle="modal" data-bs-target="#deletar-arquivo-fotografia" style="cursor: pointer;"><img width="30" src="{{asset('img/Grupo 1664.svg')}}" alt="Icone de Deletar documento enviado"></button>
                                    @endif
                                @endcan
                            @else
                                <img src="{{ asset('img/download3.svg') }}"
                                    width="30">
                            @endif
                            <span class="subtexto3 @error('arquivos.fotografia') is-invalid text-danger @enderror">
                                Fotografia individual e recente para procedimento de
                                heteroidentificação. Conforme especificado no edital do processo de
                                seleção SISU 2022 da UFAPE;</span>
                            <div class="invalid-feedback">@error('arquivos.fotografia'){{$message}}@enderror</div>
                            @if($inscricao->isArquivoRecusado('fotografia'))
                                <div class="mt-2">
                                    <div class="alert alert-danger " role="alert">
                                        <h6 style="font-family: Verdana, Geneva, Tahoma, sans-serif; font-size: 15px;" class="alert-heading">Documento recusado!</h6>
                                        <span class="p-zero" style="font-family: Verdana, Geneva, Tahoma, sans-serif; font-size: 13px;" style="color: rgb(197, 0, 0)"><strong>Motivo: </strong>{!!$inscricao->arquivo('heteroidentificacao')->avaliacao->comentario!!}</span>
                                    </div>
                                </div>
                            @elseif($inscricao->isArquivoAceito('fotografia'))
                                <div class="mt-2">
                                    <div class="alert alert-success " role="alert">
                                        <h6 style="font-family: Verdana, Geneva, Tahoma, sans-serif; font-size: 14px;" class="mb-0 alert-heading">Documento validado!</h6>
                                    </div>
                                </div>
                            @elseif($inscricao->isArquivoReenviado('fotografia'))
                        <div class="mt-2">
                            <div class="alert alert-primary " role="alert">
                                <h6 style="font-family: Verdana, Geneva, Tahoma, sans-serif; font-size: 14px;" class="mb-0 alert-heading">Documento reenviado!</h6>
                            </div>
                        </div>
                            @endif
                        </div>
                    </li>
                @endif
                @if ($documentos->contains('comprovante_renda'))
                    <li class="mt-4 px-1 align-middle">
                        <div class="col-md-12">
                            <div class="tituloEnvio">Comprovação da renda familiar bruta mensal per capita </div>
                            <div class="subtexto2 my-1">Você está concorrendo a uma vaga de cota de renda, portanto deve enviar o documento de renda familiar bruta mensal per capita.</div>
                        </div>
                        <div class="mt-2">
                            <label for="cotaRenda"
                                title="Enviar documento"
                                style="cursor:pointer;">
                                <input wire:model="arquivos.comprovante_renda"
                                    type="file"
                                    class="d-none"
                                    id="cotaRenda">
                                @can('dataEnvio', $inscricao->chamada)
                                    @if ($inscricao->isDocumentosRequeridos() || ($inscricao->isArquivoRecusadoOuReenviado('comprovante_renda') && $inscricao->isDocumentosInvalidados()))
                                        <img src="{{ asset('img/upload2.svg') }}"
                                        width="30">
                                    @endif
                                @endcan
                            </label>
                            @if ($inscricao->arquivo('comprovante_renda'))
                                <a wire:click="baixar('comprovante_renda')"
                                    title="Baixar documento"
                                    target="_blank"
                                    style="cursor:pointer;">
                                    <img src="{{asset('img/download2.svg')}}"
                                        alt="arquivo atual"
                                        width="30"
                                        class="img-flex"></a>

                                @can('periodoEnvio', $inscricao->chamada)
                                    @if ($inscricao->isDocumentosRequeridos() || ($inscricao->isArquivoRecusadoOuReenviado('comprovante_renda') && $inscricao->isDocumentosInvalidados()))
                                        <button type="button" title="Deletar documento enviado" data-bs-toggle="modal" data-bs-target="#deletar-arquivo-comprovante_renda" style="cursor: pointer;"><img width="30" src="{{asset('img/Grupo 1664.svg')}}" alt="Icone de Deletar documento enviado"></button>
                                    @endif
                                @endcan
                            @else
                                <img src="{{ asset('img/download3.svg') }}"
                                    width="30">
                            @endif
                            <span class="subtexto3 @error('arquivos.comprovante_renda') is-invalid text-danger @enderror">
                                Comprovante de renda, ou de que não possui renda, de cada membro
                                do grupo familiar, seja maior ou menor de idade;
                            </span>
                            <div class="invalid-feedback">@error('arquivos.comprovante_renda'){{$message}}@enderror</div>
                        </div>
                    </li>
                    @if($inscricao->isArquivoRecusado('comprovante_renda'))
                        <div class="mt-2">
                            <div class="alert alert-danger " role="alert">
                                <h6 style="font-family: Verdana, Geneva, Tahoma, sans-serif; font-size: 15px;" class="alert-heading">Documento recusado!</h6>
                                <span class="p-zero" style="font-family: Verdana, Geneva, Tahoma, sans-serif; font-size: 13px;" style="color: rgb(197, 0, 0)"><strong>Motivo: </strong>{!!$inscricao->arquivo('comprovante_renda')->avaliacao->comentario!!}</span>
                            </div>
                        </div>
                    @elseif($inscricao->isArquivoAceito('comprovante_renda'))
                        <div class="mt-2">
                            <div class="alert alert-success " role="alert">
                                <h6 style="font-family: Verdana, Geneva, Tahoma, sans-serif; font-size: 14px;" class="mb-0 alert-heading">Documento validado!</h6>
                            </div>
                        </div>
                    @elseif($inscricao->isArquivoReenviado('comprovante_renda'))
                        <div class="mt-2">
                            <div class="alert alert-primary " role="alert">
                                <h6 style="font-family: Verdana, Geneva, Tahoma, sans-serif; font-size: 14px;" class="mb-0 alert-heading">Documento reenviado!</h6>
                            </div>
                        </div>
                    @endif
                @endif
                @if ($documentos->contains('rani'))
                    <li class="mt-4 px-1 align-middle">
                        <div class="col-md-12">
                            <div class="tituloEnvio">Comprovação da condição de beneficiário da reserva de
                                vaga para candidato autodeclarado indígena</div>
                            <div class="subtexto2 my-1">Você está concorrendo a uma vaga de cota indígena, portanto deve enviar o respectivo comprovante.</div>
                        </div>
                        <div class="mt-2">
                            <label for="rani"
                                title="Enviar documento"
                                style="cursor:pointer;">
                                <input wire:model="arquivos.rani"
                                    type="file"
                                    class="d-none"
                                    id="rani">
                                @can('dataEnvio', $inscricao->chamada)
                                    @if ($inscricao->isDocumentosRequeridos() || ($inscricao->isArquivoRecusadoOuReenviado('rani') && $inscricao->isDocumentosInvalidados()))
                                        <img src="{{ asset('img/upload2.svg') }}"
                                        width="30">
                                    @endif
                                @endcan
                            </label>
                            @if ($inscricao->arquivo('rani'))
                                <a wire:click="baixar('rani')"
                                    title="Baixar documento"
                                    target="_blank"
                                    style="cursor:pointer;">
                                    <img src="{{asset('img/download2.svg')}}"
                                        alt="arquivo atual"
                                        width="30"
                                        class="img-flex"></a>

                                @can('periodoEnvio', $inscricao->chamada)
                                    @if ($inscricao->isDocumentosRequeridos() || ($inscricao->isArquivoRecusadoOuReenviado('rani') && $inscricao->isDocumentosInvalidados()))
                                        <button type="button" title="Deletar documento enviado" data-bs-toggle="modal" data-bs-target="#deletar-arquivo-rani" style="cursor: pointer;"><img width="30" src="{{asset('img/Grupo 1664.svg')}}" alt="Icone de Deletar documento enviado"></button>
                                    @endif
                                @endcan
                            @else
                                <img src="{{ asset('img/download3.svg') }}"
                                    width="30">
                            @endif
                            <span class="subtexto3 @error('arquivos.rani') is-invalid text-danger @enderror">
                                Registro Administrativo de Nascimento de Indígena (RANI)
                                ou declaração de vínculo com comunidade indígena brasileira à qual
                                pertença emitida por liderança indígena reconhecida ou por ancião
                                indígena reconhecido ou por personalidade indígena de reputação
                                pública reconhecida ou outro documento emitido por órgãos
                                públicos que contenham informações pertinentes à sua condição de
                                indígena;
                            </span>
                            <div class="invalid-feedback">@error('arquivos.rani'){{$message}}@enderror</div>
                        </div>
                    </li>
                    @if($inscricao->isArquivoRecusado('rani'))
                        <div class="mt-2">
                            <div class="alert alert-danger " role="alert">
                                <h6 style="font-family: Verdana, Geneva, Tahoma, sans-serif; font-size: 15px;" class="alert-heading">Documento recusado!</h6>
                                <span class="p-zero" style="font-family: Verdana, Geneva, Tahoma, sans-serif; font-size: 13px;" style="color: rgb(197, 0, 0)"><strong>Motivo: </strong>{!!$inscricao->arquivo('rani')->avaliacao->comentario!!}</span>
                            </div>
                        </div>
                    @elseif($inscricao->isArquivoAceito('rani'))
                        <div class="mt-2">
                            <div class="alert alert-success " role="alert">
                                <h6 style="font-family: Verdana, Geneva, Tahoma, sans-serif; font-size: 14px;" class="mb-0 alert-heading">Documento validado!</h6>
                            </div>
                        </div>
                    @elseif($inscricao->isArquivoReenviado('rani'))
                        <div class="mt-2">
                            <div class="alert alert-primary " role="alert">
                                <h6 style="font-family: Verdana, Geneva, Tahoma, sans-serif; font-size: 14px;" class="mb-0 alert-heading">Documento reenviado!</h6>
                            </div>
                        </div>
                    @endif
                @endif
                @if ($documentos->contains('laudo_medico'))
                    <li class="mt-4 px-1 align-middle">
                        <div class="col-md-12">
                            <div class="tituloEnvio">Comprovação da condição de beneficiário da reserva de
                                vaga para pessoas com deficiência
                            </div>
                            <div class="subtexto2 my-1">Você está concorrendo a uma vaga para pessoas com deficiência, portanto deve enviar o respectivo comprovante.</div>
                        </div>
                        <div class="mt-2">
                            <label for="cotaPCD"
                                title="Enviar documento"
                                style="cursor:pointer;">
                                <input wire:model="arquivos.laudo_medico"
                                    type="file"
                                    class="d-none"
                                    id="cotaPCD">
                                @can('dataEnvio', $inscricao->chamada)
                                    @if ($inscricao->isDocumentosRequeridos() || ($inscricao->isArquivoRecusadoOuReenviado('laudo_medico') && $inscricao->isDocumentosInvalidados()))
                                        <img src="{{ asset('img/upload2.svg') }}"
                                        width="30">
                                    @endif
                                @endcan
                            </label>
                            @if ($inscricao->arquivo('laudo_medico'))
                                <a wire:click="baixar('laudo_medico')"
                                    title="Baixar documento"
                                    target="_blank"
                                    style="cursor:pointer;">
                                    <img src="{{asset('img/download2.svg')}}"
                                        alt="arquivo atual"
                                        width="30"
                                        class="img-flex"></a>

                                @can('periodoEnvio', $inscricao->chamada)
                                    @if ($inscricao->isDocumentosRequeridos() || ($inscricao->isArquivoRecusadoOuReenviado('laudo_medico') && $inscricao->isDocumentosInvalidados()))
                                        <button type="button" title="Deletar documento enviado" data-bs-toggle="modal" data-bs-target="#deletar-arquivo-laudo_medico" style="cursor: pointer;"><img width="30" src="{{asset('img/Grupo 1664.svg')}}" alt="Icone de Deletar documento enviado"></button>
                                    @endif
                                @endcan
                            @else
                                <img src="{{ asset('img/download3.svg') }}"
                                    width="30">
                            @endif
                            <span class="subtexto3 @error('arquivos.laudo_medico') is-invalid text-danger @enderror">
                                Laudo Médico e exames de comprovação da condição de
                                beneficiário da reserva de vaga para pessoas com deficiência
                            </span>
                            <div class="invalid-feedback">@error('arquivos.laudo_medico'){{$message}}@enderror</div>
                        </div>
                    </li>
                    @if($inscricao->isArquivoRecusado('laudo_medico'))
                        <div class="mt-2">
                            <div class="alert alert-danger " role="alert">
                                <h6 style="font-family: Verdana, Geneva, Tahoma, sans-serif; font-size: 15px;" class="alert-heading">Documento recusado!</h6>
                                <span class="p-zero" style="font-family: Verdana, Geneva, Tahoma, sans-serif; font-size: 13px;" style="color: rgb(197, 0, 0)"><strong>Motivo: </strong>{!!$inscricao->arquivo('laudo_medico')->avaliacao->comentario!!}</span>
                            </div>
                        </div>
                    @elseif($inscricao->isArquivoAceito('laudo_medico'))
                        <div class="mt-2">
                            <div class="alert alert-success " role="alert">
                                <h6 style="font-family: Verdana, Geneva, Tahoma, sans-serif; font-size: 14px;" class="mb-0 alert-heading">Documento validado!</h6>
                            </div>
                        </div>
                    @elseif($inscricao->isArquivoReenviado('laudo_medico'))
                        <div class="mt-2">
                            <div class="alert alert-primary " role="alert">
                                <h6 style="font-family: Verdana, Geneva, Tahoma, sans-serif; font-size: 14px;" class="mb-0 alert-heading">Documento reenviado!</h6>
                            </div>
                        </div>
                    @endif
                @endif
            </ul>
        </form>
    </div>
    <div class="d-flex flex-wrap justify-content-between mt-5">
        <div>
            <a href="{{route('inscricaos.index')}}"
                class="btn botao my-2 py-1">
                <span class="px-4">Voltar</span>
            </a>
        </div>
        @can('dataEnvio', $inscricao->chamada)
            @if ($inscricao->isDocumentosRequeridos() || $inscricao->isDocumentosInvalidados())
                <div class="d-flex justify-content-end">
                    <div>
                        <button type="button"
                            data-bs-toggle="modal"
                            data-bs-target="#modal-confirmar"
                            class="btn botaoVerde my-2 py-1">
                            <span class="px-4">Enviar</span>
                        </button>
                    </div>
                </div>
            @endif
        @endcan
    </div>
    {{-- Modal de confirmação --}}
    <div class="modal fade" id="modal-confirmar" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-dialog">
                <div class="modal-content modalFundo p-3">
                    <div class="col-md-12 tituloModal">Enviar documentos</div>
                        <div class="pt-3 pb-2 textoModal">
                            Tem certeza que você deseja confirmar o envio dos documentos? Após essa confirmação você não poderá mais editar/enviar documentos.
                            <div class="d-flex flex-wrap justify-content-between mt-4">
                                <div class="col-md-4">
                                    <button type="button" class="btn botao my-2 py-1" data-bs-dismiss="modal"><span>Cancelar envio</span></button>
                                </div>
                                <div class="col-md-4">
                                    <div class="d-flex justify-content-end">
                                        <button type="submit"
                                            class="btn botaoVerde my-2 py-1"
                                            data-bs-dismiss="modal"
                                            form="enviar-documentos">
                                            <span class="px-4">Confirmar</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @foreach ($documentos as $documento)
        @if ($inscricao->arquivo($documento))
            @can('dataEnvio', $inscricao->chamada)
                @if ($inscricao->isDocumentosRequeridos() || ($inscricao->isArquivoRecusadoOuReenviado($documento) && $inscricao->isDocumentosInvalidados()))
                    <!-- Modal Deletar documento enviado -->
                    <div class="modal fade" id="deletar-arquivo-{{$documento}}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content modalFundo p-3">
                                <div class="col-md-12 tituloModal">Excluir {{$nomes[$documento]}}</div>
                                <div class="pt-3">
                                    Tem certeza que deseja deletar {{$nomes[$documento]}}?
                                </div>
                                <div class="row justify-content-between mt-4">
                                    <div class="col-md-3">
                                        <button type="button" class="btn botao my-2 py-1" data-bs-dismiss="modal"> <span class="px-4" style="font-weight: bolder;">Voltar</span></button>
                                    </div>
                                    <div class="col-md-4">
                                        <button type="button" data-bs-dismiss="modal" wire:click="apagar('{{$documento}}')" class="btn botaoVerde my-2 py-1" style="background-color: #FC605F;"><span class="px-4" style="font-weight: bolder;" >Excluir</span></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endcan
        @endif
    @endforeach
</div>
