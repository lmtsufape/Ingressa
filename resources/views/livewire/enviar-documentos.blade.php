<div>
    @can('periodoEnvio', $inscricao->chamada)
        @if ($inscricao->isDocumentosRequeridos())
            <div clss="mb-5">
                <form id="enviar-documentos"
                    wire:submit.prevent="submit"
                    enctype="multipart/form-data">
                    <ul class="timeline">
                        <li class="px-1 align-middle">
                            <div class="col-md-12">
                                <div class="tituloEnvio"> Documentação básica </div>
                                <div class="subtexto2 my-1">A documentação básica corresponde a documentação comum a todos os candidatos.</div>
                            </div>
                            @if ($documentos->contains('declaracao_veracidade'))
                                <div class="mt-2">
                                    <x-botao-enviar-documento documento="declaracao_veracidade"/>
                                    @if ($inscricao->arquivo('declaracao_veracidade'))
                                        <x-botao-baixar-documento documento="declaracao_veracidade"/>
                                        <button type="button" title="Deletar documento enviado" data-bs-toggle="modal" data-bs-target="#deletar-arquivo-declaracao_veracidade" style="cursor: pointer;"><img width="30" src="{{asset('img/Grupo 1664.svg')}}" alt="Icone de Deletar documento enviado"></button>
                                    @else
                                        <img src="{{ asset('img/download3.svg') }}" width="30">
                                    @endif
                                    <span class="subtexto3 @error('arquivos.declaracao_veracidade') is-invalid text-danger @enderror">
                                        Declaração de Veracidade (preencher e assinar modelo disponível em: <a href="http://www.ufape.edu.br/sisu-2022">www.ufape.edu.br/sisu-2022</a>)
                                    </span>
                                    <div class="invalid-feedback">@error('arquivos.declaracao_veracidade'){{$message}}@enderror</div>
                                </div>
                            @endif
                            @if ($documentos->contains('certificado_conclusao'))
                                <div class="mt-2">
                                    <x-botao-enviar-documento documento="certificado_conclusao"/>
                                    @if ($inscricao->arquivo('certificado_conclusao'))
                                        <x-botao-baixar-documento documento="certificado_conclusao"/>
                                        <button type="button" title="Deletar documento enviado" data-bs-toggle="modal" data-bs-target="#deletar-arquivo-certificado_conclusao" style="cursor: pointer;"><img width="30" src="{{asset('img/Grupo 1664.svg')}}" alt="Icone de Deletar documento enviado"></button>
                                    @else
                                        <img src="{{ asset('img/download3.svg') }}" width="30">
                                    @endif
                                    <span class="subtexto3 @error('arquivos.certificado_conclusao') is-invalid text-danger @enderror">
                                        Certificado de Conclusão do Ensino Médio ou Certidão de Exame Supletivo do Ensino
                                        Médio ou Certificação de Ensino Médio através do ENEM ou documento equivalente.
                                        <b>OBS.</b>: Pode estar junto com o Histórico Escolar (escanear frente e verso da Ficha 19),
                                        neste caso anexar o arquivo nos dois campos (“certificado de conclusão do ensino
                                        médio” e “histórico escolar”)
                                    </span>
                                    <div class="invalid-feedback">@error('arquivos.certificado_conclusao'){{$message}}@enderror</div>
                                </div>
                            @endif
                            @if($documentos->contains('historico'))
                                <div class="mt-2">
                                    @if (in_array($declaracoes['historico'], [null, '']))
                                        <x-botao-enviar-documento documento="historico"/>
                                    @endif
                                    @if ($inscricao->arquivo('historico'))
                                        <x-botao-baixar-documento documento="historico"/>
                                        <button type="button" title="Deletar documento enviado" data-bs-toggle="modal" data-bs-target="#deletar-arquivo-historico" style="cursor: pointer;"><img width="30" src="{{asset('img/Grupo 1664.svg')}}" alt="Icone de Deletar documento enviado"></button>
                                    @else
                                        <img src="{{ asset('img/download3.svg') }}" width="30">
                                    @endif
                                    <span class="subtexto3 @error('arquivos.historico') is-invalid text-danger @enderror">
                                        Histórico Escolar do Ensino Médio ou Equivalente. <b>OBS.</b>: Pode estar junto com o Histórico
                                        Escolar (escanear frente e verso da Ficha 19), neste caso anexar o arquivo nos dois
                                        campos (“certificado de conclusão do ensino médio” e “histórico escolar”)
                                    </span>
                                    <div class="invalid-feedback">@error('arquivos.historico'){{$message}}@enderror</div>
                                    @if(!$inscricao->arquivo('historico'))
                                        <div class="form-check mt-2">
                                            <input class="form-check-input" type="checkbox" name="declaracoes.historico" value="true" id="checkHistorico" wire:model="declaracoes.historico">
                                            <label class="form-check-label subtexto3" for="checkHistorico">
                                                Comprometo-me a entregar junto ao DRCA/UFAPE o Histórico Escolar do Ensino Médio ou Equivalente, na
                                                primeira semana de aula.
                                            </label>
                                        </div>
                                    @endif
                                </div>
                            @endif
                            @if($documentos->contains('nascimento_ou_casamento'))
                                <div class="mt-2">
                                    @if (in_array($declaracoes['nascimento_ou_casamento'], [null, '']))
                                        <x-botao-enviar-documento documento="nascimento_ou_casamento"/>
                                    @endif
                                    @if ($inscricao->arquivo('nascimento_ou_casamento'))
                                        <x-botao-baixar-documento documento="nascimento_ou_casamento"/>
                                        <button type="button" title="Deletar documento enviado" data-bs-toggle="modal" data-bs-target="#deletar-arquivo-nascimento_ou_casamento" style="cursor: pointer;"><img width="30" src="{{asset('img/Grupo 1664.svg')}}" alt="Icone de Deletar documento enviado"></button>
                                    @else
                                        <img src="{{ asset('img/download3.svg') }}" width="30">
                                    @endif
                                    <span class="subtexto3 @error('arquivos.nascimento_ou_casamento') is-invalid text-danger @enderror">
                                        Registro de Nascimento ou Certidão de Casamento
                                    </span>
                                    <div class="invalid-feedback">@error('arquivos.nascimento_ou_casamento'){{$message}}@enderror</div>
                                    @if(!$inscricao->arquivo('nascimento_ou_casamento'))
                                        <div class="form-check mt-2">
                                            <input class="form-check-input" type="checkbox" value="true" id="checkNascimento_casamento" wire:model="declaracoes.nascimento_ou_casamento">
                                            <label class="form-check-label subtexto3" for="checkNascimento_casamento">
                                                Comprometo-me a entregar junto ao DRCA/UFAPE o Registro de Nascimento ou Certidão de Casamento, na
                                                primeira semana de aula.
                                            </label>
                                        </div>
                                    @endif
                                </div>
                            @endif
                            @if($documentos->contains('rg'))
                                <div class="mt-2">
                                    <x-botao-enviar-documento documento="rg"/>
                                    @if ($inscricao->arquivo('rg'))
                                        <x-botao-baixar-documento documento="rg"/>
                                        <button type="button" title="Deletar documento enviado" data-bs-toggle="modal" data-bs-target="#deletar-arquivo-rg" style="cursor: pointer;"><img width="30" src="{{asset('img/Grupo 1664.svg')}}" alt="Icone de Deletar documento enviado"></button>
                                    @else
                                        <img src="{{ asset('img/download3.svg') }}" width="30">
                                    @endif
                                    <span class="subtexto3 @error('arquivos.rg') is-invalid text-danger @enderror">
                                        Carteira de Identidade válida e com foto recente (RG) - escanear frente e verso. <b>OBS.</b>:
                                        Caso tenha perdido ou sido roubado, anexar um Boletim de Ocorrência e algum outro
                                        documento com foto. A Carteira Nacional de Habilitação pode ser utilizada como
                                        documento com foto, mas não será aceita em substituição ao RG e ao CPF
                                    </span>
                                    <div class="invalid-feedback">@error('arquivos.rg'){{$message}}@enderror</div>
                                </div>
                            @endif
                            @if($documentos->contains('cpf'))
                                <div class="mt-2">
                                    <x-botao-enviar-documento documento="cpf"/>
                                    @if ($inscricao->arquivo('cpf'))
                                        <x-botao-baixar-documento documento="cpf"/>
                                        <button type="button" title="Deletar documento enviado" data-bs-toggle="modal" data-bs-target="#deletar-arquivo-cpf" style="cursor: pointer;"><img width="30" src="{{asset('img/Grupo 1664.svg')}}" alt="Icone de Deletar documento enviado"></button>
                                    @else
                                        <img src="{{ asset('img/download3.svg') }}" width="30">
                                    @endif
                                    <span class="subtexto3 @error('arquivos.cpf') is-invalid text-danger @enderror">
                                        Cadastro de Pessoa Física (CPF). <b>OBS.</b>: Caso conste o número do CPF na identidade (RG),
                                        anexar cópia da identidade, frente e verso. Caso tenha perdido ou sido
                                        roubado, emitir Comprovante de Situação Cadastral no CPF, através do
                                    </span>
                                    <a href="https://servicos.receita.fazenda.gov.br/servicos/cpf/consultasituacao/consultapublica.asp" target="_blank" rel="noopener noreferrer">site da Receita Federal</a>
                                    <div class="invalid-feedback">@error('arquivos.cpf'){{$message}}@enderror</div>
                                </div>
                            @endif
                            @if($documentos->contains('quitacao_eleitoral'))
                                <div class="mt-2">
                                    @if (in_array($declaracoes['quitacao_eleitoral'], [null, '']))
                                        <x-botao-enviar-documento documento="quitacao_eleitoral"/>
                                    @endif
                                    @if ($inscricao->arquivo('quitacao_eleitoral'))
                                        <x-botao-baixar-documento documento="quitacao_eleitoral"/>
                                        <button type="button" title="Deletar documento enviado" data-bs-toggle="modal" data-bs-target="#deletar-arquivo-quitacao_eleitoral" style="cursor: pointer;"><img width="30" src="{{asset('img/Grupo 1664.svg')}}" alt="Icone de Deletar documento enviado"></button>
                                    @else
                                        <img src="{{ asset('img/download3.svg') }}" width="30">
                                    @endif
                                    <span class="subtexto3 @error('arquivos.quitacao_eleitoral') is-invalid text-danger @enderror">
                                        Comprovante de quitação com o Serviço Eleitoral no último turno de votação ou Certidão de
                                        quitação eleitoral. <b>OBS.</b>:  Essa certidão poderá ser emitida no
                                        <a href="https://www.tse.jus.br/eleitor/certidoes/certidao-de-quitacao-eleitoral" target="_blank" rel="noopener noreferrer">
                                        site do Tribunal Superior Eleitoral.</a> Caso a certidão de quitação eleitoral não possa ser emitida em função de
                                        pagamento de multas eleitorais, poderá ser apresentada cópia (captura da
                                        tela) do relatório de quitação de débitos do eleitor (quitação de multas,
                                        disponível no
                                        <a href="https://www.tse.jus.br/" target="_blank" rel="noopener noreferrer">site do Tribunal Superior Eleitoral</a>)
                                    </span>
                                    <div class="invalid-feedback">@error('arquivos.quitacao_eleitoral'){{$message}}@enderror</div>
                                    @if(!$inscricao->arquivo('quitacao_eleitoral'))
                                        <div class="form-check mt-2">
                                            <input class="form-check-input" type="checkbox" name="declaracoes.quitacao_eleitoral " value="true" id="checkquitacao_eleitoral" wire:model="declaracoes.quitacao_eleitoral">
                                            <label class="form-check-label subtexto3" for="checkquitacao_eleitoral">
                                                Comprometo-me a entregar junto ao DRCA/UFAPE o Comprovante de quitação com o Serviço Eleitoral, na
                                                primeira semana de aula.
                                            </label>
                                        </div>
                                    @endif
                                </div>
                            @endif
                            @if($documentos->contains('quitacao_militar'))
                                <div class="mt-2">
                                    @if (in_array($declaracoes['quitacao_militar'], [null, '']))
                                        <x-botao-enviar-documento documento="quitacao_militar"/>
                                    @endif
                                    @if ($inscricao->arquivo('quitacao_militar'))
                                        <x-botao-baixar-documento documento="quitacao_militar"/>
                                        <button type="button" title="Deletar documento enviado" data-bs-toggle="modal" data-bs-target="#deletar-arquivo-quitacao_militar" style="cursor: pointer;"><img width="30" src="{{asset('img/Grupo 1664.svg')}}" alt="Icone de Deletar documento enviado"></button>
                                    @else
                                        <img src="{{ asset('img/download3.svg') }}" width="30">
                                    @endif
                                    <span class="subtexto3 @error('arquivos.quitacao_militar') is-invalid text-danger @enderror">
                                        Comprovante de quitação com o Serviço Militar, para candidatos
                                        do sexo masculino que tenham de 18 a 45 anos - Frente e verso. <b>OBS.</b>:  Para os militares, apresentar cópia frente e verso da carteira de identidade
                                        militar
                                    </span>
                                    <div class="invalid-feedback">@error('arquivos.quitacao_militar'){{$message}}@enderror</div>
                                    @if(!$inscricao->arquivo('quitacao_militar'))
                                        <div class="form-check mt-2">
                                            <input class="form-check-input" type="checkbox" name="declaracoes.quitacao_militar " value="true" id="checkquitacao_militar" wire:model="declaracoes.quitacao_militar">
                                            <label class="form-check-label subtexto3" for="checkquitacao_militar">
                                                Comprometo-me a entregar junto ao DRCA/UFAPE o Comprovante de quitação com o Serviço Militar, na
                                                primeira semana de aula.
                                            </label>
                                        </div>
                                    @endif
                                </div>
                            @endif
                            @if($documentos->contains('foto'))
                                <div class="mt-2">
                                    <x-botao-enviar-documento documento="foto"/>
                                    @if ($inscricao->arquivo('foto'))
                                        <x-botao-baixar-documento documento="foto"/>
                                        <button type="button" title="Deletar documento enviado" data-bs-toggle="modal" data-bs-target="#deletar-arquivo-foto" style="cursor: pointer;"><img width="30" src="{{asset('img/Grupo 1664.svg')}}" alt="Icone de Deletar documento enviado"></button>
                                    @else
                                        <img src="{{ asset('img/download3.svg') }}" width="30">
                                    @endif
                                    <span class="subtexto3 @error('arquivos.foto') is-invalid text-danger @enderror">Uma foto 3x4 atual</span>
                                    <div class="invalid-feedback">@error('arquivos.foto'){{$message}}@enderror</div>
                                </div>
                            @endif
                        </li>
                        @if ($documentos->contains('declaracao_cotista'))
                            <li class="mt-4 px-1 align-middle">
                                <div class="col-md-12">
                                    <div class="tituloEnvio"> Candidato inscrito em cota</div>
                                    <div class="subtexto2 my-1">Para concorrer a uma vaga nas cotas, também é necessário o envio destes documentos.</div>
                                </div>
                                <div class="mt-2">
                                    <x-botao-enviar-documento documento="declaracao_cotista"/>
                                    @if ($inscricao->arquivo('declaracao_cotista'))
                                        <x-botao-baixar-documento documento="declaracao_cotista"/>
                                        <button type="button" title="Deletar documento enviado" data-bs-toggle="modal" data-bs-target="#deletar-arquivo-declaracao_cotista" style="cursor: pointer;"><img width="30" src="{{asset('img/Grupo 1664.svg')}}" alt="Icone de Deletar documento enviado"></button>
                                    @else
                                        <img src="{{ asset('img/download3.svg') }}" width="30">
                                    @endif
                                    <span class="subtexto3 @error('arquivos.declaracao_cotista') is-invalid text-danger @enderror">
                                        Autodeclaração como candidato participante de reserva de vaga
                                        prevista pela Lei nº 12.711/2012, alterada pela Lei nº 13.409/2016,
                                        devidamente assinada e preenchida, conforme a modalidade de
                                        concorrência (preencher e assinar modelo disponível em:
                                        <a href="http://www.ufape.edu.br/sisu-2022">www.ufape.edu.br/sisu-2022</a>)
                                    </span>
                                    <div class="invalid-feedback">@error('arquivos.declaracao_cotista'){{$message}}@enderror</div>
                                </div>
                            </li>
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
                                    <x-botao-enviar-documento documento="heteroidentificacao"/>
                                    @if ($inscricao->arquivo('heteroidentificacao'))
                                        <x-botao-baixar-documento documento="heteroidentificacao"/>
                                        <button type="button" title="Deletar documento enviado" data-bs-toggle="modal" data-bs-target="#deletar-arquivo-heteroidentificacao" style="cursor: pointer;"><img width="30" src="{{asset('img/Grupo 1664.svg')}}" alt="Icone de Deletar documento enviado"></button>
                                    @else
                                        <img src="{{ asset('img/download3.svg') }}" width="30">
                                    @endif
                                    <span class="subtexto3 @error('arquivos.heteroidentificacao') is-invalid text-danger @enderror">
                                        Vídeo individual e recente para procedimento de heteroidentificação.
                                        De acordo com as especificações e o roteiro descritos no edital do
                                        processo de seleção SiSU 2022 da UFAPE, disponível em: <a href="http://www.ufape.edu.br/sisu-2022">www.ufape.edu.br/sisu-2022</a></span>
                                    <div class="invalid-feedback">@error('arquivos.heteroidentificacao'){{$message}}@enderror</div>
                                </div>
                                <div class="mt-2">
                                    <x-botao-enviar-documento documento="fotografia"/>
                                    @if ($inscricao->arquivo('fotografia'))
                                        <x-botao-baixar-documento documento="fotografia"/>
                                        <button type="button" title="Deletar documento enviado" data-bs-toggle="modal" data-bs-target="#deletar-arquivo-fotografia" style="cursor: pointer;"><img width="30" src="{{asset('img/Grupo 1664.svg')}}" alt="Icone de Deletar documento enviado"></button>
                                    @else
                                        <img src="{{ asset('img/download3.svg') }}" width="30">
                                    @endif
                                    <span class="subtexto3 @error('arquivos.fotografia') is-invalid text-danger @enderror">
                                        Fotografia individual e recente para procedimento de
                                        heteroidentificação. Conforme especificado no edital do processo de
                                        seleção SiSU 2022 da UFAPE, disponível em: <a href="http://www.ufape.edu.br/sisu-2022">www.ufape.edu.br/sisu-2022</a></span>
                                    <div class="invalid-feedback">@error('arquivos.fotografia'){{$message}}@enderror</div>
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
                                    <x-botao-enviar-documento documento="comprovante_renda"/>
                                    @if ($inscricao->arquivo('comprovante_renda'))
                                        <x-botao-baixar-documento documento="comprovante_renda"/>
                                        <button type="button" title="Deletar documento enviado" data-bs-toggle="modal" data-bs-target="#deletar-arquivo-comprovante_renda" style="cursor: pointer;"><img width="30" src="{{asset('img/Grupo 1664.svg')}}" alt="Icone de Deletar documento enviado"></button>
                                    @else
                                        <img src="{{ asset('img/download3.svg') }}" width="30">
                                    @endif
                                    <span class="subtexto3 @error('arquivos.comprovante_renda') is-invalid text-danger @enderror">
                                        Comprovante de renda, ou de que não possui renda, de cada membro
                                        do grupo familiar, seja maior ou menor de idade
                                    </span>
                                    <div class="invalid-feedback">@error('arquivos.comprovante_renda'){{$message}}@enderror</div>
                                </div>
                            </li>
                        @endif
                        @if ($documentos->contains('rani'))
                            <li class="mt-4 px-1 align-middle">
                                <div class="col-md-12">
                                    <div class="tituloEnvio">Comprovação da condição de beneficiário da reserva de
                                        vaga para candidato autodeclarado indígena</div>
                                    <div class="subtexto2 my-1">Você está concorrendo a uma vaga de cota indígena, portanto deve enviar o respectivo comprovante.</div>
                                </div>
                                <div class="mt-2">
                                    <x-botao-enviar-documento documento="rani"/>
                                    @if ($inscricao->arquivo('rani'))
                                        <x-botao-baixar-documento documento="rani"/>
                                        <button type="button" title="Deletar documento enviado" data-bs-toggle="modal" data-bs-target="#deletar-arquivo-rani" style="cursor: pointer;"><img width="30" src="{{asset('img/Grupo 1664.svg')}}" alt="Icone de Deletar documento enviado"></button>
                                    @else
                                        <img src="{{ asset('img/download3.svg') }}" width="30">
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
                                    <x-botao-enviar-documento documento="laudo_medico"/>
                                    @if ($inscricao->arquivo('laudo_medico'))
                                        <x-botao-baixar-documento documento="laudo_medico"/>
                                        <button type="button" title="Deletar documento enviado" data-bs-toggle="modal" data-bs-target="#deletar-arquivo-laudo_medico" style="cursor: pointer;"><img width="30" src="{{asset('img/Grupo 1664.svg')}}" alt="Icone de Deletar documento enviado"></button>
                                    @else
                                        <img src="{{ asset('img/download3.svg') }}" width="30">
                                    @endif
                                    <span class="subtexto3 @error('arquivos.laudo_medico') is-invalid text-danger @enderror">
                                        Laudo Médico e exames de comprovação da condição de beneficiário da reserva de vaga
                                        para pessoas com deficiência. Conforme especificado no Edital do processo de seleção
                                        SiSU 2022 da UFAPE, disponível em: <a href="http://www.ufape.edu.br/sisu-2022">www.ufape.edu.br/sisu-2022</a>
                                    </span>
                                    <div class="invalid-feedback">@error('arquivos.laudo_medico'){{$message}}@enderror</div>
                                </div>
                            </li>
                        @endif
                    </ul>
                    <div class="form-check mt-2 @error('termos.vinculo') is-invalid @enderror">
                        <input class="form-check-input" type="checkbox" value="true" id="checkVinculo" wire:model="termos.vinculo">
                        <label class="form-check-label subtexto3" for="checkVinculo">
                            DECLARO que não possuo vínculo em curso de graduação com outra instituição pública (Lei nº 12.089/2009)
                        </label>
                    </div>
                    <div class="invalid-feedback">@error('termos.vinculo'){{$message}}@enderror</div>

                    <div class="form-check mt-2 @error('termos.prouni') is-invalid @enderror">
                        <input class="form-check-input" type="checkbox" value="true" id="checkProuni" wire:model="termos.prouni">
                        <label class="form-check-label subtexto3" for="checkProuni">
                            DECLARO que não sou beneficiário do PROUNI
                        </label>
                    </div>
                    <div class="invalid-feedback">@error('termos.prouni'){{$message}}@enderror</div>

                    <div class="form-check mt-2 @error('termos.confirmacaovinculo') is-invalid @enderror">
                        <input class="form-check-input" type="checkbox" value="true" id="checkConfirmacaoVinculo" wire:model="termos.confirmacaovinculo">
                        <label class="form-check-label subtexto3" for="checkConfirmacaoVinculo">
                            DECLARO que estou ciente da obrigatoriedade de CONFIRMAÇÃO DE VÍNCULO, conforme especificações e datas descritas no Edital do processo de seleção SiSU 2022 da UFAPE, disponível em: <a href="https://www.ufape.edu.br/sisu-2022" target="_blank">www.ufape.edu.br/sisu-2022</a>
                        </label>
                    </div>
                    <div class="invalid-feedback">@error('termos.confirmacaovinculo'){{$message}}@enderror</div>
                </form>
            </div>
            <div class="d-flex flex-wrap justify-content-between mt-5">
                <div>
                    <a href="{{route('inscricaos.index')}}"
                        class="btn botao my-2 py-1">
                        <span class="px-4">Voltar</span>
                    </a>
                </div>
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
            @endforeach
        @else
            @include('inscricao.status-envio', ['pre_envio' => false])
        @endif
    @else
        @can('periodoRetificacao', $inscricao->chamada)
            @if (($inscricao->isDocumentosInvalidados() || $inscricao->isDocumentoAceitosComPendencias()) && is_null($inscricao->retificacao))
                <div clss="mb-5">
                    <form id="enviar-documentos"
                        wire:submit.prevent="submit"
                        enctype="multipart/form-data">
                        <ul class="timeline">
                            <li class="px-1 align-middle">
                                <div class="col-md-12">
                                    <div class="tituloEnvio"> Documentação básica </div>
                                    <div class="subtexto2 my-1">A documentação básica corresponde a documentação comum a todos os candidatos.</div>
                                </div>
                                @if ($documentos->contains('declaracao_veracidade'))
                                    <div class="mt-2">
                                        @if ($inscricao->isArquivoRecusadoOuReenviado('declaracao_veracidade') && $inscricao->isDocumentosInvalidados())
                                            <x-botao-enviar-documento documento="declaracao_veracidade"/>
                                        @endif
                                        @if ($inscricao->arquivo('declaracao_veracidade'))
                                            <x-botao-baixar-documento documento="declaracao_veracidade"/>
                                        @else
                                            <img src="{{ asset('img/download3.svg') }}" width="30">
                                        @endif
                                        <span class="subtexto3 @error('arquivos.declaracao_veracidade') is-invalid text-danger @enderror">
                                            Declaração de Veracidade (preencher e assinar modelo disponível em: <a href="http://www.ufape.edu.br/sisu-2022">www.ufape.edu.br/sisu-2022</a>)
                                        </span>
                                        <div class="invalid-feedback">@error('arquivos.declaracao_veracidade'){{$message}}@enderror</div>
                                    </div>
                                    <x-show-analise-documento :inscricao="$inscricao" documento="declaracao_veracidade"/>
                                @endif
                                @if ($documentos->contains('certificado_conclusao'))
                                    <div class="mt-2">
                                        @if ($inscricao->isArquivoRecusadoOuReenviado('certificado_conclusao') && $inscricao->isDocumentosInvalidados())
                                            <x-botao-enviar-documento documento="certificado_conclusao"/>
                                        @endif
                                        @if ($inscricao->arquivo('certificado_conclusao'))
                                            <x-botao-baixar-documento documento="certificado_conclusao"/>
                                        @else
                                            <img src="{{ asset('img/download3.svg') }}" width="30">
                                        @endif
                                        <span class="subtexto3 @error('arquivos.certificado_conclusao') is-invalid text-danger @enderror">
                                            Certificado de Conclusão do Ensino Médio ou Certidão de Exame Supletivo do Ensino
                                            Médio ou Certificação de Ensino Médio através do ENEM ou documento equivalente.
                                            <b>OBS.</b>: Pode estar junto com o Histórico Escolar (escanear frente e verso da Ficha 19),
                                            neste caso anexar o arquivo nos dois campos (“certificado de conclusão do ensino
                                            médio” e “histórico escolar”)
                                        </span>
                                        <div class="invalid-feedback">@error('arquivos.certificado_conclusao'){{$message}}@enderror</div>
                                    </div>
                                    <x-show-analise-documento :inscricao="$inscricao" documento="certificado_conclusao"/>
                                @endif
                                @if($documentos->contains('historico'))
                                    <div class="mt-2">
                                        @if (($inscricao->isArquivoRecusadoOuReenviado('historico') && $inscricao->isDocumentosInvalidados()) || ($inscricao->isDocumentoAceitosComPendencias() && ($inscricao->isArquivoNaoEnviado('historico') || !$inscricao->isArquivoAvaliado('historico'))))
                                            @if (in_array($declaracoes['historico'], [null, '']))
                                                <x-botao-enviar-documento documento="historico"/>
                                            @endif
                                        @endif
                                        @if ($inscricao->arquivo('historico'))
                                            <x-botao-baixar-documento documento="historico"/>
                                        @else
                                            <img src="{{ asset('img/download3.svg') }}" width="30">
                                        @endif
                                        <span class="subtexto3 @error('arquivos.historico') is-invalid text-danger @enderror">
                                            Histórico Escolar do Ensino Médio ou Equivalente. <b>OBS.</b>: Pode estar junto com o Histórico
                                            Escolar (escanear frente e verso da Ficha 19), neste caso anexar o arquivo nos dois
                                            campos (“certificado de conclusão do ensino médio” e “histórico escolar”)
                                        </span>
                                        <div class="invalid-feedback">@error('arquivos.historico'){{$message}}@enderror</div>
                                        @if(!$inscricao->arquivo('historico'))
                                            <div class="form-check mt-2">
                                                <input class="form-check-input" type="checkbox" name="declaracoes.historico" value="true" id="checkHistorico" wire:model="declaracoes.historico">
                                                <label class="form-check-label subtexto3" for="checkHistorico">
                                                    Comprometo-me a entregar junto ao DRCA/UFAPE o Histórico Escolar do Ensino Médio ou Equivalente, na
                                                    primeira semana de aula.
                                                </label>
                                            </div>
                                        @endif
                                    </div>
                                    <x-show-analise-documento :inscricao="$inscricao" documento="historico"/>
                                @endif
                                @if($documentos->contains('nascimento_ou_casamento'))
                                    <div class="mt-2">
                                        @if (($inscricao->isArquivoRecusadoOuReenviado('nascimento_ou_casamento') && $inscricao->isDocumentosInvalidados()) || ($inscricao->isDocumentoAceitosComPendencias() && ($inscricao->isArquivoNaoEnviado('nascimento_ou_casamento') || !$inscricao->isArquivoAvaliado('nascimento_ou_casamento'))))
                                            @if (in_array($declaracoes['nascimento_ou_casamento'], [null, '']))
                                                <x-botao-enviar-documento documento="nascimento_ou_casamento"/>
                                            @endif
                                        @endif
                                        @if ($inscricao->arquivo('nascimento_ou_casamento'))
                                            <x-botao-baixar-documento documento="nascimento_ou_casamento"/>
                                        @else
                                            <img src="{{ asset('img/download3.svg') }}" width="30">
                                        @endif
                                        <span class="subtexto3 @error('arquivos.nascimento_ou_casamento') is-invalid text-danger @enderror">
                                            Registro de Nascimento ou Certidão de Casamento
                                        </span>
                                        <div class="invalid-feedback">@error('arquivos.nascimento_ou_casamento'){{$message}}@enderror</div>
                                        @if(!$inscricao->arquivo('nascimento_ou_casamento'))
                                            <div class="form-check mt-2">
                                                <input class="form-check-input" type="checkbox" value="true" id="checkNascimento_casamento" wire:model="declaracoes.nascimento_ou_casamento">
                                                <label class="form-check-label subtexto3" for="checkNascimento_casamento">
                                                    Comprometo-me a entregar junto ao DRCA/UFAPE o Registro de Nascimento ou Certidão de Casamento, na
                                                    primeira semana de aula.
                                                </label>
                                            </div>
                                        @endif
                                    </div>
                                    <x-show-analise-documento :inscricao="$inscricao" documento="nascimento_ou_casamento"/>
                                @endif
                                @if($documentos->contains('rg'))
                                    <div class="mt-2">
                                        @if ($inscricao->isArquivoRecusadoOuReenviado('rg') && $inscricao->isDocumentosInvalidados())
                                            <x-botao-enviar-documento documento="rg"/>
                                        @endif
                                        @if ($inscricao->arquivo('rg'))
                                            <x-botao-baixar-documento documento="rg"/>
                                        @else
                                            <img src="{{ asset('img/download3.svg') }}" width="30">
                                        @endif
                                        <span class="subtexto3 @error('arquivos.rg') is-invalid text-danger @enderror">
                                            Carteira de Identidade válida e com foto recente (RG) - escanear frente e verso. <b>OBS.</b>:
                                            Caso tenha perdido ou sido roubado, anexar um Boletim de Ocorrência e algum outro
                                            documento com foto. A Carteira Nacional de Habilitação pode ser utilizada como
                                            documento com foto, mas não será aceita em substituição ao RG e ao CPF
                                        </span>
                                        <div class="invalid-feedback">@error('arquivos.rg'){{$message}}@enderror</div>
                                    </div>
                                    <x-show-analise-documento :inscricao="$inscricao" documento="rg"/>
                                @endif
                                @if($documentos->contains('cpf'))
                                    <div class="mt-2">
                                        @if ($inscricao->isArquivoRecusadoOuReenviado('cpf') && $inscricao->isDocumentosInvalidados())
                                            <x-botao-enviar-documento documento="cpf"/>
                                        @endif
                                        @if ($inscricao->arquivo('cpf'))
                                            <x-botao-baixar-documento documento="cpf"/>
                                        @else
                                            <img src="{{ asset('img/download3.svg') }}" width="30">
                                        @endif
                                        <span class="subtexto3 @error('arquivos.cpf') is-invalid text-danger @enderror">
                                            Cadastro de Pessoa Física (CPF). <b>OBS.</b>: Caso conste o número do CPF na identidade (RG),
                                            anexar cópia da identidade, frente e verso. Caso tenha perdido ou sido
                                            roubado, emitir Comprovante de Situação Cadastral no CPF, através do
                                        </span>
                                        <a href="https://servicos.receita.fazenda.gov.br/servicos/cpf/consultasituacao/consultapublica.asp" target="_blank" rel="noopener noreferrer">site da Receita Federal</a>
                                        <div class="invalid-feedback">@error('arquivos.cpf'){{$message}}@enderror</div>
                                    </div>
                                    <x-show-analise-documento :inscricao="$inscricao" documento="cpf"/>
                                @endif
                                @if($documentos->contains('quitacao_eleitoral'))
                                    <div class="mt-2">
                                        @if (($inscricao->isArquivoRecusadoOuReenviado('quitacao_eleitoral') && $inscricao->isDocumentosInvalidados()) || ($inscricao->isDocumentoAceitosComPendencias() && ($inscricao->isArquivoNaoEnviado('quitacao_eleitoral') || !$inscricao->isArquivoAvaliado('quitacao_eleitoral'))))
                                            @if (in_array($declaracoes['quitacao_eleitoral'], [null, '']))
                                                <x-botao-enviar-documento documento="quitacao_eleitoral"/>
                                            @endif
                                        @endif
                                        @if ($inscricao->arquivo('quitacao_eleitoral'))
                                            <x-botao-baixar-documento documento="quitacao_eleitoral"/>
                                        @else
                                            <img src="{{ asset('img/download3.svg') }}" width="30">
                                        @endif
                                        <span class="subtexto3 @error('arquivos.quitacao_eleitoral') is-invalid text-danger @enderror">
                                            Comprovante de quitação com o Serviço Eleitoral no último turno de votação ou Certidão de
                                            quitação eleitoral. <b>OBS.</b>:  Essa certidão poderá ser emitida no
                                            <a href="https://www.tse.jus.br/eleitor/certidoes/certidao-de-quitacao-eleitoral" target="_blank" rel="noopener noreferrer">
                                            site do Tribunal Superior Eleitoral.</a> Caso a certidão de quitação eleitoral não possa ser emitida em função de
                                            pagamento de multas eleitorais, poderá ser apresentada cópia (captura da
                                            tela) do relatório de quitação de débitos do eleitor (quitação de multas,
                                            disponível no
                                            <a href="https://www.tse.jus.br/" target="_blank" rel="noopener noreferrer">site do Tribunal Superior Eleitoral</a>)
                                        </span>
                                        <div class="invalid-feedback">@error('arquivos.quitacao_eleitoral'){{$message}}@enderror</div>
                                        @if(!$inscricao->arquivo('quitacao_eleitoral'))
                                            <div class="form-check mt-2">
                                                <input class="form-check-input" type="checkbox" name="declaracoes.quitacao_eleitoral " value="true" id="checkquitacao_eleitoral" wire:model="declaracoes.quitacao_eleitoral">
                                                <label class="form-check-label subtexto3" for="checkquitacao_eleitoral">
                                                    Comprometo-me a entregar junto ao DRCA/UFAPE o Comprovante de quitação com o Serviço Eleitoral, na
                                                    primeira semana de aula.
                                                </label>
                                            </div>
                                        @endif
                                    </div>
                                    <x-show-analise-documento :inscricao="$inscricao" documento="quitacao_eleitoral"/>
                                @endif
                                @if($documentos->contains('quitacao_militar'))
                                    <div class="mt-2">
                                        @if (($inscricao->isArquivoRecusadoOuReenviado('quitacao_militar') && $inscricao->isDocumentosInvalidados()) || ($inscricao->isDocumentoAceitosComPendencias() && ($inscricao->isArquivoNaoEnviado('quitacao_militar') || !$inscricao->isArquivoAvaliado('quitacao_militar'))))
                                            @if (in_array($declaracoes['quitacao_militar'], [null, '']))
                                                <x-botao-enviar-documento documento="quitacao_militar"/>
                                            @endif
                                        @endif
                                        @if ($inscricao->arquivo('quitacao_militar'))
                                            <x-botao-baixar-documento documento="quitacao_militar"/>
                                        @else
                                            <img src="{{ asset('img/download3.svg') }}" width="30">
                                        @endif
                                        <span class="subtexto3 @error('arquivos.quitacao_militar') is-invalid text-danger @enderror">
                                            Comprovante de quitação com o Serviço Militar, para candidatos
                                            do sexo masculino que tenham de 18 a 45 anos - Frente e verso. <b>OBS.</b>:  Para os militares, apresentar cópia frente e verso da carteira de identidade
                                            militar
                                        </span>
                                        <div class="invalid-feedback">@error('arquivos.quitacao_militar'){{$message}}@enderror</div>
                                        @if(!$inscricao->arquivo('quitacao_militar'))
                                            <div class="form-check mt-2">
                                                <input class="form-check-input" type="checkbox" name="declaracoes.quitacao_militar " value="true" id="checkquitacao_militar" wire:model="declaracoes.quitacao_militar">
                                                <label class="form-check-label subtexto3" for="checkquitacao_militar">
                                                    Comprometo-me a entregar junto ao DRCA/UFAPE o Comprovante de quitação com o Serviço Militar, na
                                                    primeira semana de aula.
                                                </label>
                                            </div>
                                        @endif
                                    </div>
                                    <x-show-analise-documento :inscricao="$inscricao" documento="quitacao_militar"/>
                                @endif
                                @if($documentos->contains('foto'))
                                    <div class="mt-2">
                                        @if ($inscricao->isArquivoRecusadoOuReenviado('foto') && $inscricao->isDocumentosInvalidados())
                                            <x-botao-enviar-documento documento="foto"/>
                                        @endif
                                        @if ($inscricao->arquivo('foto'))
                                            <x-botao-baixar-documento documento="foto"/>
                                        @else
                                            <img src="{{ asset('img/download3.svg') }}" width="30">
                                        @endif
                                        <span class="subtexto3 @error('arquivos.foto') is-invalid text-danger @enderror">Uma foto 3x4 atual</span>
                                        <div class="invalid-feedback">@error('arquivos.foto'){{$message}}@enderror</div>
                                    </div>
                                    <x-show-analise-documento :inscricao="$inscricao" documento="foto"/>
                                @endif
                            </li>
                            @if ($documentos->contains('declaracao_cotista'))
                                <li class="mt-4 px-1 align-middle">
                                    <div class="col-md-12">
                                        <div class="tituloEnvio"> Candidato inscrito em cota</div>
                                        <div class="subtexto2 my-1">Para concorrer a uma vaga nas cotas, também é necessário o envio destes documentos.</div>
                                    </div>
                                    <div class="mt-2">
                                        @if ($inscricao->isArquivoRecusadoOuReenviado('declaracao_cotista') && $inscricao->isDocumentosInvalidados())
                                            <x-botao-enviar-documento documento="declaracao_cotista"/>
                                        @endif
                                        @if ($inscricao->arquivo('declaracao_cotista'))
                                            <x-botao-baixar-documento documento="declaracao_cotista"/>
                                        @else
                                            <img src="{{ asset('img/download3.svg') }}" width="30">
                                        @endif
                                        <span class="subtexto3 @error('arquivos.declaracao_cotista') is-invalid text-danger @enderror">
                                            Autodeclaração como candidato participante de reserva de vaga
                                            prevista pela Lei nº 12.711/2012, alterada pela Lei nº 13.409/2016,
                                            devidamente assinada e preenchida, conforme a modalidade de
                                            concorrência (preencher e assinar modelo disponível em:
                                            <a href="http://www.ufape.edu.br/sisu-2022">www.ufape.edu.br/sisu-2022</a>)
                                        </span>
                                        <div class="invalid-feedback">@error('arquivos.declaracao_cotista'){{$message}}@enderror</div>
                                    </div>
                                </li>
                                <x-show-analise-documento :inscricao="$inscricao" documento="declaracao_cotista"/>
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
                                        @if ($inscricao->isArquivoRecusadoOuReenviado('heteroidentificacao') && $inscricao->isDocumentosInvalidados())
                                            <x-botao-enviar-documento documento="heteroidentificacao"/>
                                        @endif
                                        @if ($inscricao->arquivo('heteroidentificacao'))
                                            <x-botao-baixar-documento documento="heteroidentificacao"/>
                                        @else
                                            <img src="{{ asset('img/download3.svg') }}" width="30">
                                        @endif
                                        <span class="subtexto3 @error('arquivos.heteroidentificacao') is-invalid text-danger @enderror">
                                            Vídeo individual e recente para procedimento de heteroidentificação.
                                            De acordo com as especificações e o roteiro descritos no edital do
                                            processo de seleção SiSU 2022 da UFAPE, disponível em: <a href="http://www.ufape.edu.br/sisu-2022">www.ufape.edu.br/sisu-2022</a></span>
                                        <div class="invalid-feedback">@error('arquivos.heteroidentificacao'){{$message}}@enderror</div>
                                    </div>
                                    <div class="mt-2">
                                        @if ($inscricao->isArquivoRecusadoOuReenviado('fotografia') && $inscricao->isDocumentosInvalidados())
                                            <x-botao-enviar-documento documento="fotografia"/>
                                        @endcan
                                        @if ($inscricao->arquivo('fotografia'))
                                            <x-botao-baixar-documento documento="fotografia"/>
                                        @else
                                            <img src="{{ asset('img/download3.svg') }}" width="30">
                                        @endif
                                        <span class="subtexto3 @error('arquivos.fotografia') is-invalid text-danger @enderror">
                                            Fotografia individual e recente para procedimento de
                                            heteroidentificação. Conforme especificado no edital do processo de
                                            seleção SiSU 2022 da UFAPE, disponível em: <a href="http://www.ufape.edu.br/sisu-2022">www.ufape.edu.br/sisu-2022</a></span>
                                        <div class="invalid-feedback">@error('arquivos.fotografia'){{$message}}@enderror</div>
                                    </div>
                                </li>
                                <x-show-analise-documento :inscricao="$inscricao" documento="heteroidentificacao"/>
                            @endif
                            @if ($documentos->contains('comprovante_renda'))
                                <li class="mt-4 px-1 align-middle">
                                    <div class="col-md-12">
                                        <div class="tituloEnvio">Comprovação da renda familiar bruta mensal per capita </div>
                                        <div class="subtexto2 my-1">Você está concorrendo a uma vaga de cota de renda, portanto deve enviar o documento de renda familiar bruta mensal per capita.</div>
                                    </div>
                                    <div class="mt-2">
                                        @if ($inscricao->isArquivoRecusadoOuReenviado('comprovante_renda') && $inscricao->isDocumentosInvalidados())
                                            <x-botao-enviar-documento documento="comprovante_renda"/>
                                        @endif
                                        @if ($inscricao->arquivo('comprovante_renda'))
                                            <x-botao-baixar-documento documento="comprovante_renda"/>
                                        @else
                                            <img src="{{ asset('img/download3.svg') }}" width="30">
                                        @endif
                                        <span class="subtexto3 @error('arquivos.comprovante_renda') is-invalid text-danger @enderror">
                                            Comprovante de renda, ou de que não possui renda, de cada membro
                                            do grupo familiar, seja maior ou menor de idade
                                        </span>
                                        <div class="invalid-feedback">@error('arquivos.comprovante_renda'){{$message}}@enderror</div>
                                    </div>
                                </li>
                                <x-show-analise-documento :inscricao="$inscricao" documento="comprovante_renda"/>
                            @endif
                            @if ($documentos->contains('rani'))
                                <li class="mt-4 px-1 align-middle">
                                    <div class="col-md-12">
                                        <div class="tituloEnvio">Comprovação da condição de beneficiário da reserva de
                                            vaga para candidato autodeclarado indígena</div>
                                        <div class="subtexto2 my-1">Você está concorrendo a uma vaga de cota indígena, portanto deve enviar o respectivo comprovante.</div>
                                    </div>
                                    <div class="mt-2">
                                        @if ($inscricao->isArquivoRecusadoOuReenviado('rani') && $inscricao->isDocumentosInvalidados())
                                            <x-botao-enviar-documento documento="rani"/>
                                        @endif
                                        @if ($inscricao->arquivo('rani'))
                                            <x-botao-baixar-documento documento="rani"/>
                                        @else
                                            <img src="{{ asset('img/download3.svg') }}" width="30">
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
                                <x-show-analise-documento :inscricao="$inscricao" documento="rani"/>
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
                                        @if ($inscricao->isArquivoRecusadoOuReenviado('laudo_medico') && $inscricao->isDocumentosInvalidados())
                                            <x-botao-enviar-documento documento="laudo_medico"/>
                                        @endif
                                        @if ($inscricao->arquivo('laudo_medico'))
                                            <x-botao-baixar-documento documento="laudo_medico"/>
                                        @else
                                            <img src="{{ asset('img/download3.svg') }}" width="30">
                                        @endif
                                        <span class="subtexto3 @error('arquivos.laudo_medico') is-invalid text-danger @enderror">
                                            Laudo Médico e exames de comprovação da condição de beneficiário da reserva de vaga
                                            para pessoas com deficiência. Conforme especificado no Edital do processo de seleção
                                            SiSU 2022 da UFAPE, disponível em: <a href="http://www.ufape.edu.br/sisu-2022">www.ufape.edu.br/sisu-2022</a>
                                        </span>
                                        <div class="invalid-feedback">@error('arquivos.laudo_medico'){{$message}}@enderror</div>
                                    </div>
                                </li>
                                <x-show-analise-documento :inscricao="$inscricao" documento="laudo_medico"/>
                            @endif
                        </ul>
                        <div class="form-check mt-2 @error('termos.vinculo') is-invalid @enderror">
                            <input class="form-check-input" type="checkbox" value="true" id="checkVinculo" wire:model="termos.vinculo">
                            <label class="form-check-label subtexto3" for="checkVinculo">
                                DECLARO que não possuo vínculo em curso de graduação com outra instituição pública (Lei nº 12.089/2009)
                            </label>
                        </div>
                        <div class="invalid-feedback">@error('termos.vinculo'){{$message}}@enderror</div>

                        <div class="form-check mt-2 @error('termos.prouni') is-invalid @enderror">
                            <input class="form-check-input" type="checkbox" value="true" id="checkProuni" wire:model="termos.prouni">
                            <label class="form-check-label subtexto3" for="checkProuni">
                                DECLARO que não sou beneficiário do PROUNI
                            </label>
                        </div>
                        <div class="invalid-feedback">@error('termos.prouni'){{$message}}@enderror</div>

                        <div class="form-check mt-2 @error('termos.confirmacaovinculo') is-invalid @enderror">
                            <input class="form-check-input" type="checkbox" value="true" id="checkConfirmacaoVinculo" wire:model="termos.confirmacaovinculo">
                            <label class="form-check-label subtexto3" for="checkConfirmacaoVinculo">
                                DECLARO que estou ciente da obrigatoriedade de CONFIRMAÇÃO DE VÍNCULO, conforme especificações e datas descritas no Edital do processo de seleção SiSU 2022 da UFAPE, disponível em: <a href="https://www.ufape.edu.br/sisu-2022" target="_blank">www.ufape.edu.br/sisu-2022</a>
                            </label>
                        </div>
                        <div class="invalid-feedback">@error('termos.confirmacaovinculo'){{$message}}@enderror</div>
                    </form>
                </div>
                <div class="d-flex flex-wrap justify-content-between mt-5">
                    <div>
                        <a href="{{route('inscricaos.index')}}"
                            class="btn botao my-2 py-1">
                            <span class="px-4">Voltar</span>
                        </a>
                    </div>
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
            @else
                @include('inscricao.status-envio', ['pre_envio' => false])
            @endif
        @else
            @if ($inscricao->isDocumentosRequeridos())
                @include('inscricao.status-envio', ['pre_envio' => true])
            @else
                @include('inscricao.status-envio', ['pre_envio' => false])
            @endif
        @endcan
    @endcan
</div>
