<div>
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
                            @if ($inscricao->arquivo('declaracao_veracidade'))
                                <a wire:click="baixar('declaracao_veracidade')"
                                    title="Baixar documento"
                                    target="_blank"
                                    style="cursor:pointer;">
                                    <img src="{{asset('img/download2.svg')}}"
                                        alt="arquivo atual"
                                        width="30"
                                        class="img-flex"></a>
                            @else
                                <img src="{{ asset('img/download3.svg') }}"
                                    width="30">
                            @endif
                            <span class="subtexto3">
                                Declaração de Veracidade (preencher e assinar modelo disponível em: <a href="http://www.ufape.edu.br/documentossisu2023">www.ufape.edu.br/documentossisu2023</a>)
                            </span>
                        </div>
                        <x-show-analise-documento :inscricao="$inscricao" documento="declaracao_veracidade"/>
                    @endif
                    @if ($documentos->contains('certificado_conclusao'))
                        <div class="mt-2">
                            @if ($inscricao->arquivo('certificado_conclusao'))
                                <a wire:click="baixar('certificado_conclusao')"
                                    title="Baixar documento"
                                    target="_blank"
                                    style="cursor:pointer;">
                                    <img src="{{asset('img/download2.svg')}}"
                                        alt="arquivo atual"
                                        width="30"
                                        class="img-flex"></a>
                            @else
                                <img src="{{ asset('img/download3.svg') }}"
                                    width="30">
                            @endif
                            <span class="subtexto3">
                                Certificado de Conclusão do Ensino Médio ou Certidão de Exame Supletivo do Ensino
                                Médio ou Certificação de Ensino Médio através do ENEM ou documento equivalente.
                                <b>OBS.</b>: Pode estar junto com o Histórico Escolar (escanear frente e verso da Ficha 19),
                                neste caso anexar o arquivo nos dois campos (“certificado de conclusão do ensino
                                médio” e “histórico escolar”)
                            </span>
                        </div>
                        <x-show-analise-documento :inscricao="$inscricao" documento="certificado_conclusao"/>
                    @endif
                    @if($documentos->contains('historico'))
                        <div class="mt-2">
                            @if ($inscricao->arquivo('historico'))
                                <a wire:click="baixar('historico')"
                                    title="Baixar documento"
                                    target="_blank"
                                    style="cursor:pointer;">
                                    <img src="{{asset('img/download2.svg')}}"
                                        alt="arquivo atual"
                                        width="30"
                                        class="img-flex"></a>
                            @else
                                <img src="{{ asset('img/download3.svg') }}"
                                    width="30">
                            @endif
                            <span class="subtexto3">
                                Histórico Escolar do Ensino Médio ou Equivalente. <b>OBS.</b>: Pode estar junto com o Histórico
                                Escolar (escanear frente e verso da Ficha 19), neste caso anexar o arquivo nos dois
                                campos (“certificado de conclusão do ensino médio” e “histórico escolar”)
                            </span>
                            @if(!$inscricao->arquivo('historico') && !$pre_envio)
                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox" checked disabled id="checkHistorico">
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
                            @if ($inscricao->arquivo('nascimento_ou_casamento'))
                                <a wire:click="baixar('nascimento_ou_casamento')"
                                    title="Baixar documento"
                                    target="_blank"
                                    style="cursor:pointer;">
                                    <img src="{{asset('img/download2.svg')}}"
                                        alt="arquivo atual"
                                        width="30"
                                        class="img-flex"></a>
                            @else
                                <img src="{{ asset('img/download3.svg') }}"
                                    width="30">
                            @endif
                            <span class="subtexto3">
                                Registro de Nascimento ou Certidão de Casamento
                            </span>
                            @if(!$inscricao->arquivo('nascimento_ou_casamento') && !$pre_envio)
                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox" checked disabled id="checkNascimento_casamento">
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
                            @if ($inscricao->arquivo('rg'))
                                <a wire:click="baixar('rg')"
                                    title="Baixar documento"
                                    target="_blank"
                                    style="cursor:pointer;">
                                    <img src="{{asset('img/download2.svg')}}"
                                        alt="arquivo atual"
                                        width="30"
                                        class="img-flex"></a>
                            @else
                                <img src="{{ asset('img/download3.svg') }}"
                                    width="30">
                            @endif
                            <span class="subtexto3">
                                Carteira de Identidade válida e com foto recente (RG) - escanear frente e verso. <b>OBS.</b>:
                                Caso tenha perdido ou sido roubado, anexar um Boletim de Ocorrência e algum outro
                                documento com foto. A Carteira Nacional de Habilitação pode ser utilizada como
                                documento com foto, mas não será aceita em substituição ao RG e ao CPF
                            </span>
                        </div>
                        <x-show-analise-documento :inscricao="$inscricao" documento="rg"/>
                    @endif
                    @if($documentos->contains('cpf'))
                        <div class="mt-2">
                            @if ($inscricao->arquivo('cpf'))
                                <a wire:click="baixar('cpf')"
                                    title="Baixar documento"
                                    target="_blank"
                                    style="cursor:pointer;">
                                    <img src="{{asset('img/download2.svg')}}"
                                        alt="arquivo atual"
                                        width="30"
                                        class="img-flex"></a>
                            @else
                                <img src="{{ asset('img/download3.svg') }}"
                                    width="30">
                            @endif
                            <span class="subtexto3">
                                Cadastro de Pessoa Física (CPF). <b>OBS.</b>: Caso conste o número do CPF na identidade (RG),
                                anexar cópia da identidade, frente e verso. Caso tenha perdido ou sido
                                roubado, emitir Comprovante de Situação Cadastral no CPF, através do
                            </span>
                            <a href="https://servicos.receita.fazenda.gov.br/servicos/cpf/consultasituacao/consultapublica.asp" target="_blank" rel="noopener noreferrer">site da Receita Federal</a>
                        </div>
                        <x-show-analise-documento :inscricao="$inscricao" documento="cpf"/>
                    @endif
                    @if($documentos->contains('quitacao_eleitoral'))
                        <div class="mt-2">
                            @if ($inscricao->arquivo('quitacao_eleitoral'))
                                <a wire:click="baixar('quitacao_eleitoral')"
                                    title="Baixar documento"
                                    target="_blank"
                                    style="cursor:pointer;">
                                    <img src="{{asset('img/download2.svg')}}"
                                        alt="arquivo atual"
                                        width="30"
                                        class="img-flex"></a>
                            @else
                                <img src="{{ asset('img/download3.svg') }}"
                                    width="30">
                            @endif
                            <span class="subtexto3">
                                Comprovante de quitação com a Justiça Eleitoral ou Certidão de
                                quitação eleitoral. <b>OBS.</b>:  Essa certidão poderá ser emitida no
                                <a href="https://www.tse.jus.br/servicos-eleitorais/titulo-eleitoral/quitacao-de-multas#consulta-de-d-bitos-do-eleitor" target="_blank" rel="noopener noreferrer">
                                site do Tribunal Superior Eleitoral.</a> Caso a certidão de quitação eleitoral não possa ser emitida em função de
                                pagamento de multas eleitorais, poderá ser apresentada cópia (captura da
                                tela) do relatório de quitação de débitos do eleitor (quitação de multas,
                                disponível no
                                <a href="https://www.tse.jus.br/" target="_blank" rel="noopener noreferrer">site do Tribunal Superior Eleitoral</a>)
                            </span>
                            @if(!$inscricao->arquivo('quitacao_eleitoral') && !$pre_envio)
                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox" checked disabled id="checkquitacao_eleitoral">
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
                            @if ($inscricao->arquivo('quitacao_militar'))
                                <a wire:click="baixar('quitacao_militar')"
                                    title="Baixar documento"
                                    target="_blank"
                                    style="cursor:pointer;">
                                    <img src="{{asset('img/download2.svg')}}"
                                        alt="arquivo atual"
                                        width="30"
                                        class="img-flex"></a>
                            @else
                                <img src="{{ asset('img/download3.svg') }}"
                                    width="30">
                            @endif
                            <span class="subtexto3">
                                Comprovante de quitação com o Serviço Militar, para candidatos
                                do sexo masculino que tenham de 18 a 45 anos - Frente e verso. <b>OBS.</b>:  Para os militares, apresentar cópia frente e verso da carteira de identidade
                                militar
                            </span>
                            @if(!$inscricao->arquivo('quitacao_militar') && !$pre_envio)
                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox" checked disabled id="checkquitacao_militar">
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
                            @if ($inscricao->arquivo('foto'))
                                <a wire:click="baixar('foto')"
                                    title="Baixar documento"
                                    target="_blank"
                                    style="cursor:pointer;">
                                    <img src="{{asset('img/download2.svg')}}"
                                        alt="arquivo atual"
                                        width="30"
                                        class="img-flex"></a>
                            @else
                                <img src="{{ asset('img/download3.svg') }}"
                                    width="30">
                            @endif
                            <span class="subtexto3">Uma foto 3x4 atual</span>
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
                            @if ($inscricao->arquivo('declaracao_cotista'))
                                <a wire:click="baixar('declaracao_cotista')"
                                    title="Baixar documento"
                                    target="_blank"
                                    style="cursor:pointer;">
                                    <img src="{{asset('img/download2.svg')}}"
                                        alt="arquivo atual"
                                        width="30"
                                        class="img-flex"></a>
                            @else
                                <img src="{{ asset('img/download3.svg') }}"
                                    width="30">
                            @endif
                            <span class="subtexto3">
                                Autodeclaração como candidato participante de reserva de vaga
                                prevista pela Lei nº 12.711/2012, alterada pela Lei nº 13.409/2016,
                                devidamente assinada e preenchida, conforme a modalidade de
                                concorrência (preencher e assinar modelo disponível em:
                                <a href="http://www.ufape.edu.br/documentossisu2023">www.ufape.edu.br/documentossisu2023</a>)
                            </span>
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
                            @if ($inscricao->arquivo('heteroidentificacao'))
                                <a wire:click="baixar('heteroidentificacao')"
                                    title="Baixar documento"
                                    target="_blank"
                                    style="cursor:pointer;">
                                    <img src="{{asset('img/download2.svg')}}"
                                        alt="arquivo atual"
                                        width="30"
                                        class="img-flex"></a>
                            @else
                                <img src="{{ asset('img/download3.svg') }}"
                                    width="30">
                            @endif
                            <span class="subtexto3">
                                Vídeo individual e recente para procedimento de heteroidentificação.
                                De acordo com as especificações e o roteiro descritos no edital do
                                processo de seleção SiSU 2023 da UFAPE, disponível em: <a href="http://www.ufape.edu.br/documentossisu2023">www.ufape.edu.br/documentossisu2023</a></span>
                        </div>
                        <x-show-analise-documento :inscricao="$inscricao" documento="heteroidentificacao"/>
                        <div class="mt-2">
                            @if ($inscricao->arquivo('fotografia'))
                                <a wire:click="baixar('fotografia')"
                                    title="Baixar documento"
                                    target="_blank"
                                    style="cursor:pointer;">
                                    <img src="{{asset('img/download2.svg')}}"
                                        alt="arquivo atual"
                                        width="30"
                                        class="img-flex"></a>
                            @else
                                <img src="{{ asset('img/download3.svg') }}"
                                    width="30">
                            @endif
                            <span class="subtexto3">
                                Fotografia individual e recente para procedimento de
                                heteroidentificação. Conforme especificado no edital do processo de
                                seleção SiSU 2023 da UFAPE, disponível em: <a href="http://www.ufape.edu.br/documentossisu2023">www.ufape.edu.br/documentossisu2023</a></span>
                        </div>
                        <x-show-analise-documento :inscricao="$inscricao" documento="fotografia"/>
                    </li>
                @endif
                @if ($documentos->contains('comprovante_renda'))
                    <li class="mt-4 px-1 align-middle">
                        <div class="col-md-12">
                            <div class="tituloEnvio">Comprovação da renda familiar bruta mensal per capita </div>
                            <div class="subtexto2 my-1">Você está concorrendo a uma vaga de cota de renda, portanto deve enviar o documento de renda familiar bruta mensal per capita.</div>
                        </div>
                        <div class="mt-2">
                            @if ($inscricao->arquivo('comprovante_renda'))
                                <a wire:click="baixar('comprovante_renda')"
                                    title="Baixar documento"
                                    target="_blank"
                                    style="cursor:pointer;">
                                    <img src="{{asset('img/download2.svg')}}"
                                        alt="arquivo atual"
                                        width="30"
                                        class="img-flex"></a>
                            @else
                                <img src="{{ asset('img/download3.svg') }}"
                                    width="30">
                            @endif
                            <span class="subtexto3">
                                Comprovante de renda, ou de que não possui renda, de cada membro
                                do grupo familiar, seja maior ou menor de idade
                            </span>
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
                            @if ($inscricao->arquivo('rani'))
                                <a wire:click="baixar('rani')"
                                    title="Baixar documento"
                                    target="_blank"
                                    style="cursor:pointer;">
                                    <img src="{{asset('img/download2.svg')}}"
                                        alt="arquivo atual"
                                        width="30"
                                        class="img-flex"></a>
                            @else
                                <img src="{{ asset('img/download3.svg') }}"
                                    width="30">
                            @endif
                            <span class="subtexto3">
                                Registro Administrativo de Nascimento de Indígena (RANI)
                                ou declaração de vínculo com comunidade indígena brasileira à qual
                                pertença emitida por liderança indígena reconhecida ou por ancião
                                indígena reconhecido ou por personalidade indígena de reputação
                                pública reconhecida ou outro documento emitido por órgãos
                                públicos que contenham informações pertinentes à sua condição de
                                indígena;
                            </span>
                        </div>
                    </li>
                    <x-show-analise-documento :inscricao="$inscricao" documento="rani"/>
                @endif
                @if ($documentos->contains('declaracao_quilombola'))
                    <li class="mt-4 px-1 align-middle">
                        <div class="col-md-12">
                            <div class="tituloEnvio">Comprovação da condição de beneficiário da reserva de
                                vaga para candidato autodeclarado quilombola</div>
                            <div class="subtexto2 my-1">Você está concorrendo a uma vaga de cota quilombola, portanto deve enviar o respectivo comprovante.</div>
                        </div>
                        <div class="mt-2">
                            @if ($inscricao->arquivo('declaracao_quilombola'))
                                <a wire:click="baixar('declaracao_quilombola')"
                                    title="Baixar documento"
                                    target="_blank"
                                    style="cursor:pointer;">
                                    <img src="{{asset('img/download2.svg')}}"
                                        alt="arquivo atual"
                                        width="30"
                                        class="img-flex"></a>
                            @else
                                <img src="{{ asset('img/download3.svg') }}"
                                    width="30">
                            @endif
                            <span class="subtexto3">
                                Declaração da Fundação Cultural Palmares ou Declaração de pertencimento Ético e de Vínculo com Comunidade Quilombola assinada por 03 (três) lideranças da Comunidade.
                            </span>
                        </div>
                    </li>
                    <x-show-analise-documento :inscricao="$inscricao" documento="declaracao_quilombola"/>
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
                            @if ($inscricao->arquivo('laudo_medico'))
                                <a wire:click="baixar('laudo_medico')"
                                    title="Baixar documento"
                                    target="_blank"
                                    style="cursor:pointer;">
                                    <img src="{{asset('img/download2.svg')}}"
                                        alt="arquivo atual"
                                        width="30"
                                        class="img-flex"></a>
                            @else
                                <img src="{{ asset('img/download3.svg') }}"
                                    width="30">
                            @endif
                            <span class="subtexto3">
                                Laudo Médico e exames de comprovação da condição de beneficiário da reserva de vaga
                                para pessoas com deficiência. Conforme especificado no Edital do processo de seleção
                                SiSU 2023 da UFAPE, disponível em: <a href="http://www.ufape.edu.br/documentossisu2023">www.ufape.edu.br/documentossisu2023</a>
                            </span>
                        </div>
                    </li>
                    <x-show-analise-documento :inscricao="$inscricao" documento="laudo_medico"/>
                @endif
            </ul>
            @empty($pre_envio)
                <div class="form-check mt-2">
                    <input class="form-check-input" type="checkbox" checked disabled id="checkVinculo">
                    <label class="form-check-label subtexto3" for="checkVinculo">
                        DECLARO que não possuo vínculo em curso de graduação com outra instituição pública (Lei nº 12.089/2009)
                    </label>
                </div>

                <div class="form-check mt-2">
                    <input class="form-check-input" type="checkbox" checked disabled id="checkProuni">
                    <label class="form-check-label subtexto3" for="checkProuni">
                        DECLARO que não sou beneficiário do PROUNI
                    </label>
                </div>

                <div class="form-check mt-2">
                    <input class="form-check-input" type="checkbox" checked disabled id="checkConfirmacaoVinculo">
                    <label class="form-check-label subtexto3" for="checkConfirmacaoVinculo">
                        DECLARO que estou ciente da obrigatoriedade de CONFIRMAÇÃO DE VÍNCULO, conforme especificações e datas descritas no Edital do processo de seleção SiSU 2023 da UFAPE, disponível em: <a href="http://www.ufape.edu.br/documentossisu2023" target="_blank">www.ufape.edu.br/documentossisu2023</a>
                    </label>
                </div>
            @endempty
        </form>
    </div>
    <div class="d-flex flex-wrap justify-content-between mt-5">
        <div>
            <a href="{{route('inscricaos.index')}}"
                class="btn botao my-2 py-1">
                <span class="px-4">Voltar</span>
            </a>
        </div>
    </div>
</div>
