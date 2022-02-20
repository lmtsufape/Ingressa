<x-app-layout>
    <div class="fundo2 px-5">

        <div class="container">
            {{--<div class="row">
                <div class="col-sm-12">
                    <div class="col-md-12" style="text-align: right">
                        <a class="btn botao my-2 py-1" href="{{route('chamadas.candidatos.curso', ['sisu_id' => $inscricao->chamada->sisu->id, 'chamada_id' => $inscricao->chamada->id, 'curso_id' => $inscricao->curso->id])}}"> <span class="px-4">Voltar</span></a>
                    </div>
                </div>
            </div>--}}
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
                                <strong>Justificativa:</strong> {!!$inscricao->justificativa!!}<br>
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
            <div class="pb-3">
                <span style="color: #373737; font-size: 17px; font-weight: 700;" > <a href="{{route('sisus.show', ['sisu' => $chamada->sisu->id])}}" style="text-decoration: none; color: #373737;">  Chamada</a> > <a href="{{route('chamadas.candidatos', ['sisu_id' => $chamada->sisu->id, 'chamada_id' => $chamada->id])}}" style="text-decoration: none; color: #373737;"> Cursos</a> >  <a href="{{route('chamadas.candidatos.curso', ['sisu_id' => $inscricao->chamada->sisu->id, 'chamada_id' => $inscricao->chamada->id, 'curso_id' => $inscricao->curso->id])}}" style="text-decoration: none; color: #373737;"> {{$inscricao->curso->nome}} -
                    @switch($inscricao->curso->turno)
                        @case(App\Models\Curso::TURNO_ENUM['matutino'])
                            Matutino
                            @break
                        @case(App\Models\Curso::TURNO_ENUM['vespertino'])
                            Vespertino
                            @break
                        @case(App\Models\Curso::TURNO_ENUM['noturno'])
                            Noturno
                            @break
                        @case(App\Models\Curso::TURNO_ENUM['integral'])
                            Integral
                            @break
                    @endswitch </a>
                > </span> <span style="color: #24CEE8; font-size: 17px; font-weight: 600;">{{$inscricao->candidato->user->name}} - {{$inscricao->cota->cod_cota}}</span>
            </div>
            <div class="row justify-content-between">
                <div class="col-md-8">
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
                                <div class="col-md-10">
                                    <button title="Carregar ficha cadastral" onclick="carregarFicha()" style="cursor:pointer;"><img src="{{asset('img/Grupo 1662.svg')}}"
                                        alt="" width="40" class="img-flex" alt="Icone de ficha cadastral"></button>

                                    <label class="tituloTabelas ps-1" id="nomeDoc">Ficha Geral</label>
                                </div>
                                <div class="col-md-2" style="text-align: right">
                                    <button title="Documento anterior" onclick="carregarProxDoc({{$inscricao->id}}, -1)" style="cursor:pointer;"><img width="30" src="{{asset('img/Icon ionic-ios-arrow-dropleft-circle.svg')}}"></button>
                                    <button title="Próximo documento" onclick="carregarProxDoc({{$inscricao->id}}, 1)" style="cursor:pointer;"><img width="30" src="{{asset('img/Icon ionic-ios-arrow-dropright-circle.svg')}}"></button>
                                </div>
                            </div>
                        </div>
                        <div class="corpo" id="mensagemVazia" class="text-center" style="display: none;" >
                            <div class="row justify-content-center">
                                <div class="col-md-10">
                                    <p id="aguardandoTexto" style="font-weight: bolder; font-size: 18px; padding-top: 10px;">O candidato informou que:</p>
                                </div>
                            </div>
                            <div id="rowCheckboxCaixa" class="row justify-content-center">
                                <div class="col-md-10">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" checked disabled id="checkboxCaixa">
                                        <label class="form-check-label" id="caixaTexto" style="font-weight: bolder; font-size: 18px;" for="checkboxCaixa">
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 px-3 pt-5">
                                <div class="row justify-content-between">
                                    <div class="col-md-6">
                                        <a href="{{route('chamadas.candidatos.curso', ['sisu_id' => $inscricao->chamada->sisu->id, 'chamada_id' => $inscricao->chamada->id, 'curso_id' => $inscricao->curso->id])}}" type="button" class="btn botao my-2 py-1 col-md-5"><span class="px-4">Voltar</span></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="corpo p-3" style="display: none;">
                            <div class="d-flex align-items-center my-2 pt-1 pb-3">
                                <iframe width="100%" height="700" frameborder="0" allowtransparency="true" id="documentoPDF" src="" ></iframe>
                            </div>
                            <div id="motivo-reprovacao" style="display: none" class="col-md-12 alert alert-danger" role="alert">
                            </div>
                            <div id="motivo-aprovacao" style="display: none" class="col-md-12 alert alert-success" role="alert">
                            </div>

                            <div id="avaliarDoc" style="display: none">
                                <div class="col-md-12 px-3 pt-5">
                                    <div class="row justify-content-between">
                                        <div class="col-md-6">
                                            <a href="{{route('chamadas.candidatos.curso', ['sisu_id' => $inscricao->chamada->sisu->id, 'chamada_id' => $inscricao->chamada->id, 'curso_id' => $inscricao->curso->id])}}" type="button" class="btn botao my-2 py-1 col-md-5"><span class="px-4">Voltar</span></a>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row justify-content-end">
                                                <button data-bs-toggle="modal" data-bs-target="#avaliar-documento-modal" id="raprovarBotao" style="background-color: #FC605F;" class="me-1 btn botao my-2 py-1 col-md-5" onclick="atualizarInputReprovar();CKEDITOR.instances.comentario.setData( '', function() { this.updateElement();})"> <span class="px-3 text-center">Recusar</span></button>
                                                <button data-bs-toggle="modal" data-bs-target="#avaliar-documento-modal" id="aprovarBotao" class="btn botaoVerde my-2 py-1 col-md-5" onclick="atualizarInputAprovar();CKEDITOR.instances.comentario.setData( '', function() { this.updateElement();})"><span class="px-3 text-center" >Aprovar</span></button>
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
                                    <img class="aling-middle" width="130" src="@if($inscricao->arquivo('foto')) {{route('inscricao.arquivo', [$inscricao->id, 'foto'])}} @else{{asset('img/foto_geral.svg')}}@endif" alt="icone-busca">
                                </div>
                                <div class="">
                                    <div class="tituloDocumento mx-3">
                                        Nome: <p class="nomeDocumento" style="display: inline">{{$inscricao->candidato->user->name}}</p>
                                    </div>
                                    {{--<div class="tituloDocumento mx-3 pt-1">
                                        CEP: {{$inscricao->nu_cep}}
                                    </div>--}}
                                    <div class="tituloDocumento mx-3 pt-1">
                                        Data de Nascimento: <p class="nomeDocumento" style="display: inline">{{date('d/m/Y',strtotime($inscricao->candidato->dt_nascimento))}}</p>
                                    </div>
                                    <div class="tituloDocumento mx-3 pt-1">
                                        Sexo: <p class="nomeDocumento" style="display: inline">{{$inscricao->tp_sexo == "M" ? 'Masculino' : 'Feminino'}}</p>
                                    </div>
                                    <div class="tituloDocumento mx-3 pt-1">
                                        Estado Civil: @isset($inscricao->candidato->estado_civil)<p class="nomeDocumento" style="display: inline">{{\App\Models\Candidato::ESTADO_CIVIL[$inscricao->candidato->estado_civil]}}</p>@endisset
                                    </div>
                                    <div class="tituloDocumento mx-3 pt-1">
                                        CPF: <p class="nomeDocumento" style="display: inline">{{$inscricao->candidato->nu_cpf_inscrito}}</p>
                                    </div>
                                    <div class="tituloDocumento mx-3 pt-1">
                                        RG: <p class="nomeDocumento" style="display: inline">{{$inscricao->nu_rg}}</p>
                                    </div>
                                    {{--<div class="tituloDocumento mx-3 pt-1">
                                        Data de Expedição
                                    </div>--}}
                                </div>
                            </div>

                            <div class="col-md-12 py-3 px-3" style="border-bottom: 2px solid #f5f5f5;">
                                <div class="row">
                                    <div class="col-md-5 tituloDocumento">
                                        Orgão expedidor: <p class="nomeDocumento" style="display: inline">{{$inscricao->candidato->orgao_expedidor}}</p>
                                    </div>
                                    <div class="col-md-3 tituloDocumento">
                                        UF: <p class="nomeDocumento" style="display: inline">{{$inscricao->candidato->uf_rg}}</p>
                                    </div>
                                    <div class="col-md-4 tituloDocumento">
                                        Expedição: <p class="nomeDocumento" style="display: inline">{{date('d/m/Y', strtotime($inscricao->candidato->data_expedicao))}}</p>
                                    </div>
                                </div>
                                <div class="row pt-2">
                                    <div class="col-md-5 tituloDocumento">
                                        Título Eleitoral: <p class="nomeDocumento" style="display: inline">{{$inscricao->candidato->titulo}}</p>
                                    </div>
                                    <div class="col-md-3 tituloDocumento">
                                        Zona: <p class="nomeDocumento" style="display: inline">{{$inscricao->candidato->zona_eleitoral}}</p>
                                    </div>
                                    <div class="col-md-3 tituloDocumento">
                                        Seção: <p class="nomeDocumento" style="display: inline">{{$inscricao->candidato->secao_eleitoral}}</p>
                                    </div>
                                </div>
                                <div class="row pt-2">
                                    <div class="col-md-5 tituloDocumento">
                                        Naturalidade: <p class="nomeDocumento" style="display: inline">{{$inscricao->candidato->cidade_natal}}</p>
                                    </div>
                                    <div class="col-md-3 tituloDocumento">
                                        UF: <p class="nomeDocumento" style="display: inline">{{$inscricao->candidato->uf_natural}}</p>
                                    </div>
                                    <div class="col-md-3 tituloDocumento">
                                        País: <p class="nomeDocumento" style="display: inline">{{$inscricao->candidato->pais_natural}}</p>
                                    </div>
                                </div>
                                <div class="col-md-12 pt-2 tituloDocumento">
                                    Nome da Mãe: <p class="nomeDocumento" style="display: inline">{{$inscricao->no_mae}}</p>
                                </div>
                                <div class="col-md-12 pt-2 tituloDocumento">
                                    Nome do Pai: <p class="nomeDocumento" style="display: inline">{{$inscricao->candidato->pai}}</p>
                                </div>
                            </div>
                            <div class="col-md-12 py-3 px-3" style="border-bottom: 2px solid #f5f5f5;">
                                <div class="row">
                                    <div class="col-md-4 tituloDocumento">
                                        Unidade: <p class="nomeDocumento" style="display: inline">{{$inscricao->no_campus}}</p>
                                    </div>
                                    <div class="col-md-4 tituloDocumento">
                                        Formação: <p class="nomeDocumento" style="display: inline">{{$inscricao->ds_formacao}}</p>
                                    </div>
                                    <div class="col-md-4 tituloDocumento">
                                        Turno: <p class="nomeDocumento" style="display: inline">{{$inscricao->ds_turno}}</p>
                                    </div>
                                </div>
                                <div class="row pt-2">
                                    <div class="col-md-4 tituloDocumento">
                                        Forma de Ingresso: <p class="nomeDocumento" style="display: inline">SiSU</p>
                                    </div>
                                    <div class="col-md-4 tituloDocumento">
                                        Ano de Ingresso: <p class="nomeDocumento" style="display: inline">{{date('Y',strtotime($inscricao->dt_operacao))}}</p>
                                    </div>
                                    <div class="col-md-4 tituloDocumento">
                                        Nota: <p class="nomeDocumento" style="display: inline">{{$inscricao->nu_nota_candidato}}</p>
                                    </div>
                                </div>
                                <div class="row pt-2">
                                    <div class="col-md-8 tituloDocumento">
                                        Curso: <p class="nomeDocumento" style="display: inline">{{$inscricao->no_curso}}</p>
                                    </div>
                                    {{--<div class="col-md-4 tituloDocumento">
                                        Semestre:
                                    </div>--}}
                                </div>
                                <div class="col-md-12 pt-2 tituloDocumento">
                                    Cota de Classificação:<p class="nomeDocumento" style="display: inline"></p>
                                </div>
                                <div class="col-md-12 pt-2 tituloDocumento">
                                    Modalidade: <p class="nomeDocumento" style="display: inline">{{$inscricao->no_modalidade_concorrencia}}</p>
                                </div>
                            </div>
                            <div class="col-md-12 py-3 px-3" style="border-bottom: 2px solid #f5f5f5;">
                                <div class="col-md-12 tituloDocumento">
                                    Endereço: <p class="nomeDocumento" style="display: inline">{{$inscricao->ds_logradouro}}</p>
                                </div>
                                <div class="row pt-2">
                                    <div class="col-md-4 tituloDocumento">
                                        Número: <p class="nomeDocumento" style="display: inline">{{$inscricao->nu_endereco}}</p>
                                    </div>
                                    <div class="col-md-4 tituloDocumento">
                                        CEP: <p class="nomeDocumento" style="display: inline">{{$inscricao->nu_cep}}</p>
                                    </div>
                                    <div class="col-md-4 tituloDocumento">
                                        Complemento: <p class="nomeDocumento" style="display: inline">{{$inscricao->ds_complemento}}</p>
                                    </div>
                                </div>
                                <div class="row pt-2">
                                    <div class="col-md-4 tituloDocumento">
                                        Cidade: <p class="nomeDocumento" style="display: inline">{{$inscricao->no_municipio}}</p>
                                    </div>
                                    <div class="col-md-4 tituloDocumento">
                                        Bairro: <p class="nomeDocumento" style="display: inline">{{$inscricao->no_bairro}}</p>
                                    </div>
                                    <div class="col-md-4 tituloDocumento">
                                        UF: <p class="nomeDocumento" style="display: inline">{{$inscricao->sg_uf_inscrito}}</p>
                                    </div>
                                </div>
                                <div class="row pt-2">
                                    <div class="col-md-4 tituloDocumento">
                                        Celular: <p class="nomeDocumento" style="display: inline">{{$inscricao->nu_fone1}}</p>
                                    </div>
                                    <div class="col-md-4 tituloDocumento">
                                        Celular: <p class="nomeDocumento" style="display: inline">{{$inscricao->nu_fone2}}</p>
                                    </div>
                                </div>
                                <div class="col-md-12 pt-2 tituloDocumento">
                                    Email: <p class="nomeDocumento" style="display: inline">@if($inscricao->candidato->user->primeiro_acesso == true){{$inscricao->ds_email}}@else{{$inscricao->candidato->user->email}}@endif</p>
                                </div>
                            </div>
                            <div class="col-md-12 py-3 px-3" style="border-bottom: 2px solid #f5f5f5;">
                                <div class="col-md-12 tituloDocumento">
                                    Estabelecimento que concluiu o Ensino Médio: <p class="nomeDocumento" style="display: inline">{{$inscricao->candidato->escola_ens_med}}</p>
                                </div>
                                <div class="row pt-2">
                                    <div class="col-md-2 tituloDocumento">
                                        UF: <p class="nomeDocumento" style="display: inline">{{$inscricao->candidato->uf_escola}}</p>
                                    </div>
                                    <div class="col-md-4 tituloDocumento">
                                        Ano de Conclusão: <p class="nomeDocumento" style="display: inline">{{$inscricao->candidato->ano_conclusao}}</p>
                                    </div>
                                    <div class="col-md-6 tituloDocumento">
                                        Modalidade: <p class="nomeDocumento" style="display: inline">{{$inscricao->candidato->modalidade}}</p>
                                    </div>
                                </div>
                                <div class="row pt-2">
                                    <div class="col-md-12 tituloDocumento">
                                        Concluiu o Ensino Médio na rede pública?
                                        @isset($inscricao->candidato->concluiu_publica)<p class="nomeDocumento" style="display: inline">{{$inscricao->candidato->concluiu_publica ? 'Sim' : 'Não'}}</p>@endisset
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 py-3 px-3" style="border-bottom: 2px solid #f5f5f5;">
                                <div class="row">
                                    <div class="col-md-12 tituloDocumento">
                                        Necessidades Especiais:
                                        @isset($inscricao->candidato->necessidades)
                                        <p class="nomeDocumento" style="display: inline">
                                            @foreach (explode(',', $inscricao->candidato->necessidades) as $necessidade)
                                                {{\App\Models\Candidato::NECESSIDADES[$necessidade]}}@if(!$loop->last),@endif
                                            @endforeach
                                        </p>
                                        @endisset
                                    </div>
                                </div>
                                <div class="row pt-2">
                                    <div class="col-md-4 tituloDocumento">
                                        Cor/Raça: @isset($inscricao->candidato->cor_raca)<p class="nomeDocumento" style="display: inline">{{\App\Models\Candidato::COR_RACA[$inscricao->candidato->cor_raca]}}</p>@endisset
                                    </div>
                                    <div class="col-md-4 tituloDocumento">
                                        Etnia: @isset($inscricao->candidato->etnia)<p class="nomeDocumento" style="display: inline">{{\App\Models\Candidato::ETNIA[$inscricao->candidato->etnia]}}</p>@endisset
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 py-3 px-3">
                                <div class="tituloDocumento">
                                    Qual a Cidade/Estado onde você reside atualmente? <p class="nomeDocumento" style="display: inline">{{$inscricao->candidato->reside}}</p>
                                </div>
                                <div class="tituloDocumento pt-2">
                                    Seu local de moradia atual se encontra em: @isset($inscricao->candidato->localidade)<p class="nomeDocumento" style="display: inline">{{$inscricao->candidato->localidade == "zona_urbana" ? 'Zona urbana' : "Zona rural"}}</p>@endisset
                                </div>
                                <div class="tituloDocumento pt-2">
                                    Você exerce alguma atividade remunerada? @isset($inscricao->candidato->trabalha)<p class="nomeDocumento" style="display: inline">{{$inscricao->candidato->trabalha ? 'Sim' : 'Não'}}</p>@endisset
                                </div>
                                <div class="tituloDocumento pt-2">
                                    Quantas pessoas fazem parte do seu grupo familiar? <p class="nomeDocumento" style="display: inline">{{$inscricao->candidato->grupo_familiar}}</p>
                                </div>
                                <div class="tituloDocumento pt-2">
                                    Qual o valor da sua renda total? <p class="nomeDocumento" style="display: inline">{{$inscricao->candidato->valor_renda}}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <a href="{{route('chamadas.candidatos.curso', ['sisu_id' => $inscricao->chamada->sisu->id, 'chamada_id' => $inscricao->chamada->id, 'curso_id' => $inscricao->curso->id])}}" class="btn botao my-2 py-1 col-md-5"> <span class="px-4">Voltar</span></a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="col-md-12 caixa shadow p-3">
                            @can('isAdmin', \App\Models\User::class)
                                <div style="border-bottom: 1px solid #f5f5f5; line-height: 1.2;" class="d-flex align-items-center justify-content-between pb-2">
                                    <div class="d-flex align-items-center">
                                        <span class="tituloTipoDoc">Documentação básica</span>
                                    </div>
                                    <a title="Baixar todos os documentos do candidato" href="{{route('baixar.documentos.candidato', $inscricao->id)}}">
                                        <img width="35" src="{{asset('img/download1.svg')}}" alt="Icone de baixar todos os documentos"></a>
                                    </a>
                                </div>
                            @else
                                @can('ehAnalistaGeral', \App\Models\User::class)
                                    <div style="border-bottom: 1px solid #f5f5f5; line-height: 1.2;" class="d-flex align-items-center justify-content-between pb-2">
                                        <div class="d-flex align-items-center">
                                            <span class="tituloTipoDoc">Documentação básica</span>
                                        </div>
                                        <a title="Baixar todos os documentos do candidato" href="{{route('baixar.documentos.candidato', $inscricao->id)}}">
                                            <img width="35" src="{{asset('img/download1.svg')}}" alt="Icone de baixar todos os documentos"></a>
                                        </a>
                                    </div>
                                @endcan
                            @endcan
                            @foreach ($documentos as $indice =>  $documento)
                                @if($documento == 'rani')
                                    <div class="row">
                                        <div style="border-bottom: 1px solid #f5f5f5; line-height: 1.2;" class="d-flex align-items-center justify-content-between pb-2 pt-4">
                                            <div class="d-flex align-items-center">
                                                <span class="tituloTipoDoc">Comprovação da condição de beneficiário da reserva de
                                                    vaga para candidato autodeclarado indígena</span>
                                            </div>
                                        </div>
                                    </div>
                                @elseif($documento == 'heteroidentificacao')

                                    <div class="row">
                                        <div style="border-bottom: 1px solid #f5f5f5; line-height: 1.2;" class="d-flex align-items-center justify-content-between pb-2 pt-4">
                                            <div class="d-flex align-items-center">
                                                <span class="tituloTipoDoc">Comprovação da condição de beneficiário da reserva de
                                                    vaga para candidato autodeclarado negro (preto ou
                                                    pardo)</span>
                                            </div>
                                        </div>
                                    </div>
                                @elseif($documento == 'comprovante_renda')
                                    <div class="row">
                                        <div style="border-bottom: 1px solid #f5f5f5; line-height: 1.2;" class="d-flex align-items-center justify-content-between pb-2 pt-4">
                                            <div class="d-flex align-items-center">
                                                <span class="tituloTipoDoc">Comprovação da renda familiar bruta mensal per capita</span>
                                            </div>
                                        </div>
                                    </div>
                                @elseif($documento == 'laudo_medico')
                                    <div class="row">
                                        <div style="border-bottom: 1px solid #f5f5f5; line-height: 1.2;" class="d-flex align-items-center justify-content-between pb-2 pt-4">
                                            <div class="d-flex align-items-center">
                                                <span class="tituloTipoDoc">Comprovação da condição de beneficiário da reserva de
                                                    vaga para pessoas com deficiência</span>
                                            </div>
                                        </div>
                                    </div>
                                @elseif($documento == 'declaracao_cotista')
                                    <div class="row">
                                        <div style="border-bottom: 1px solid #f5f5f5; line-height: 1.2;" class="d-flex align-items-center justify-content-between pb-2 pt-4">
                                            <div class="d-flex align-items-center">
                                                <span class="tituloTipoDoc">Autodeclaração como candidato participante de reserva de vaga</span>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                <div class="col-md-12">
                                    <div class="d-flex align-items-center justify-content-between pt-3">
                                        @if($inscricao->arquivos()->where('nome', $documento)->first() != null)
                                            <div class="col-md-2">
                                                <a title="Abrir documento em nova aba" href="{{route('inscricao.arquivo', ['inscricao_id' => $inscricao->id, 'documento_nome' => $documento])}}" target="_blank" style="cursor:pointer;"><img @if(is_null($inscricao->arquivos()->where('nome', $documento)->first()->avaliacao)) src="{{asset('img/download2.svg')}}" @elseif($inscricao->arquivos()->where('nome', $documento)->first()->avaliacao->avaliacao == \App\Models\Avaliacao::AVALIACAO_ENUM['aceito'])  src="{{asset('img/documento-aceito.svg')}}" @elseif($inscricao->arquivos()->where('nome', $documento)->first()->avaliacao->avaliacao == \App\Models\Avaliacao::AVALIACAO_ENUM['recusado']) src="{{asset('img/documento-recusado.svg')}}" @else src="{{asset('img/download2.svg')}}" @endif alt="arquivo atual"  width="45" class="img-flex"></a>
                                            </div>
                                        @else
                                            <div class="col-md-2">
                                                <a title="Documento não enviado" target="_blank" style="cursor:pointer;"><img src="{{asset('img/download3.svg')}}" alt="arquivo atual"  width="45" class="img-flex"></a>
                                            </div>
                                        @endif

                                        @if($documento == 'declaracao_veracidade')
                                            <div class="col-md-10" style="cursor:pointer;">
                                                <button id="nomeDocumento{{$indice}}" class="nomeDocumento ps-3" style="display:inline-block; text-align: left;" for="{{$documento}}" onclick="carregarDocumento({{$inscricao->id}}, '{{$documento}}', {{$indice}})">Declaração de Veracidade;</button>
                                            </div>
                                        @elseif($documento == 'certificado_conclusao')
                                            <div class="col-md-10" style="cursor:pointer;">
                                                <button id="nomeDocumento{{$indice}}" class="nomeDocumento ps-3" style="display:inline-block; text-align: left;" for="{{$documento}}" onclick="carregarDocumento({{$inscricao->id}}, '{{$documento}}', {{$indice}})">Certificado de Conclusão do Ensino Médio ou Certidão de Exame Supletivo do Ensino Médio ou Certificação de Ensino Médio através do ENEM ou documento equivalente;</button>
                                            </div>
                                        @elseif($documento == 'historico')
                                            <div class="col-md-10" style="cursor:pointer;">
                                                <button id="nomeDocumento{{$indice}}" class="nomeDocumento ps-3" style="display:inline-block; text-align: left;" for="{{$documento}}" onclick="carregarDocumento({{$inscricao->id}}, '{{$documento}}', {{$indice}})">Histórico Escolar do Ensino Médio ou equivalente;</button>
                                            </div>
                                        @elseif($documento == 'nascimento_ou_casamento')
                                            <div class="col-md-10" style="cursor:pointer;" >
                                                <button id="nomeDocumento{{$indice}}" class="nomeDocumento ps-3" style="display:inline-block; text-align: left;"  for="{{$documento}}" onclick="carregarDocumento({{$inscricao->id}}, '{{$documento}}', {{$indice}})">Registro de Nascimento ou Certidão de Casamento;</button>
                                            </div>
                                        @elseif($documento == 'cpf')
                                            <div class="col-md-10" style="cursor:pointer;">
                                                <button id="nomeDocumento{{$indice}}" class="nomeDocumento ps-3" style="display:inline-block; text-align: left;"  for="{{$documento}}" onclick="carregarDocumento({{$inscricao->id}}, '{{$documento}}', {{$indice}})">Cadastro de Pessoa Física (CPF) - pode estar no RG;</button>
                                            </div>
                                        @elseif($documento == 'rg')
                                            <div class="col-md-10" style="cursor:pointer;">
                                                <button id="nomeDocumento{{$indice}}" class="nomeDocumento ps-3" style="display:inline-block; text-align: left;"  for="{{$documento}}" onclick="carregarDocumento({{$inscricao->id}}, '{{$documento}}', {{$indice}})">Carteira de Identidade (RG) - Frente e verso;</button>
                                            </div>
                                        @elseif($documento == 'quitacao_eleitoral')
                                            <div class="col-md-10" style="cursor:pointer;">
                                                <button id="nomeDocumento{{$indice}}" class="nomeDocumento ps-3" style="display:inline-block; text-align: left;"  for="{{$documento}}" onclick="carregarDocumento({{$inscricao->id}}, '{{$documento}}', {{$indice}})">Comprovante de quitação com o Serviço Eleitoral no último turno de votação;</button>
                                            </div>
                                        @elseif($documento == 'quitacao_militar')
                                            <div class="col-md-10" style="cursor:pointer;">
                                                <button id="nomeDocumento{{$indice}}" class="nomeDocumento ps-3" style="display:inline-block; text-align: left;"  for="{{$documento}}" onclick="carregarDocumento({{$inscricao->id}}, '{{$documento}}', {{$indice}})">Comprovante de quitação com o Serviço Militar, para candidatos do sexo masculino que tenham de 18 a 45 anos - Frente e verso;</button>
                                            </div>
                                        @elseif($documento == 'foto')
                                            <div class="col-md-10" style="cursor:pointer;">
                                                <button id="nomeDocumento{{$indice}}" class="nomeDocumento ps-3" style="display:inline-block; text-align: left;" for="{{$documento}}" onclick="carregarDocumento({{$inscricao->id}}, '{{$documento}}', {{$indice}})">Uma foto 3x4 atual;</button>
                                            </div>
                                        @elseif($documento == 'rani')
                                            <div class="col-md-10" style="cursor:pointer;">
                                                <button id="nomeDocumento{{$indice}}" class="nomeDocumento ps-3" style="display:inline-block; text-align: left;"  for="{{$documento}}" onclick="carregarDocumento({{$inscricao->id}}, '{{$documento}}', {{$indice}})">Registro Administrativo de Nascimento de Indígena ou equivalente;</button>
                                            </div>
                                        @elseif($documento == 'declaracao_cotista')
                                            <div class="col-md-10" style="cursor:pointer;">
                                                <button id="nomeDocumento{{$indice}}" class="nomeDocumento ps-3" style="display:inline-block; text-align: left;"  for="{{$documento}}" onclick="carregarDocumento({{$inscricao->id}}, '{{$documento}}', {{$indice}})">Autodeclaração como candidato participante de reserva de vaga;</button>
                                            </div>
                                        @elseif($documento == 'heteroidentificacao')
                                            <div class="col-md-10" style="cursor:pointer;">
                                                <button id="nomeDocumento{{$indice}}" class="nomeDocumento ps-3" style="display:inline-block; text-align: left;"  for="{{$documento}}" onclick="carregarDocumento({{$inscricao->id}}, '{{$documento}}', {{$indice}})">Vídeo individual e recente para procedimento de heteroidentificação;</button>
                                            </div>
                                        @elseif($documento == 'fotografia')
                                            <div class="col-md-10" style="cursor:pointer;">
                                                <button id="nomeDocumento{{$indice}}" class="nomeDocumento ps-3" style="display:inline-block; text-align: left;"  for="{{$documento}}" onclick="carregarDocumento({{$inscricao->id}}, '{{$documento}}', {{$indice}})">Fotografia individual e recente para procedimento de heteroidentificação;</button>
                                            </div>
                                        @elseif($documento == 'comprovante_renda')
                                            <div class="col-md-10" style="cursor:pointer;">
                                                <button id="nomeDocumento{{$indice}}" class="nomeDocumento ps-3" style="display:inline-block; text-align: left;"  for="{{$documento}}" onclick="carregarDocumento({{$inscricao->id}}, '{{$documento}}', {{$indice}})">Comprovante de renda, ou de que não possui renda, de cada membro do grupo familiar, seja maior ou menor de idade;</button>
                                            </div>
                                        @elseif($documento == 'laudo_medico')
                                            <div class="col-md-10" style="cursor:pointer;">
                                                <button id="nomeDocumento{{$indice}}" class="nomeDocumento ps-3" style="display:inline-block; text-align: left;" for="{{$documento}}" onclick="carregarDocumento({{$inscricao->id}}, '{{$documento}}', {{$indice}})">Laudo médico e exames;</button>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @can('isAdminOrAnalistaGeral', \App\Models\User::class)
                            @if($inscricao->status == \App\Models\Inscricao::STATUS_ENUM['documentos_pendentes'])
                                <button disabled type="button" class="btn botaoVerde mt-4 py-1 col-md-12"><span class="px-4">@if($inscricao->cd_efetivado != \App\Models\Inscricao::STATUS_VALIDACAO_CANDIDATO['cadastro_validado'])Validar Cadastro @else Cadastro Validado @endif</span></button>
                            @else
                                <button @if(($inscricao->status != \App\Models\Inscricao::STATUS_ENUM['documentos_aceitos_sem_pendencias'] && $inscricao->status != \App\Models\Inscricao::STATUS_ENUM['documentos_aceitos_com_pendencias'] && $inscricao->status == \App\Models\Inscricao::STATUS_ENUM['documentos_invalidados'])) disabled @endif id="efetivarBotao2" type="button" class="btn botaoVerde mt-4 py-1 col-md-12" onclick="atualizarInputEfetivar(true)"><span class="px-4">@if($inscricao->cd_efetivado != \App\Models\Inscricao::STATUS_VALIDACAO_CANDIDATO['cadastro_validado'])Validar Cadastro @else Cadastro Validado @endif</span></button>
                            @endif
                            <button @if($inscricao->status == \App\Models\Inscricao::STATUS_ENUM['documentos_aceitos_sem_pendencias'] || $inscricao->status == \App\Models\Inscricao::STATUS_ENUM['documentos_aceitos_com_pendencias'])@elseif($inscricao->status != \App\Models\Inscricao::STATUS_ENUM['documentos_invalidados']) disabled @endif id="efetivarBotao1" type="button" class="btn botao mt-2 py-1 col-md-12" onclick="atualizarInputEfetivar(false)" style="background-color: #FC605F;"> <span class="px-4">@if(is_null($inscricao->cd_efetivado) ||  $inscricao->cd_efetivado == \App\Models\Inscricao::STATUS_VALIDACAO_CANDIDATO['cadastro_validado'])Invalidar Cadastro @else  Cadastro Invalidado @endif</span></button>
                        @endcan
                        @can('isAdminOrHeteroidentificacao', \App\Models\User::class)
                            @if($inscricao->isCotaRacial() && $inscricao->candidato->isPretoOrPardo())
                                <button id="bloquearHeteroidentificacao" type="button" class="btn botao mt-2 py-1 col-md-12" onclick="bloquearCandidatoInput({{\App\Models\Inscricao::STATUS_RETIFICACAO['bloqueado_motivo_racial']}})" style="background-color: #FC605F;"> <span class="px-4">@if(is_null($inscricao->retificacao) || $inscricao->retificacao == \App\Models\Inscricao::STATUS_RETIFICACAO['bloqueado_motivo_medico'])Bloquear por não atender aos critérios fenotípicos @elseif($inscricao->retificacao == \App\Models\Inscricao::STATUS_RETIFICACAO['bloqueado_motivo_racial'] || $inscricao->retificacao == \App\Models\Inscricao::STATUS_RETIFICACAO['bloqueado_motivo_racial_e_medico'])Bloqueado por não atender aos critérios fenotípicos @endif</span></button>
                            @endif
                        @endcan
                        @can('isAdminOrMedico', \App\Models\User::class)
                            @if($inscricao->isCotaDeficiencia())
                                <button id="bloquearMedico" type="button" class="btn botao mt-2 py-1 col-md-12" onclick="bloquearCandidatoInput({{\App\Models\Inscricao::STATUS_RETIFICACAO['bloqueado_motivo_medico']}})" style="background-color: #FC605F;"> <span class="px-4">@if(is_null($inscricao->retificacao) || $inscricao->retificacao == \App\Models\Inscricao::STATUS_RETIFICACAO['bloqueado_motivo_racial'])Bloquear por não atender aos critérios médicos @elseif($inscricao->retificacao == \App\Models\Inscricao::STATUS_RETIFICACAO['bloqueado_motivo_medico'] || $inscricao->retificacao == \App\Models\Inscricao::STATUS_RETIFICACAO['bloqueado_motivo_racial_e_medico'])Bloqueado por não atender aos critérios médicos @endif</span></button>
                            @endif
                        @endcan
                        <button data-bs-toggle="modal" data-bs-target="#enviar-email-candidato-modal" class="btn botao mt-2 py-1 col-md-12"><span class="px-4">Enviar um e-mail para o candidato</span></button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--CORPO-->

    <div class="modal fade" id="enviar-email-candidato-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog  modal-lg">
            <div class="modal-content modalFundo p-3">
                <div id ="enviarEmailText" class="col-md-12 tituloModal">Enviar e-mail</div>
                <div class="pt-3 pb-2 textoModal">
                    <form method="post" id="enviar-email-candidato" action="{{route('enviar.email.candidato')}}">
                        @csrf
                        <input type="hidden" name="enviar_email" value="-1">
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
                                <textarea id="conteúdo" class="form-control campoDeTexto ckeditor-editor @error('conteúdo') is-invalid @enderror" type="text" name="conteúdo" autofocus placeholder="Insira o conteúdo do e-mail aqui...">{{old('conteúdo')}}</textarea>

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
                        <button type="submit" class="btn botaoVerde my-2 py-1" form="enviar-email-candidato" style="float: right;"><span class="px-4" style="font-weight: bolder;" >Enviar</span></button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="aprovar-recusar-candidato-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
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
                                <textarea id="justificativa" class="form-control campoDeTexto ckeditor-editor @error('justificativa') is-invalid @enderror" type="text" name="justificativa" autofocus autocomplete="justificativa" placeholder="Insira alguma justificativa">{{old('justificativa', $inscricao->justificativa)}}</textarea>

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
                        <button type="submit" class="btn botaoVerde my-2 py-1" form="aprovar-reprovar-candidato"style="background-color: #FC605F; float: right;"><span class="px-4" style="font-weight: bolder;" >Invalidar</span></button>
                    </div>
                    <div id ="aprovarCandidatoButtonForm" class="col-md-4">
                        <button type="submit" class="btn botaoVerde my-2 py-1" form="aprovar-reprovar-candidato" style="float: right;"><span class="px-4" style="font-weight: bolder;" >Validar</span></button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="bloquear-inscricao-racial" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content modalFundo p-3">
                <div class="col-md-12 tituloModal">Bloquear/Desbloquear Inscrição</div>
                <div class="pt-3 pb-2 textoModal">
                    <form method="post" id="bloquear-candidato-form" action="{{route('inscricao.bloquear.inscricao', ['sisu_id' => $inscricao->chamada->sisu->id, 'chamada_id' => $inscricao->chamada->id, 'curso_id' => $inscricao->curso->id])}}">
                        @csrf
                        <input type="hidden" name="inscricaoID" value="{{$inscricao->id}}">
                        <input type="hidden" name="bloquear" id="bloquearInscricao" value="">
                        <div class="pt-3">
                            Tem certeza que deseja bloquear/desbloquear a inscrição do candidato?<br>
                        </div>
                    </form>
                </div>
                <div class="row justify-content-between mt-4">
                    <div class="col-md-3">
                        <button type="button" class="btn botao my-2 py-1" data-bs-dismiss="modal"> <span class="px-4" style="font-weight: bolder;">Cancelar</span></button>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn botaoVerde my-2 py-1" form="bloquear-candidato-form" style="float: right;"><span class="px-4" style="font-weight: bolder;" >Sim</span></button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="avaliar-documento-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content modalFundo p-3">
                <div id ="reprovarTituloForm" class="col-md-12 tituloModal">Reprovar documento</div>
                <div id ="aprovarTituloForm" class="col-md-12 tituloModal">Aprovar documento</div>
                <div class="pt-3 pb-2 textoModal">

                    <form method="POST" id="avaliar-documentos" action="{{route('inscricao.avaliar.documento', $inscricao->id)}}">
                        @csrf
                        <input type="hidden" name="reprovar_documento" value="{{$inscricao->id}}">
                        <input type="hidden" name="inscricao_id" value="{{$inscricao->id}}">
                        <input type="hidden" name="documento_id" value="" id="documento_id">
                        <input type="hidden" name="documento_nome" value="" id="documento_nome">
                        <input type="hidden" name="documento_indice" value="-1" id="documento_indice">
                        <input type="hidden" name="aprovar" id="inputAprovar" value="">
                        <div id ="aprovarTextForm" class="pt-3">
                            Tem certeza que deseja aprovar este documento?
                        </div>
                        <div id ="reprovarTextForm" class="form-row">
                            <div class="col-md-12 pt-3 textoModal">
                                <label class="pb-2" for="comentario">Motivo:</label>
                                <textarea id="comentario" class="form-control campoDeTexto ckeditor-editor @error('comentario') is-invalid @enderror" name="comentario" placeholder="Insira o motivo para recusar o documento">{{old('comentario')}}</textarea>

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
                    <div class="col-md-6">
                        <button type="button" class="btn botao my-2 py-1" data-bs-dismiss="modal"> <span class="px-4" style="font-weight: bolder;">Cancelar</span></button>
                    </div>
                    <div id ="reprovarButtonForm" class="col-md-6" >
                        <button type="submit" class="btn botaoVerde my-2 py-1" form="avaliar-documentos"style="background-color: #FC605F; float: right;"><span class="px-4" style="font-weight: bolder;" >Recusar</span></button>
                    </div>
                    <div id ="aprovarButtonForm" class="col-md-6" >
                        <button type="submit" class="btn botaoVerde my-2 py-1" form="avaliar-documentos" style="float: right;"><span class="px-4" style="font-weight: bolder;" >Aprovar</span></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    $(document).ready(function(){
        var inputs = document.getElementsByClassName('ckeditor-editor');

        for(var i = 0; i < inputs.length; i++) {
            CKEDITOR.replace(inputs[i].id);
        }
    });

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
        var iFrame = $('#documentoPDF');
        $.ajax({
            url:"{{route('inscricao.documento.ajax')}}",
            type:"get",
            data: {"inscricao_id": inscricao_id, "documento_nome": documento_nome},
            dataType:'json',
            success: function(documento) {
                atualizarNome(documento_nome);
                if(parseInt(document.getElementById("documento_indice").value) != -1){
                    document.getElementById("nomeDocumento"+parseInt(document.getElementById("documento_indice").value)).style.fontWeight = "normal";
                }
                document.getElementById("nomeDocumento"+indice).style.fontWeight = "700";


                document.getElementById("documento_indice").value = indice;
                document.getElementById("motivo-reprovacao").style.display = "none";
                document.getElementById("motivo-aprovacao").style.display = "none";
                if(documento.id == null){
                    if($("#mensagemVazia").is(":hidden")){
                        if(documento.nome == "Aguardando o envio do documento."){
                            $("#rowCheckboxCaixa").hide();
                            document.getElementById("aguardandoTexto").innerHTML = documento.nome;
                        }else{
                            document.getElementById("aguardandoTexto").innerHTML = "O candidato informou que:";
                            document.getElementById("caixaTexto").innerHTML = documento.nome;
                            $("#rowCheckboxCaixa").show();
                        }
                        $("#mensagemVazia").show();
                    }
                    iFrame.hide();
                }else{
                    iFrame.attr('src', documento.caminho);
                    document.getElementById("documentoPDF").parentElement.parentElement.style.display = '';
                    document.getElementById("documento_id").value = documento.id;
                    document.getElementById("comentario").value = documento.comentario;
                    document.getElementById("documento_nome").value = documento_nome;

                    btnAprovar = document.getElementById("aprovarBotao");
                    btnReprovar = document.getElementById("raprovarBotao");
                    document.getElementById("motivo-reprovacao").style.display = "none";
                    document.getElementById("motivo-aprovacao").style.display = "none";
                    if(documento.avaliacao == "1"){
                        btnAprovar.innerText  = "Aprovado";
                        if(documento.comentario != null){
                            document.getElementById("motivo-aprovacao").innerHTML = documento.comentario;
                            document.getElementById("motivo-aprovacao").style.display = "block";
                        }
                        btnAprovar.disabled = true;
                    }else if(documento.avaliacao == "2"){
                        btnReprovar.innerText  = "Reprovado";
                        document.getElementById("motivo-reprovacao").innerHTML = documento.comentario;
                        document.getElementById("motivo-reprovacao").style.display = "block";
                        btnReprovar.disabled = true;
                    }
                    if(documento.analisaGeral == true && (documento_nome == "heteroidentificacao" || documento_nome == "fotografia" || documento_nome == "laudo_medico")){
                        btnAprovar.disabled = true;
                        btnReprovar.disabled = true;
                    }
                    $('#documentoPDF').on("load", function() {
                        $("#avaliarDoc").show();
                    });

                    if(iFrame.is(":hidden")){
                        iFrame.show();
                    }
                }
            }
        });
    }

    function atualizarInputAprovar(){

        $('#comentario').attr('required', false);
        $('#comentario').val('');

        document.getElementById('inputAprovar').value = true;
        $("#aprovarTituloForm").show();
        $("#aprovarTextForm").show();
        $("#aprovarButtonForm").show();

        $('#reprovarTituloForm').hide();
        if($('#nomeDoc').text() == getNome('laudo_medico') || $('#nomeDoc').text() == getNome('heteroidentificacao') || $('#nomeDoc').text() == getNome('fotografia')){
            $('#reprovarTextForm').show();
        }else{
            $('#reprovarTextForm').hide();
        }
        $('#reprovarButtonForm').hide();
    }

    function atualizarInputReprovar(){
        $('#comentario').attr('required', false);
        $('#comentario').val('');

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
        $('#justificativa').attr('required', false);
        if(valor == true){
            $('#reprovarCandidatoForm').hide();
            $('#reprovarCandidatoTextForm').hide();
            $('#reprovarCandidatoButtonForm').hide();

            $("#aprovarCandidatoForm").show();
            $("#aprovarCandidatoTextForm").show();
            $("#aprovarCandidatoButtonForm").show();

            $('#aprovar-recusar-candidato-modal').modal('toggle');
        }else{
            $("#aprovarCandidatoForm").hide();
            $("#aprovarCandidatoTextForm").hide();
            $("#aprovarCandidatoButtonForm").hide();

            $('#reprovarCandidatoForm').show();
            $('#reprovarCandidatoTextForm').show();
            $('#reprovarCandidatoButtonForm').show();

            $('#aprovar-recusar-candidato-modal').modal('toggle');
        }
    }

    function bloquearCandidatoInput(valor){
        document.getElementById('bloquearInscricao').value = valor;
        $('#bloquear-inscricao-racial').modal('toggle');
    }

    function carregarFicha(){
        if(parseInt(document.getElementById("documento_indice").value) != -1){
            document.getElementById("nomeDocumento"+parseInt(document.getElementById("documento_indice").value)).style.fontWeight = "normal";
        }
        atualizarNome("ficha");
        document.getElementById("documento_indice").value = -1;
        document.getElementById("documentoPDF").parentElement.parentElement.style.display = 'none';
        document.getElementById("corpoFicha").style.display = '';
        $("#mensagemVazia").hide();
    }

    function carregarProxDoc(inscricao_id, valor){
        var indice = document.getElementById("documento_indice")
        if(parseInt(document.getElementById("documento_indice").value) != -1){
            document.getElementById("nomeDocumento"+parseInt(document.getElementById("documento_indice").value)).style.fontWeight = "normal";
        }
        var documento_indice = parseInt(document.getElementById("documento_indice").value)+valor;
        indice.value = documento_indice;
        $.ajax({
            url:"{{route('inscricao.documento.proximo')}}",
            type:"get",
            data: {"inscricao_id": inscricao_id, "documento_indice": documento_indice},
            dataType:'json',
            success: function(documento) {
                indice.value = documento.indice;
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
            return "Laudo médico e exames";
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

@if(old('reprovar_documento') != null)
    <script>
        $(document).ready(function() {
            carregarDocumento("{{old('reprovar_documento')}}", "{{old('documento_nome')}}", "{{old('documento_indice')}}");
            atualizarInputReprovar();
            $('#avaliar-documento-modal').modal('show');
        });
    </script>
@endif

@if (old('inscricaoID') != null)
    <script>
        $(document).ready(function() {
            atualizarInputEfetivar(false);
            $('#aprovar-recusar-candidato-modal').modal('show');
        });
    </script>
@endif

@if (old('enviar_email') == -1)
    <script>
        $(document).ready(function(){
            $('#enviar-email-candidato-modal').modal('show');
        });
    </script>
@endif
