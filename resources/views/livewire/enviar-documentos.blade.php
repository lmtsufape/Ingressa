<div>
    <div clss="mb-5">
        <form id="enviar-documentos"
            wire:submit.prevent="submit"
            enctype="multipart/form-data">
            <ul class="timeline">
                <li class="px-1 align-middle">
                    <div class="col-md-12">
                        <div class="tituloEnvio"> Documentação básica </div>
                        <div class="subtexto2 my-1"> Lorem Ipsum is simply dummy text of the printing and
                            typesetting industry. Lorem Ipsum has been the industry's standard dummy text
                            ever
                            since the 1500s, when an unknown printer took a galley of type and scrambled it
                            to
                            make a type specimen book. </div>
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
                                @if ($inscricao->isDocumentosRequeridos())
                                    <img src="{{ asset('img/upload2.svg') }}"
                                        width="30">
                                @endif
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
                            @else
                                <img src="{{ asset('img/download3.svg') }}"
                                    width="30">
                            @endif
                            <span class="subtexto3 @error('arquivos.declaracao_veracidade') is-invalid text-danger @enderror">
                                Declaração de Veracidade;
                            </span>
                            <div class="invalid-feedback">@error('arquivos.declaracao_veracidade'){{$message}}@enderror</div>
                        </div>
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
                                    @if ($inscricao->isDocumentosRequeridos())
                                        <img src="{{ asset('img/upload2.svg') }}"
                                            width="30">
                                    @endif
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
                            @else
                                <img src="{{ asset('img/download3.svg') }}"
                                    width="30">
                            @endif
                            <span class="subtexto3 @error('arquivos.certificado_conclusao') is-invalid text-danger @enderror">
                                Certificado de Conclusão do Ensino Médio ou Certidão de Exame Supletivo do Ensino Médio ou
                                Certificação de Ensino Médio através do ENEM ou documento equivalente (pode estar junto com
                                o Histórico Escolar (escanear frente e verso da Ficha 19), neste caso anexar o arquivo nos
                                dois campos);
                            </span>
                            <div class="invalid-feedback">@error('arquivos.certificado_conclusao'){{$message}}@enderror</div>
                        </div>
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
                                @if ($inscricao->isDocumentosRequeridos())
                                    <img src="{{ asset('img/upload2.svg') }}"
                                        width="30">
                                @endif
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
                            @else
                                <img src="{{ asset('img/download3.svg') }}"
                                    width="30">
                            @endif
                            <span class="subtexto3 @error('arquivos.historico') is-invalid text-danger @enderror">
                                Histórico Escolar do Ensino Médio ou Equivalente (pode estar junto com
                                o Certificado de Conclusão do Ensino Médio (escanear frente e verso da Ficha 19), neste caso anexar
                                o arquivo nos dois campos);
                            </span>
                            <div class="invalid-feedback">@error('arquivos.historico'){{$message}}@enderror</div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="declaracoes.historico " value="true" id="checkHistorico" wire:model="declaracoes.historico">
                                <label class="form-check-label subtexto3" for="checkHistorico">
                                    Comprometo-me a entregar junto ao DRCA/UFAPE o Histórico Escolar do Ensino Médio ou Equivalente, na
                                    primeira semana de aula.
                                </label>
                            </div>
                        </div>
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
                                @if ($inscricao->isDocumentosRequeridos())
                                    <img src="{{ asset('img/upload2.svg') }}"
                                        width="30">
                                @endif
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
                            @else
                                <img src="{{ asset('img/download3.svg') }}"
                                    width="30">
                            @endif
                            <span class="subtexto3 @error('arquivos.nascimento_ou_casamento') is-invalid text-danger @enderror">
                                Regristro de Nascimento ou Certidão de Casamento;
                            </span>
                            <div class="invalid-feedback">@error('arquivos.nascimento_ou_casamento'){{$message}}@enderror</div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="true" id="checkNascimento_casamento" wire:model="declaracoes.nascimento_ou_casamento">
                                <label class="form-check-label subtexto3" for="checkNascimento_casamento">
                                    Comprometo-me a entregar junto ao DRCA/UFAPE o Registro de Nascimento ou Certidão de Casamento, na
                                    primeira semana de aula.
                                </label>
                            </div>
                        </div>
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
                                @if ($inscricao->isDocumentosRequeridos())
                                    <img src="{{ asset('img/upload2.svg') }}"
                                        width="30">
                                @endif
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
                                @if ($inscricao->isDocumentosRequeridos())
                                    <img src="{{ asset('img/upload2.svg') }}"
                                        width="30">
                                @endif
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
                                @if ($inscricao->isDocumentosRequeridos())
                                    <img src="{{ asset('img/upload2.svg') }}"
                                        width="30">
                                @endif
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
                                <a href="https://www.tse.jus.br/" target="_blank" rel="noopener noreferrer">site do Tribunal Superior Eleitoral</a>;
                            </span>
                            <div class="invalid-feedback">@error('arquivos.quitacao_eleitoral'){{$message}}@enderror</div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="declaracoes.quitacao_eleitoral " value="true" id="checkquitacao_eleitoral" wire:model="declaracoes.quitacao_eleitoral">
                                <label class="form-check-label subtexto3" for="checkquitacao_eleitoral">
                                    Comprometo-me a entregar junto ao DRCA/UFAPE o Comprovante de quitação com o Serviço Eleitoral, na
                                    primeira semana de aula.
                                </label>
                            </div>
                        </div>
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
                                @if ($inscricao->isDocumentosRequeridos())
                                    <img src="{{ asset('img/upload2.svg') }}"
                                        width="30">
                                @endif
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
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="declaracoes.quitacao_militar " value="true" id="checkquitacao_militar" wire:model="declaracoes.quitacao_militar">
                                <label class="form-check-label subtexto3" for="checkquitacao_militar">
                                    Comprometo-me a entregar junto ao DRCA/UFAPE o Comprovante de quitação com o Serviço Militar, na
                                    primeira semana de aula.
                                </label>
                            </div>
                        </div>
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
                                @if ($inscricao->isDocumentosRequeridos())
                                    <img src="{{ asset('img/upload2.svg') }}"
                                        width="30">
                                @endif
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
                            @else
                                <img src="{{ asset('img/download3.svg') }}"
                                    width="30">
                            @endif
                            <span class="subtexto3 @error('arquivos.foto') is-invalid text-danger @enderror">Uma foto 3x4 atual;</span>
                            <div class="invalid-feedback">@error('arquivos.foto'){{$message}}@enderror</div>
                        </div>
                    @endif
                </li>
                @if ($documentos->contains('declaracao_cotista'))
                    <li class="mt-4 px-1 align-middle">
                        <div class="col-md-12">
                            <div class="tituloEnvio"> Candidato inscrito em cota</div>
                            <div class="subtexto2 my-1">
                                Lorem Ipsum is simply dummy text of the printing and
                                typesetting industry. Lorem Ipsum has been the industry's standard dummy
                                text ever
                                since the 1500s, when an unknown printer took a galley of type and scrambled
                                it to
                                make a type specimen book.
                            </div>
                        </div>
                        <div class="mt-2">
                            <label for="cotista"
                                title="Enviar documento"
                                style="cursor:pointer;">
                                <input wire:model="arquivos.declaracao_cotista"
                                    type="file"
                                    class="d-none"
                                    id="cotista">
                                @if ($inscricao->isDocumentosRequeridos())
                                    <img src="{{ asset('img/upload2.svg') }}"
                                        width="30">
                                @endif
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
                @endif
                @if ($documentos->contains('heteroidentificacao'))
                    <li class="mt-4 px-1 align-middle">
                        <div class="col-md-12">
                            <div class="tituloEnvio"> Comprovação da condição de beneficiário da reserva de
                                vaga para candidato autodeclarado negro (preto ou
                                pardo) </div>
                            <div class="subtexto2 my-1">
                                Lorem Ipsum is simply dummy text of the printing and
                                typesetting industry. Lorem Ipsum has been the industry's standard dummy
                                text ever
                                since the 1500s, when an unknown printer took a galley of type and scrambled
                                it to
                                make a type specimen book.
                            </div>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input @error('declaracoes.preto_pardo') is-invalid @enderror" type="checkbox" value="true" id="checkpreto_pardo" wire:model="declaracoes.preto_pardo">
                            <label class="form-check-label subtexto3" for="checkpreto_pardo">
                                Declaro que me candidatei às vagas destinadas aos candidatos autodeclarados pretos ou pardos.
                            </label>
                        </div>
                        <div class="invalid-feedback" style="display: block">@error('declaracoes.preto_pardo'){{$message}}@enderror</div>

                        <div class="mt-2">
                            <label for="docHeteroidentificacao"
                                title="Enviar documento"
                                style="cursor:pointer;">
                                <input wire:model="arquivos.heteroidentificacao"
                                    type="file"
                                    class="d-none"
                                    id="docHeteroidentificacao">
                                @if ($inscricao->isDocumentosRequeridos())
                                    <img src="{{ asset('img/upload2.svg') }}"
                                        width="30">
                                @endif
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
                            @else
                                <img src="{{ asset('img/download3.svg') }}"
                                    width="30">
                            @endif
                            <span class="subtexto3 @error('arquivos.heteroidentificacao') is-invalid text-danger @enderror">
                                Vídeo individual e recente para procedimento de heteroidentificação.
                                De acordo com as especificações e o roteiro descritos no edital do
                                processo de seleção SISU 2022 da UFAPE;</span>
                            <div class="invalid-feedback">@error('arquivos.heteroidentificacao'){{$message}}@enderror</div>
                        </div>
                        <div class="mt-2">
                            <label for="docFotografia"
                                title="Enviar documento"
                                style="cursor:pointer;">
                                <input wire:model="arquivos.fotografia"
                                    type="file"
                                    class="d-none"
                                    id="docFotografia">
                                @if ($inscricao->isDocumentosRequeridos())
                                    <img src="{{ asset('img/upload2.svg') }}"
                                        width="30">
                                @endif
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
                            @else
                                <img src="{{ asset('img/download3.svg') }}"
                                    width="30">
                            @endif
                            <span class="subtexto3 @error('arquivos.fotografia') is-invalid text-danger @enderror">
                                Fotografia individual e recente para procedimento de
                                heteroidentificação. Conforme especificado no edital do processo de
                                seleção SISU 2022 da UFAPE;</span>
                            <div class="invalid-feedback">@error('arquivos.fotografia'){{$message}}@enderror</div>
                        </div>
                    </li>
                @endif
                @if ($documentos->contains('comprovante_renda'))
                    <li class="mt-4 px-1 align-middle">
                        <div class="col-md-12">
                            <div class="tituloEnvio">Comprovação da renda familiar bruta mensal per capita </div>
                            <div class="subtexto2 my-1"> Lorem Ipsum is simply dummy text of the printing
                                and
                                typesetting industry. Lorem Ipsum has been the industry's standard dummy
                                text ever
                                since the 1500s, when an unknown printer took a galley of type and scrambled
                                it to
                                make a type specimen book. </div>
                        </div>
                        <div class="mt-2">
                            <label for="cotaRenda"
                                title="Enviar documento"
                                style="cursor:pointer;">
                                <input wire:model="arquivos.comprovante_renda"
                                    type="file"
                                    class="d-none"
                                    id="cotaRenda">
                                @if ($inscricao->isDocumentosRequeridos())
                                    <img src="{{ asset('img/upload2.svg') }}"
                                        width="30">
                                @endif
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
                @endif
                @if ($documentos->contains('rani'))
                    <li class="mt-4 px-1 align-middle">
                        <div class="col-md-12">
                            <div class="tituloEnvio">Comprovação da condição de beneficiário da reserva de
                                vaga para candidato autodeclarado indígena</div>
                            <div class="subtexto2 my-1"> Lorem Ipsum is simply dummy text of the printing
                                and
                                typesetting industry. Lorem Ipsum has been the industry's standard dummy
                                text ever
                                since the 1500s, when an unknown printer took a galley of type and scrambled
                                it to
                                make a type specimen book. </div>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input @error('declaracoes.indigena') is-invalid @enderror" type="checkbox" value="true" id="checkindigena" wire:model="declaracoes.indigena">
                            <label class="form-check-label subtexto3" for="checkindigena">
                                Declaro que me candidatei às vagas destinadas aos candidatos autodeclarados indígenas.
                            </label>
                        </div>
                        <div class="invalid-feedback" style="display: block">@error('declaracoes.indigena'){{$message}}@enderror</div>
                        <div class="mt-2">
                            <label for="rani"
                                title="Enviar documento"
                                style="cursor:pointer;">
                                <input wire:model="arquivos.rani"
                                    type="file"
                                    class="d-none"
                                    id="rani">
                                @if ($inscricao->isDocumentosRequeridos())
                                    <img src="{{ asset('img/upload2.svg') }}"
                                        width="30">
                                @endif
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
                @endif
                @if ($documentos->contains('laudo_medico'))
                    <li class="mt-4 px-1 align-middle">
                        <div class="col-md-12">
                            <div class="tituloEnvio">Comprovação da condição de beneficiário da reserva de
                                vaga para pessoas com deficiência
                            </div>
                            <div class="subtexto2 my-1"> Lorem Ipsum is simply dummy text of the printing
                                and
                                typesetting industry. Lorem Ipsum has been the industry's standard dummy
                                text ever
                                since the 1500s, when an unknown printer took a galley of type and scrambled
                                it to
                                make a type specimen book. </div>
                        </div>
                        <div class="mt-2">
                            <label for="cotaPCD"
                                title="Enviar documento"
                                style="cursor:pointer;">
                                <input wire:model="arquivos.laudo_medico"
                                    type="file"
                                    class="d-none"
                                    id="cotaPCD">
                                @if ($inscricao->isDocumentosRequeridos())
                                    <img src="{{ asset('img/upload2.svg') }}"
                                        width="30">
                                @endif
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
                @endif
            </ul>
        </form>
    </div>
    <div class="row justify-content-between mt-5">
        <div class="col-md-3">
            <a href="{{route('inscricaos.index')}}"
                class="btn botao my-2 py-1">
                <span class="px-4">Voltar</span>
            </a>
        </div>
        @if ($inscricao->isDocumentosRequeridos())
            <div class="col-md-3">
                <button type="submit"
                    form="enviar-documentos"
                    class="btn botaoVerde my-2 py-1">
                    <span class="px-4">Enviar</span>
                </button>
            </div>
        @endif
    </div>
</div>
