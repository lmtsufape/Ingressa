<x-app-layout>
    <div class="fundo2 px-5">
        <div class="col-md-11 px-0" style="text-align: right">
            <a class="btn botao my-2 py-1" href="{{route('chamadas.candidatos.curso', ['sisu_id' => $inscricao->chamada->sisu->id, 'chamada_id' => $inscricao->chamada->id, 'curso_id' => $inscricao->curso->id])}}"> <span class="px-4">Voltar</span></a>
        </div>
        <div class="container">
            <div class="row justify-content-between">
                <div class="col-md-7">
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
                        <div class="alert alert-danger" role="alert">
                            {{$message}}
                        </div>
                    @enderror
                    <div style="border-radius: 0.5rem;" class="col-md-12 p-0 shadow">
                        <div class="cabecalhoAzul p-2 px-3 align-items-center">
                            <div class="row justify-content-between">
                              <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <a onclick="carregarFicha()" style="cursor:pointer;"><img src="{{asset('img/Grupo 1662.svg')}}"
                                        alt="" width="40" class="img-flex"></a>

                                    <label class="tituloTabelas ps-1" id="nomeDoc">Ficha Geral</label>
                                </div>
                                  <a><img width="30" src="{{asset('img/Icon ionic-ios-arrow-dropright-circle.svg')}}"></a>
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
                            <form method="POST" id="analisar-documentos" action="{{route('inscricao.avaliar.documento', $inscricao->id)}}">
                                @csrf
                                <input type="hidden" name="inscricao_id" value="{{$inscricao->id}}">
                                <input type="hidden" name="documento_id" value="" id="documento_id">
                                <input type="hidden" name="aprovar" id="inputAprovar" value="">
                                <div id="avaliarDoc" style="display: none">
                                    <div class="col-md-12 px-3 pt-5">
                                        <div class="row justify-content-between">
                                            <a id="textoComent" style="cursor:pointer; font-size: 12px; color: #1492E6">Deseja adicionar alguma observação?</a>
                                            <button id="raprovarBotao" type="submit" class="btn botao my-2 py-1 col-md-3"  onclick="atualizarInputReprovar()">Recusar</button>
                                            <button id="aprovarBotao" type="submit" class="btn botaoVerde my-2 py-1 col-md-3" onclick="atualizarInputAprovar()">Aprovar</button>
                                        </div>
                                        </div>
                                    </div>
                                    <div id="divComent" style="display: none">
                                        <input type="text" name="comentario" id="comentarioTexto">
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="corpo p-3" id="corpoFicha">
                            <div class="d-flex align-items-center my-2 pt-1 pb-3">
                                <div style="border-radius: 0.5rem;" class="shadow">
                                    <img class="aling-middle" width="130" src="{{asset('img/foto_geral.svg')}}" alt="icone-busca">
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
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="col-md-12 caixa shadow p-3">
                            <div style="border-bottom: 1px solid #f5f5f5;" class="d-flex align-items-center justify-content-between pb-2">
                                <div class="d-flex align-items-center">
                                <span class="tituloTipoDoc">Documentação Geral</span>
                            </div>
                              <a><img width="35" src="{{asset('img/download1.svg')}}"></a>
                        </div>
                        @foreach ($documentos as $documento)
                            @if($documento == 'autodeclaracao')
                                <div>
                                    <div style="border-bottom: 1px solid #f5f5f5;" class="d-flex align-items-center justify-content-between pb-2">
                                        <div class="d-flex align-items-center">
                                        <span class="tituloTipoDoc">Candidatos inscritos em cotas de cor/etnia</span>
                                    </div>
                                </div>
                            @endif
                            @if($documento == 'comprovante_renda')
                                <div>
                                    <div style="border-bottom: 1px solid #f5f5f5;" class="d-flex align-items-center justify-content-between pb-2">
                                        <div class="d-flex align-items-center">
                                        <span class="tituloTipoDoc">Candidatos inscritos em cotas de candidatos com renda familiar bruta per
                                            capita igual ou inferior a 1,5 salário mínimo</span>
                                    </div>
                                </div>
                            @endif
                            @if($documento == 'laudo_medico')
                                <div>
                                    <div style="border-bottom: 1px solid #f5f5f5;" class="d-flex align-items-center justify-content-between pb-2">
                                        <div class="d-flex align-items-center">
                                        <span class="tituloTipoDoc">Candidatos inscritos em cotas de Pessoa Com Deficiência (PCD)</span>
                                    </div>
                                </div>
                            @endif
                            <div class="d-flex align-items-center justify-content-between pt-3">
                                <div class="d-flex align-items-center">
                                    @if($inscricao->arquivos()->where('nome', $documento)->first() != null)
                                        <a href="{{route('inscricao.arquivo', ['inscricao_id' => $inscricao->id, 'documento_nome' => $documento])}}" target="_blank" style="cursor:pointer;"><img src="{{asset('img/download2.svg')}}" alt="arquivo atual"  width="45" class="img-flex"></a>
                                    @else
                                        <a target="_blank" style="cursor:pointer;"><img src="{{asset('img/download2.svg')}}" alt="arquivo atual"  width="45" class="img-flex"></a>
                                    @endif

                                    @if($documento == 'certificado_conclusao')
                                        <span class="nomeDocumento ps-3" for="{{$documento}}" style="cursor:pointer;" onclick="carregarDocumento({{$inscricao->id}}, '{{$documento}}')">Certificado de Conclusão do Ensino Médio ou Certidão de Exame Supletivo do Ensino Médio ou Certificação de Ensino Médio através do ENEM ou documento equivalente;</span>
                                    @elseif($documento == 'historico')
                                        <span class="nomeDocumento ps-3" for="{{$documento}}" style="cursor:pointer;" onclick="carregarDocumento({{$inscricao->id}}, '{{$documento}}')">Histórico Escolar do Ensino Médio ou equivalente;</span>
                                    @elseif($documento == 'nascimento_ou_casamento')
                                        <span class="nomeDocumento ps-3" for="{{$documento}}" style="cursor:pointer;" onclick="carregarDocumento({{$inscricao->id}}, '{{$documento}}')">Registro de Nascimento ou Certidão de Casamento;</span>
                                    @elseif($documento == 'cpf')
                                        <span class="nomeDocumento ps-3" for="{{$documento}}" style="cursor:pointer;" onclick="carregarDocumento({{$inscricao->id}}, '{{$documento}}')">Cadastro de Pessoa Física (CPF) - pode estar no RG;</span>
                                    @elseif($documento == 'rg')
                                        <span class="nomeDocumento ps-3" for="{{$documento}}" style="cursor:pointer;" onclick="carregarDocumento({{$inscricao->id}}, '{{$documento}}')">Carteira de Identidade (RG) - Frente e verso;</span>
                                    @elseif($documento == 'quitacao_eleitoral')
                                        <span class="nomeDocumento ps-3" for="{{$documento}}" style="cursor:pointer;" onclick="carregarDocumento({{$inscricao->id}}, '{{$documento}}')">Comprovante de quitação com o Serviço Eleitoral no último turno de votação;</span>
                                    @elseif($documento == 'quitacao_militar')
                                        <span class="nomeDocumento ps-3" for="{{$documento}}" style="cursor:pointer;" onclick="carregarDocumento({{$inscricao->id}}, '{{$documento}}')">Comprovante de quitação com o Serviço Militar, para candidatos do sexo masculino que tenham de 18 a 45 anos - Frente e verso;</span>
                                    @elseif($documento == 'foto')
                                        <span class="nomeDocumento ps-3" for="{{$documento}}" style="cursor:pointer;" onclick="carregarDocumento({{$inscricao->id}}, '{{$documento}}')">Uma foto 3x4 atual;</span>
                                    @elseif($documento == 'autodeclaracao')
                                        <span class="nomeDocumento ps-3" for="{{$documento}}" style="cursor:pointer;" onclick="carregarDocumento({{$inscricao->id}}, '{{$documento}}')">Autodeclaração de cor/etnia;</span>
                                    @elseif($documento == 'comprovante_renda')
                                        <span class="nomeDocumento ps-3" for="{{$documento}}" style="cursor:pointer;" onclick="carregarDocumento({{$inscricao->id}}, '{{$documento}}')">Comprovante de renda, ou de que não possui renda, de cada membro do grupo familiar, seja maior ou menor de idade;</span>
                                    @elseif($documento == 'laudo_medico')
                                        <span class="nomeDocumento ps-3" for="{{$documento}}" style="cursor:pointer;" onclick="carregarDocumento({{$inscricao->id}}, '{{$documento}}')">Laudo médico;</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                        <form method="post" action="{{route('inscricao.status.efetivado',['sisu_id' => $inscricao->chamada->sisu->id, 'chamada_id' => $inscricao->chamada->id, 'curso_id' => $inscricao->curso->id])}}">
                            @csrf
                            <input type="hidden" name="inscricaoID" value="{{$inscricao->id}}">
                            <input type="hidden" name="curso" value="{{$inscricao->curso->id}}">
                            <input type="hidden" name="efetivar" id="inputEfetivar" value="">
                            <button id="efetivarBotao2" type="submit" class="btn botaoVerde mt-4 py-1 col-md-12"><span class="px-4" onclick="atualizarInputEfetivar(true)" {{$inscricao->cd_efetivado == true ? 'disabled' : '' }}>{{$inscricao->cd_efetivado == true ? 'Efetivato' : 'Efetivar' }}</button>
                            <button id="efetivarBotao1" type="submit" class="btn botao mt-2 py-1 col-md-12"> <span class="px-4" onclick="atualizarInputEfetivar(false)" {{$inscricao->cd_efetivado == false ? 'disabled' : '' }}>{{$inscricao->cd_efetivado == true ? 'Desfazer efetivar' : 'Não efetivado' }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
      </div>

    <!--CORPO-->

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

    function carregarDocumento(inscricao_id, documento_nome) {
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
                    document.getElementById("comentarioTexto").value = documento.comentario;
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
        document.getElementById('inputAprovar').value = true;
    }

    function atualizarInputReprovar(){
        document.getElementById('inputAprovar').value = false;
    }

    function atualizarInputEfetivar(valor){
        document.getElementById('inputEfetivar').value = valor;
    }

    function carregarFicha(){
        atualizarNome("ficha");
        document.getElementById("documentoPDF").parentElement.parentElement.style.display = 'none';
        document.getElementById("corpoFicha").style.display = '';
        $("#mensagemVazia").hide();
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
        }else if($documento == 'ficha'){
            return "Ficha Geral";
        }
    }

    $("#textoComent").click(function() {
        if($("#divComent").is(":hidden")){
            $("#divComent").show();
        }else{
            $("#divComent").hide();
        }
    });
</script>
