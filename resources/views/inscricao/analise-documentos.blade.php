<x-app-layout>
    <div class="fundo2 px-5">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="col-md-12" style="text-align: right">
                        <a class="btn botao my-2 py-1" href="{{route('chamadas.candidatos.curso', ['sisu_id' => $inscricao->chamada->sisu->id, 'chamada_id' => $inscricao->chamada->id, 'curso_id' => $inscricao->curso->id])}}"> <span class="px-4">Voltar</span></a>
                    </div>
                </div>
            </div>
            @if(session('success'))
                <div class="row mt-3" id="mensagemSucesso">
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
            @error('error')
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{$message}}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @enderror
            @can('isAdmin', \App\Models\User::class)
                <div class="row">
                    <div class="col-md-12">
                        @if($inscricao->cd_efetivado == \App\Models\Inscricao::STATUS_VALIDACAO_CANDIDATO['cadastro_invalidado_confirmacao'])
                            <div class="alert alert-warning fade show" role="alert">
                                <strong>Atenção!</strong> O Candidato teve o cadastro invalidado! Confirme se o candidato realmente deve ter o cadastro invalidado.<br>
                                <strong>Justificativa:</strong> {{$inscricao->justificativa}}<br>
                                <form method="post" id="confirmar-invalidacao-candidato" action="{{route('inscricao.confirmar.invalidacao',['sisu_id' => $inscricao->chamada->sisu->id, 'chamada_id' => $inscricao->chamada->id, 'curso_id' => $inscricao->curso->id])}}">
                                    @csrf
                                    <input type="hidden" name="inscricaoID" value="{{$inscricao->id}}">
                                    <input type="hidden" name="curso" value="{{$inscricao->curso->id}}">
                                    <input type="hidden" name="confirmarInvalidacao" id="confirmarInvalidacao" value="">
                                    <div class="row justify-content-between mt-4" style="text-align: center">
                                        <div id ="negarInvalidacao" class="col-md-6 form-group">
                                            <button type="submit" class="btn botaoVerde my-2 py-1" onclick="atualizarInputConfirmarInvalidacao(false)"><span class="px-4" style="font-weight: bolder;" >Desfazer invalidação</span></button>
                                        </div>
                                        <div id ="confirmarInvalidacao" class="col-md-6 form-group">
                                            <button type="submit" class="btn botaoVerde my-2 py-1" style="background-color: #FC605F;" onclick="atualizarInputConfirmarInvalidacao(true)"><span class="px-4" style="font-weight: bolder;" >Confirmar invalidação</span></button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            @endcan
            <div class="row justify-content-between">
                <div class="col-md-7">
                    @if(session('nomeDoc'))
                        <script>
                            $(document).ready(function(){
                                carregarDocumento({!! json_encode(session('inscricao'), JSON_HEX_TAG) !!}, {!! json_encode(session('nomeDoc'), JSON_HEX_TAG) !!}, {!! json_encode(session('indice'), JSON_HEX_TAG) !!});
                            });
                        </script>
                    @endif
                    <div style="border-radius: 0.5rem;" class="col-md-12 p-0 shadow">
                        <div class="cabecalhoAzul p-2 px-3 align-items-center">
                            <div class="row align-items-center justify-content-between">
                                <div class="col-md-11">
                                    <a onclick="carregarFicha()" style="cursor:pointer;"><img src="{{asset('img/Grupo 1662.svg')}}"
                                        alt="" width="40" class="img-flex"></a>

                                    <label class="tituloTabelas ps-1" id="nomeDoc">Ficha Geral</label>
                                </div>
                                <div class="col-md-1" style="text-align: right">
                                    <a title="Próximo documento" onclick="carregarProxDoc({{$inscricao->id}}, 1)" style="cursor:pointer;"><img width="30" src="{{asset('img/Icon ionic-ios-arrow-dropright-circle.svg')}}"></a>
                                    <a title="Documento anterior" onclick="carregarProxDoc({{$inscricao->id}}, -1)" style="cursor:pointer;"><img width="30" src="{{asset('img/Icon ionic-ios-arrow-dropleft-circle.svg')}}"></a>
                                </div>
                            </div>
                        </div>
                        <div id="mensagemVazia" class="text-center" style="display: none;" >
                            <div class="col-md-12 text-center legenda" style="font-weight: bolder; font-size: 20px;">
                                Documento não enviado pelo candidato
                            </div>
                        </div>
                        <div class="corpo p-3" style="display: none;">
                            <div class="d-flex align-items-center my-2 pt-1 pb-3">
                                <iframe width="100%" height="700" frameborder="0" allowtransparency="true" id="documentoPDF" src="" ></iframe>
                            </div>

                            <div id="avaliarDoc" style="display: none">
                                <div class="col-md-12 px-3 pt-5">
                                    <div class="row justify-content-between">
                                        <div class="col-md-6">
                                            <a data-bs-toggle="modal" data-bs-target="#avaliar-documento-modal" id="raprovarBotao" style="background-color: #1492E6;;" class="me-1 btn botao my-2 py-1 col-md-5" onclick="atualizarInputReprovar()"> <span class="px-3 text-center">Recusar</span></a>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row justify-content-end">
                                                <a data-bs-toggle="modal" data-bs-target="#avaliar-documento-modal" id="aprovarBotao" class="btn botaoVerde my-2 py-1 col-md-5" onclick="atualizarInputAprovar()"><span class="px-3 text-center" >Aprovar</span></a>
                                            </div>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="corpo p-3" id="corpoFicha">
                            <div class="d-flex align-items-center my-2 pt-1 pb-3">
                                <div style="border-radius: 0.5rem;" class="shadow">
                                    <img class="aling-middle" width="130" src="@if($inscricao->arquivo('foto')) {{asset('storage/'.$inscricao->arquivo('foto')->caminho)}} @else{{asset('img/foto_geral.svg')}}@endif" alt="icone-busca">
                                </div>
                                <div class="">
                                    <div class="tituloDocumento mx-3">
                                        Nome: {{$inscricao->candidato->user->name}}
                                    </div>
                                    {{--<div class="tituloDocumento mx-3 pt-1">
                                        CEP: {{$inscricao->nu_cep}}
                                    </div>--}}
                                    <div class="tituloDocumento mx-3 pt-1">
                                        Data de Nascimento: {{date('d/m/Y',strtotime($inscricao->candidato->dt_nascimento))}}
                                    </div>
                                    <div class="tituloDocumento mx-3 pt-1">
                                        Sexo: {{$inscricao->tp_sexo}}
                                    </div>
                                    <div class="tituloDocumento mx-3 pt-1">
                                        Estado Civil:
                                    </div>
                                    <div class="tituloDocumento mx-3 pt-1">
                                        CPF: {{$inscricao->candidato->nu_cpf_inscrito}}
                                    </div>
                                    <div class="tituloDocumento mx-3 pt-1">
                                        Identidade: {{$inscricao->nu_rg}}
                                    </div>
                                    {{--<div class="tituloDocumento mx-3 pt-1">
                                        Data de Expedição:
                                    </div>--}}
                                </div>
                            </div>

                            <div class="col-md-12 py-3 px-3" style="border-bottom: 2px solid #f5f5f5;">
                                <div class="row">
                                    <div class="col-md-4 tituloDocumento">
                                        Título Eleitoral:
                                    </div>
                                    <div class="col-md-3 tituloDocumento">
                                        Zona:
                                    </div>
                                    <div class="col-md-3 tituloDocumento">
                                        Seção:
                                    </div>
                                </div>
                                <div class="row pt-2">
                                    <div class="col-md-4 tituloDocumento">
                                        UF:
                                    </div>
                                    <div class="col-md-3 tituloDocumento">
                                        País:
                                    </div>
                                    <div class="col-md-5 tituloDocumento">
                                        Cidade onde Nasceu:
                                    </div>
                                </div>
                                <div class="col-md-12 pt-2 tituloDocumento">
                                    Nome da Mãe: {{$inscricao->no_mae}}
                                </div>
                                <div class="col-md-12 pt-2 tituloDocumento">
                                    Nome do Pai:
                                </div>
                            </div>
                            <div class="col-md-12 py-3 px-3" style="border-bottom: 2px solid #f5f5f5;">
                                <div class="row">
                                    <div class="col-md-4 tituloDocumento">
                                        Unidade: {{$inscricao->no_campus}}
                                    </div>
                                    <div class="col-md-4 tituloDocumento">
                                        Formação: {{$inscricao->ds_formacao}}
                                    </div>
                                    <div class="col-md-4 tituloDocumento">
                                        Turno: {{$inscricao->ds_turno}}
                                    </div>
                                </div>
                                <div class="row pt-2">
                                    <div class="col-md-4 tituloDocumento">
                                        Forma de Ingresso: SiSU
                                    </div>
                                    <div class="col-md-4 tituloDocumento">
                                        Modalidade: {{$inscricao->no_modalidade_concorrencia}}
                                    </div>
                                    <div class="col-md-4 tituloDocumento">
                                        Ano de Ingresso: {{date('Y',strtotime($inscricao->dt_operacao))}}
                                    </div>
                                </div>
                                <div class="row pt-2">
                                    <div class="col-md-4 tituloDocumento">
                                        Curso: {{$inscricao->no_curso}}
                                    </div>
                                    {{--<div class="col-md-4 tituloDocumento">
                                        Semestre:
                                    </div>--}}
                                    <div class="col-md-4 tituloDocumento">
                                        Nota: {{$inscricao->nu_nota_candidato}}
                                    </div>
                                </div>
                                <div class="col-md-12 pt-2 tituloDocumento">
                                    Cota de Classificação:
                                </div>
                            </div>
                            <div class="col-md-12 py-3 px-3" style="border-bottom: 2px solid #f5f5f5;">
                                <div class="col-md-12 tituloDocumento">
                                    Endereço: {{$inscricao->ds_logradouro}}
                                </div>
                                <div class="row pt-2">
                                    <div class="col-md-4 tituloDocumento">
                                        Número: {{$inscricao->nu_endereco}}
                                    </div>
                                    <div class="col-md-4 tituloDocumento">
                                        CEP: {{$inscricao->nu_cep}}
                                    </div>
                                    <div class="col-md-4 tituloDocumento">
                                        Complemento: {{$inscricao->ds_complemento}}
                                    </div>
                                </div>
                                <div class="row pt-2">
                                    <div class="col-md-4 tituloDocumento">
                                        Cidade: {{$inscricao->no_municipio}}
                                    </div>
                                    <div class="col-md-4 tituloDocumento">
                                        Bairro: {{$inscricao->no_bairro}}
                                    </div>
                                    <div class="col-md-4 tituloDocumento">
                                        UF: {{$inscricao->sg_uf_inscrito}}
                                    </div>
                                </div>
                                <div class="row pt-2">
                                    <div class="col-md-4 tituloDocumento">
                                        Telefone:
                                    </div>
                                    <div class="col-md-4 tituloDocumento">
                                        Celular: {{$inscricao->nu_fone1}}
                                    </div>
                                </div>
                                <div class="col-md-12 pt-2 tituloDocumento">
                                    Email: @if($inscricao->candidato->user->primeiro_acesso == true){{$inscricao->ds_email}}@else{{$inscricao->candidato->user->email}}@endif
                                </div>
                            </div>
                            <div class="col-md-12 py-3 px-3" style="border-bottom: 2px solid #f5f5f5;">
                                <div class="col-md-12 tituloDocumento">
                                    Estabelecimento que concluiu o Ensino Médio:
                                </div>
                                <div class="row pt-2">
                                    <div class="col-md-2 tituloDocumento">
                                        UF:
                                    </div>
                                    <div class="col-md-4 tituloDocumento">
                                        Ano de Conclusão:
                                    </div>
                                    <div class="col-md-6 tituloDocumento">
                                        Modalidade:
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 py-3 px-3" style="border-bottom: 2px solid #f5f5f5;">
                                <div class="row pt-2">
                                    <div class="col-md-8 tituloDocumento">
                                        Necessidades Especiais:
                                    </div>
                                    <div class="col-md-4 tituloDocumento">
                                        Cor/Raça:
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 py-3 px-3">
                                <div class="tituloDocumento">
                                    Qual a Cidade/Estado onde você reside atualmente?
                                </div>
                                <div class="tituloDocumento pt-2">
                                    Seu local de moradia atual se encontra em:
                                </div>
                                <div class="tituloDocumento pt-2">
                                    Você trabalha?
                                </div>
                                <div class="tituloDocumento pt-2">
                                    Quantas pessoas fazem parte do seu grupo familiar?
                                </div>
                                <div class="tituloDocumento pt-2">
                                    Qual o valor da sua renda total?
                                </div>
                            </div>
                            {{--<div class="col-md-6">
                                <a href="{{route('chamadas.candidatos.curso', ['sisu_id' => $inscricao->chamada->sisu->id, 'chamada_id' => $inscricao->chamada->id, 'curso_id' => $inscricao->curso->id])}}" class="btn botao my-2 py-1 col-md-5"> <span class="px-4">Voltar</span></a>    
                            </div>--}}
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="col-md-12 caixa shadow p-3">
                            @can('isAdmin', \App\Models\User::class)
                                <div style="border-bottom: 1px solid #f5f5f5;" class="d-flex align-items-center justify-content-between pb-2">
                                    <div class="d-flex align-items-center">
                                        <span class="tituloTipoDoc">Documentação básica</span>
                                    </div>
                                    <a href="{{route('baixar.documentos.candidato', $inscricao->id)}}">
                                        <img width="35" src="{{asset('img/download1.svg')}}"></a>
                                    </a>
                                </div>
                            @else
                                @can('ehAnalistaGeral', \App\Models\User::class)
                                    <div style="border-bottom: 1px solid #f5f5f5;" class="d-flex align-items-center justify-content-between pb-2">
                                        <div class="d-flex align-items-center">
                                            <span class="tituloTipoDoc">Documentação básica</span>
                                        </div>
                                        <a href="{{route('baixar.documentos.candidato', $inscricao->id)}}">
                                            <img width="35" src="{{asset('img/download1.svg')}}"></a>
                                        </a>
                                    </div>
                                @endcan
                            @endcan
                            @foreach ($documentos as $indice =>  $documento)
                                @if($documento == 'rani')
                                    <div>
                                        <div style="border-bottom: 1px solid #f5f5f5;" class="d-flex align-items-center justify-content-between pb-2">
                                            <div class="d-flex align-items-center">
                                                <span class="tituloTipoDoc" style="font-size: 20px;">Comprovação da condição de beneficiário da reserva de
                                                    vaga para candidato autodeclarado indígena</span>
                                            </div>
                                        </div>
                                    </div>
                                @elseif($documento == 'heteroidentificacao')
                                    <div>
                                        <div style="border-bottom: 1px solid #f5f5f5;" class="d-flex align-items-center justify-content-between pb-2">
                                            <div class="d-flex align-items-center">
                                                <span class="tituloTipoDoc" style="font-size: 20px;">Comprovação da condição de beneficiário da reserva de
                                                    vaga para candidato autodeclarado negro (preto ou
                                                    pardo)</span>
                                            </div>
                                        </div>
                                    </div>
                                @elseif($documento == 'comprovante_renda')
                                    <div>
                                        <div style="border-bottom: 1px solid #f5f5f5;" class="d-flex align-items-center justify-content-between pb-2">
                                            <div class="d-flex align-items-center">
                                                <span class="tituloTipoDoc" style="font-size: 20px;">Comprovação da renda familiar bruta mensal per capita</span>
                                            </div>
                                        </div>
                                    </div>
                                @elseif($documento == 'laudo_medico')
                                    <div>
                                        <div style="border-bottom: 1px solid #f5f5f5;" class="d-flex align-items-center justify-content-between pb-2">
                                            <div class="d-flex align-items-center">
                                                <span class="tituloTipoDoc" style="font-size: 20px;">Comprovação da condição de beneficiário da reserva de
                                                    vaga para pessoas com deficiência</span>
                                            </div>
                                        </div>
                                    </div>
                                @elseif($documento == 'declaracao_cotista')
                                    <div>
                                        <div style="border-bottom: 1px solid #f5f5f5;" class="d-flex align-items-center justify-content-between pb-2">
                                            <div class="d-flex align-items-center">
                                                <span class="tituloTipoDoc" style="font-size: 20px;">Autodeclaração como candidato participante de reserva de vaga</span>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                <div class="col-md-12">
                                    <div class="d-flex align-items-center justify-content-between pt-3">
                                        @if($inscricao->arquivos()->where('nome', $documento)->first() != null)
                                            <div class="col-md-2">
                                                <a href="{{route('inscricao.arquivo', ['inscricao_id' => $inscricao->id, 'documento_nome' => $documento])}}" target="_blank" style="cursor:pointer;"><img src="{{asset('img/download2.svg')}}" alt="arquivo atual"  width="45" class="img-flex"></a>
                                            </div>
                                        @else
                                            <div class="col-md-2">
                                                <a target="_blank" style="cursor:pointer;"><img src="{{asset('img/download3.svg')}}" alt="arquivo atual"  width="45" class="img-flex"></a>
                                            </div>
                                        @endif

                                        @if($documento == 'declaracao_veracidade')
                                            <div class="col-md-10" style="cursor:pointer;">
                                                <div class="nomeDocumento ps-3" style="display:inline-block;" for="{{$documento}}" onclick="carregarDocumento({{$inscricao->id}}, '{{$documento}}', {{$indice}})">Declaração de Veracidade;</div>
                                            </div>
                                        @elseif($documento == 'certificado_conclusao')
                                            <div class="col-md-10" style="cursor:pointer;">
                                                <div class="nomeDocumento ps-3" style="display:inline-block;" for="{{$documento}}" onclick="carregarDocumento({{$inscricao->id}}, '{{$documento}}', {{$indice}})">Certificado de Conclusão do Ensino Médio ou Certidão de Exame Supletivo do Ensino Médio ou Certificação de Ensino Médio através do ENEM ou documento equivalente;</div>
                                            </div>
                                        @elseif($documento == 'historico')
                                            <div class="col-md-10" style="cursor:pointer;">
                                                <div class="nomeDocumento ps-3" style="display:inline-block;" for="{{$documento}}" onclick="carregarDocumento({{$inscricao->id}}, '{{$documento}}', {{$indice}})">Histórico Escolar do Ensino Médio ou equivalente;</div>
                                            </div>
                                        @elseif($documento == 'nascimento_ou_casamento')
                                            <div class="col-md-10" style="cursor:pointer;" >
                                                <div class="nomeDocumento ps-3" style="display:inline-block;"  for="{{$documento}}" onclick="carregarDocumento({{$inscricao->id}}, '{{$documento}}', {{$indice}})">Registro de Nascimento ou Certidão de Casamento;</div>
                                            </div>
                                        @elseif($documento == 'cpf')
                                            <div class="col-md-10" style="cursor:pointer;">
                                                <div class="nomeDocumento ps-3" style="display:inline-block;"  for="{{$documento}}" onclick="carregarDocumento({{$inscricao->id}}, '{{$documento}}', {{$indice}})">Cadastro de Pessoa Física (CPF) - pode estar no RG;</div>
                                            </div>
                                        @elseif($documento == 'rg')
                                            <div class="col-md-10" style="cursor:pointer;">
                                                <div class="nomeDocumento ps-3" style="display:inline-block;"  for="{{$documento}}" onclick="carregarDocumento({{$inscricao->id}}, '{{$documento}}', {{$indice}})">Carteira de Identidade (RG) - Frente e verso;</div>
                                            </div>
                                        @elseif($documento == 'quitacao_eleitoral')
                                            <div class="col-md-10" style="cursor:pointer;">
                                                <div class="nomeDocumento ps-3" style="display:inline-block;"  for="{{$documento}}" onclick="carregarDocumento({{$inscricao->id}}, '{{$documento}}', {{$indice}})">Comprovante de quitação com o Serviço Eleitoral no último turno de votação;</div>
                                            </div>
                                        @elseif($documento == 'quitacao_militar')
                                            <div class="col-md-10" style="cursor:pointer;">
                                                <div class="nomeDocumento ps-3" style="display:inline-block;"  for="{{$documento}}" onclick="carregarDocumento({{$inscricao->id}}, '{{$documento}}', {{$indice}})">Comprovante de quitação com o Serviço Militar, para candidatos do sexo masculino que tenham de 18 a 45 anos - Frente e verso;</div>
                                            </div>
                                        @elseif($documento == 'foto')
                                            <div class="col-md-10" style="cursor:pointer;">
                                                <div class="nomeDocumento ps-3" style="display:inline-block;" for="{{$documento}}" onclick="carregarDocumento({{$inscricao->id}}, '{{$documento}}', {{$indice}})">Uma foto 3x4 atual;</div>
                                            </div>
                                        @elseif($documento == 'rani')
                                            <div class="col-md-10" style="cursor:pointer;">
                                                <div class="nomeDocumento ps-3" style="display:inline-block;"  for="{{$documento}}" onclick="carregarDocumento({{$inscricao->id}}, '{{$documento}}', {{$indice}})">Registro Administrativo de Nascimento de Indígena ou equivalente;</div>
                                            </div>
                                        @elseif($documento == 'declaracao_cotista')
                                            <div class="col-md-10" style="cursor:pointer;">
                                                <div class="nomeDocumento ps-3" style="display:inline-block;"  for="{{$documento}}" onclick="carregarDocumento({{$inscricao->id}}, '{{$documento}}', {{$indice}})">Autodeclaração como candidato participante de reserva de vaga;</div>
                                            </div>
                                        @elseif($documento == 'heteroidentificacao')
                                            <div class="col-md-10" style="cursor:pointer;">
                                                <div class="nomeDocumento ps-3" style="display:inline-block;"  for="{{$documento}}" onclick="carregarDocumento({{$inscricao->id}}, '{{$documento}}', {{$indice}})">Vídeo individual e recente para procedimento de heteroidentificação;</div>
                                            </div>
                                        @elseif($documento == 'fotografia')
                                            <div class="col-md-10" style="cursor:pointer;">
                                                <div class="nomeDocumento ps-3" style="display:inline-block;"  for="{{$documento}}" onclick="carregarDocumento({{$inscricao->id}}, '{{$documento}}', {{$indice}})">Fotografia individual e recente para procedimento de heteroidentificação;</div>
                                            </div>
                                        @elseif($documento == 'comprovante_renda')
                                            <div class="col-md-10" style="cursor:pointer;">
                                                <div class="nomeDocumento ps-3" style="display:inline-block;"  for="{{$documento}}" onclick="carregarDocumento({{$inscricao->id}}, '{{$documento}}', {{$indice}})">Comprovante de renda, ou de que não possui renda, de cada membro do grupo familiar, seja maior ou menor de idade;</div>
                                            </div>
                                        @elseif($documento == 'laudo_medico')
                                            <div class="col-md-10" style="cursor:pointer;">
                                                <div class="nomeDocumento ps-3" style="display:inline-block;" for="{{$documento}}" onclick="carregarDocumento({{$inscricao->id}}, '{{$documento}}', {{$indice}})">Laudo médico;</div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @can('isAdmin', \App\Models\User::class)
                            <button id="efetivarBotao2" type="button" class="btn botaoVerde mt-4 py-1 col-md-12" onclick="atualizarInputEfetivar(true)"><span class="px-4">@if($inscricao->cd_efetivado != \App\Models\Inscricao::STATUS_VALIDACAO_CANDIDATO['cadastro_validado'])Validar Cadastro @else Cadastro Validado @endif</span></button>
                            <button id="efetivarBotao1" type="button" class="btn botao mt-2 py-1 col-md-12" onclick="atualizarInputEfetivar(false)"> <span class="px-4">@if(is_null($inscricao->cd_efetivado) ||  $inscricao->cd_efetivado == \App\Models\Inscricao::STATUS_VALIDACAO_CANDIDATO['cadastro_validado'])Invalidar Cadastro @else  Cadastro Invalidado @endif</span></button>
                        @else
                            @can('ehAnalistaGeral', \App\Models\User::class)
                                <button id="efetivarBotao2" type="button" class="btn botaoVerde mt-4 py-1 col-md-12" onclick="atualizarInputEfetivar(true)"><span class="px-4">@if($inscricao->cd_efetivado != \App\Models\Inscricao::STATUS_VALIDACAO_CANDIDATO['cadastro_validado'])Validar Cadastro @else Cadastro Validado @endif</span></button>
                                <button id="efetivarBotao1" type="button" class="btn botao mt-2 py-1 col-md-12" onclick="atualizarInputEfetivar(false)"> <span class="px-4">@if(is_null($inscricao->cd_efetivado) ||  $inscricao->cd_efetivado == \App\Models\Inscricao::STATUS_VALIDACAO_CANDIDATO['cadastro_validado'])Invalidar Cadastro @else  Cadastro Invalidado @endif</span></button>
                            @endcan
                        @endcan
                        <a data-bs-toggle="modal" data-bs-target="#enviar-email-candidato-modal" style="background-color: #1492E6;" class="btn botaoVerde mt-2 py-1 col-md-12"><span class="px-4">Enviar um e-mail para o candidato</span></a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--CORPO-->

    <div class="modal fade" id="enviar-email-candidato-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content modalFundo p-3">
                <div id ="enviarEmailText" class="col-md-12 tituloModal">Enviar e-mail</div>
                <div class="pt-3 pb-2 textoModal">
                    <form method="post" id="enviar-email-candidato" action="{{route('enviar.email.candidato')}}">
                        @csrf
                        <input type="hidden" name="inscricao_id" value="{{$inscricao->id}}">
                        <input type="hidden" name="curso_id" value="{{$inscricao->curso->id}}">
                        <div class="row">
                            <div class="col-md-12 textoModal">
                                <label class="pb-2" for="conteúdo">Assunto</label>
                                <input class="form-control campoDeTexto @error('assunto') is-invalid @enderror" type="text" name="assunto" id="assunto" placeholder="Digite o assunto">

                                @error('assunto')
                                    <div id="validationServer03Feedback" class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 pt-3 textoModal">
                                <label class="pb-2" for="conteúdo">Conteúdo</label>
                                <textarea id="conteúdo" class="form-control campoDeTexto @error('conteúdo') is-invalid @enderror" type="text" name="conteúdo" autofocus placeholder="Insira o conteúdo do e-mail aqui...">{{old('conteúdo')}}</textarea>

                                @error('conteúdo')
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
                        <button type="button" class="btn botao my-2 py-1" data-bs-dismiss="modal"> <span class="px-4" style="font-weight: bolder;">Cancelar</span></button>
                    </div>
                    <div id ="enviarEmailButton" class="col-md-4">
                        <button type="submit" class="btn botaoVerde my-2 py-1" form="enviar-email-candidato"><span class="px-4" style="font-weight: bolder;" >Enviar</span></button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="aprovar-recusar-candidato-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content modalFundo p-3">
                <div id ="reprovarCandidatoForm" class="col-md-12 tituloModal">Invalidar Cadastro</div>
                <div id ="aprovarCandidatoForm" class="col-md-12 tituloModal">Validar Cadastro</div>
                <div class="pt-3 pb-2 textoModal">
                    <form method="post" id="aprovar-reprovar-candidato" action="{{route('inscricao.status.efetivado',['sisu_id' => $inscricao->chamada->sisu->id, 'chamada_id' => $inscricao->chamada->id, 'curso_id' => $inscricao->curso->id])}}">
                        @csrf
                        <input type="hidden" name="inscricaoID" value="{{$inscricao->id}}">
                        <input type="hidden" name="curso" value="{{$inscricao->curso->id}}">
                        <input type="hidden" name="efetivar" id="inputEfetivar" value="">
                        <div id="aprovarCandidatoTextForm" class="pt-3">
                            Tem certeza que deseja validar o cadastro do candidato?
                        </div>
                        <div id="reprovarCandidatoTextForm" class="pt-3">
                            Tem certeza que deseja invalidar o cadastro do candidato?
                        </div>
                        <div id ="justificativaCadastroTextForm" class="form-row">
                            <div class="col-md-12 pt-3 textoModal">
                                <label class="pb-2" for="justificativa">Justificativa:</label>
                                <textarea id="justificativa" class="form-control campoDeTexto @error('justificativa') is-invalid @enderror" type="text" name="justificativa" required autofocus autocomplete="justificativa" placeholder="Insira alguma justificativa">{{old('justificativa', $inscricao->justificativa)}}</textarea>

                                @error('justificativa')
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
                        <button type="button" class="btn botao my-2 py-1" data-bs-dismiss="modal"> <span class="px-4" style="font-weight: bolder;">Cancelar</span></button>
                    </div>
                    <div id ="reprovarCandidatoButtonForm" class="col-md-4">
                        <button type="submit" class="btn botaoVerde my-2 py-1" form="aprovar-reprovar-candidato"style="background-color: #FC605F;"><span class="px-4" style="font-weight: bolder;" >Invalidar</span></button>
                    </div>
                    <div id ="aprovarCandidatoButtonForm" class="col-md-4">
                        <button type="submit" class="btn botaoVerde my-2 py-1" form="aprovar-reprovar-candidato"><span class="px-4" style="font-weight: bolder;" >Validar</span></button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="avaliar-documento-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content modalFundo p-3">
                <div id ="reprovarTituloForm" class="col-md-12 tituloModal">Reprovar documento</div>
                <div id ="aprovarTituloForm" class="col-md-12 tituloModal">Aprovar documento</div>
                <div class="pt-3 pb-2 textoModal">

                    <form method="POST" id="avaliar-documentos" action="{{route('inscricao.avaliar.documento', $inscricao->id)}}">
                        @csrf
                        <input type="hidden" name="inscricao_id" value="{{$inscricao->id}}">
                        <input type="hidden" name="documento_id" value="" id="documento_id">
                        <input type="hidden" value="-1" id="documento_indice">
                        <input type="hidden" name="aprovar" id="inputAprovar" value="">
                        <div id ="aprovarTextForm" class="pt-3">
                            Tem certeza que deseja aprovar este documento?
                        </div>
                        <div id ="reprovarTextForm" class="form-row">
                            <div class="col-md-12 pt-3 textoModal">
                                <label class="pb-2" for="comentario">Motivo:</label>
                                <textarea id="comentario" class="form-control campoDeTexto @error('comentario') is-invalid @enderror" type="text" name="comentario" value="{{old('comentario')}}" required autofocus autocomplete="comentario" placeholder="Insira o motivo para recusar o documento"></textarea>

                                @error('comentario')
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
                        <button type="button" class="btn botao my-2 py-1" data-bs-dismiss="modal"> <span class="px-4" style="font-weight: bolder;">Cancelar</span></button>
                    </div>
                    <div id ="reprovarButtonForm" class="col-md-4">
                        <button type="submit" class="btn botaoVerde my-2 py-1" form="avaliar-documentos"style="background-color: #FC605F;"><span class="px-4" style="font-weight: bolder;" >Recusar</span></button>
                    </div>
                    <div id ="aprovarButtonForm" class="col-md-4">
                        <button type="submit" class="btn botaoVerde my-2 py-1" form="avaliar-documentos"><span class="px-4" style="font-weight: bolder;" >Aprovar</span></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    function limparBotoes(){
        $("#avaliarDoc").hide();
        btnAprovar = document.getElementById("aprovarBotao");
        btnReprovar = document.getElementById("raprovarBotao");
        btnReprovar.innerText  = "Reprovar";
        btnReprovar.disabled = false;
        btnAprovar.disabled = false;
        btnAprovar.innerText  = "Aprovar";
    }

    function carregarDocumento(inscricao_id, documento_nome, indice) {
        this.limparBotoes();
        $("#mensagemVazia").hide();
        $("#corpoFicha").hide();
        var $iFrame = $('iframe');
        $.ajax({
            url:"{{route('inscricao.documento.ajax')}}",
            type:"get",
            data: {"inscricao_id": inscricao_id, "documento_nome": documento_nome},
            dataType:'json',
            success: function(documento) {
                atualizarNome(documento_nome);
                document.getElementById("documento_indice").value = indice;
                if(documento.id == null){
                    if($("#mensagemVazia").is(":hidden")){
                        $("#mensagemVazia").show();
                    }
                    $iFrame.hide();
                }else{
                    $iFrame.attr('src', documento.caminho);
                    document.getElementById("documentoPDF").parentElement.parentElement.style.display = '';
                    document.getElementById("documento_id").value = documento.id;
                    document.getElementById("documento_id").value = documento.id;
                    document.getElementById("comentario").value = documento.comentario;
                    btnAprovar = document.getElementById("aprovarBotao");
                    btnReprovar = document.getElementById("raprovarBotao");
                    if(documento.avaliacao == "1"){
                        btnAprovar.innerText  = "Aprovado";
                        btnAprovar.disabled = true;
                    }else if(documento.avaliacao == "2"){
                        btnReprovar.innerText  = "Reprovado";
                        btnReprovar.disabled = true;
                    }
                    $('#documentoPDF').on("load", function() {
                        $("#avaliarDoc").show();
                    });

                    if($iFrame.is(":hidden")){
                        $iFrame.show();
                    }
                }
            }
        });
    }

    function atualizarInputAprovar(){
        $('#comentario').attr('required', false);

        document.getElementById('inputAprovar').value = true;
        $("#aprovarTituloForm").show();
        $("#aprovarTextForm").show();
        $("#aprovarButtonForm").show();

        $('#reprovarTituloForm').hide();
        $('#reprovarTextForm').hide();
        $('#reprovarButtonForm').hide();
    }

    function atualizarInputReprovar(){
        $('#comentario').attr('required', true);

        document.getElementById('inputAprovar').value = false;
        $("#aprovarTituloForm").hide();
        $("#aprovarTextForm").hide();
        $("#aprovarButtonForm").hide();

        $('#reprovarTituloForm').show();
        $('#reprovarTextForm').show();
        $('#reprovarButtonForm').show();
    }

    function atualizarInputEfetivar(valor){
        document.getElementById('inputEfetivar').value = valor;
        if(valor == true){
            $('#justificativa').attr('required', false);

            $('#reprovarCandidatoForm').hide();
            $('#reprovarCandidatoTextForm').hide();
            $('#reprovarCandidatoButtonForm').hide();

            $("#aprovarCandidatoForm").show();
            $("#aprovarCandidatoTextForm").show();
            $("#aprovarCandidatoButtonForm").show();

            $('#aprovar-recusar-candidato-modal').modal('toggle');
        }else{
            $('#justificativa').attr('required', true);

            $("#aprovarCandidatoForm").hide();
            $("#aprovarCandidatoTextForm").hide();
            $("#aprovarCandidatoButtonForm").hide();

            $('#reprovarCandidatoForm').show();
            $('#reprovarCandidatoTextForm').show();
            $('#reprovarCandidatoButtonForm').show();

            $('#aprovar-recusar-candidato-modal').modal('toggle');
        }
    }

    function carregarFicha(){
        atualizarNome("ficha");
        document.getElementById("documento_indice").value = -1;
        document.getElementById("documentoPDF").parentElement.parentElement.style.display = 'none';
        document.getElementById("corpoFicha").style.display = '';
        $("#mensagemVazia").hide();
    }

    function carregarProxDoc(inscricao_id, valor){
        var indice = document.getElementById("documento_indice")
        var documento_indice = parseInt(document.getElementById("documento_indice").value)+valor;
        indice.value = documento_indice;
        $.ajax({
            url:"{{route('inscricao.documento.proximo')}}",
            type:"get",
            data: {"inscricao_id": inscricao_id, "documento_indice": documento_indice},
            dataType:'json',
            success: function(documento) {
                indice.value = documento.indice;
                console.log(indice.value);
                if(documento.nome == 'ficha'){
                    carregarFicha();
                }else{
                    carregarDocumento(inscricao_id, documento.nome, documento.indice);
                }
            }
        });
    }

    function atualizarNome($documento){
        $('#nomeDoc').text(getNome($documento));
    }

    function getNome($documento){
        if($documento == 'certificado_conclusao'){
            return "Certificado de Conclusão do Ensino Médio";
        }else if($documento == 'historico'){
            return "Histórico Escolar do Ensino Médio ou equivalente";
        }else if($documento == 'nascimento_ou_casamento'){
            return "Registro de Nascimento ou Certidão de Casamento";
        }else if($documento == 'cpf'){
            return "Cadastro de Pessoa Física (CPF)";
        }else if($documento == 'rg'){
            return "Carteira de Identidade (RG)";
        }else if($documento == 'quitacao_eleitoral'){
            return "Comprovante de quitação com o Serviço Eleitoral";
        }else if($documento == 'quitacao_militar'){
            return "Comprovante de quitação com o Serviço Militar";
        }else if($documento == 'foto'){
            return "Foto 3x4";
        }else if($documento == 'autodeclaracao'){
            return "Autodeclaração de cor/etnia";
        }else if($documento == 'comprovante_renda'){
            return "Comprovante de renda";
        }else if($documento == 'laudo_medico'){
            return "Laudo médico";
        }else if($documento == 'declaracao_veracidade'){
            return "Declaração de Veracidade";
        }else if($documento == 'rani'){
            return "Declaração Indígena";
        }else if($documento == 'heteroidentificacao'){
            return "Vídeo de Heteroidentificação";
        }else if($documento == 'fotografia'){
            return "Foto de Heteroidentificação";
        }else if($documento == 'declaracao_cotista'){
            return "Declaração Cotista";
        }else if($documento == 'ficha'){
            return "Ficha Geral";
        }
    }

    function atualizarInputConfirmarInvalidacao(valor){
        document.getElementById('confirmarInvalidacao').value = valor;
    }

</script>
