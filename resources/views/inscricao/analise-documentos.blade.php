<x-app-layout>
    <div class="fundo px-5 py-5">
        <div class="py-3 px-4 row ms-0 justify-content-between">
            <div class="col-md-8 cabecalho shadow p-3 align-items-center"  style="background-color: #24cee8">
                <div class="row justify-content-between" >
                    <div class="d-flex align-items-center justify-content-between" >
                        <div class="d-flex align-items-center">
                            <img src="{{asset('img/Grupo 1662.svg')}}"
                            alt="" width="40" class="img-flex">
                            <span class="tituloTabelas ps-1">Ficha Geral</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-8 shadow p-3 corpo align-items-center">
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
                <div id="mensagemVazia" class="text-center" style="display: none;" >
                    <div class="col-md-12 text-center legenda" style="font-weight: bolder; font-size: 20px;">
                        Documento não enviado pelo candidato
                    </div>
                </div>
                <iframe width="100%" height="85%" frameborder="0" allowtransparency="true" id="documentoPDF" src=""></iframe>
                <form method="POST" id="analisar-documentos" action="{{route('inscricao.avaliar.documento', $inscricao->id)}}">
                    @csrf
                    <input type="hidden" name="inscricao_id" value="{{$inscricao->id}}">
                    <input type="hidden" name="documento_id" value="" id="documento_id">
                    <input type="hidden" name="aprovar" id="inputAprovar" value="">
                    <div id="avaliarDoc" style="margin-top: 40px; display: none">
                        <div class="form-row justify-content-center">
                            <div class="col-md-12" style="text-align: left">
                                <a id="textoComent" style="cursor:pointer; font-size: 12px; color: #24cee8">Deseja adicionar alguma observação?</a>
                                <button id="raprovarBotao" type="submit" class="btn btn-success btn-cota" style="margin-left: 30px; width: 30%" onclick="atualizarInputReprovar()">Reprovar</button>
                                <button id="aprovarBotao" type="submit" class="btn btn-primary btn-claro" style="margin-left: 30px; width: 30%" onclick="atualizarInputAprovar()">Aprovar</button>
                            </div>
                        </div>
                        <div id="divComent" style="display: none">
                            <input type="text" name="comentario" id="comentarioTexto">
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-md-3 shadow p-3 caixa">
                <div class="row justify-content-between" >
                    <div class="d-flex align-items-center justify-content-between" >
                        <div class="col-md-12 data" style="font-weight: bolder;">
                            Documentação Geral
                        </div>
                    </div>
                </div>
                @foreach ($documentos as $documento)
                    @if($documento == 'autodeclaracao')
                        <div class="col-md-12 data" style="margin-top: 5px; font-weight: bolder; font-size: 20px;">
                            Candidatos inscritos em cotas de cor/etnia
                        </div>
                    @endif
                    @if($documento == 'comprovante_renda')
                        <div class="col-md-12 data" style="margin-top: 5px; font-weight: bolder; font-size: 20px;">
                            Candidatos inscritos em cotas de candidatos com renda familiar bruta per
                            capita igual ou inferior a 1,5 salário mínimo
                        </div>
                    @endif
                    @if($documento == 'laudo_medico')
                        <div class="col-md-12 data" style="margin-top: 5px; font-weight: bolder; font-size: 20px;">
                            Candidatos inscritos em cotas de Pessoa Com Deficiência (PCD)
                        </div>
                    @endif
                    <div class="form-row justify-content-start" style="margin-top: 10px; width: 100%; cursor:pointer;" onclick="carregarDocumento({{$inscricao->id}}, '{{$documento}}')">
                        <div class="d-flex justify-content-start" >
                            <div class="col-md-4">
                                @if($inscricao->arquivos()->where('nome', $documento)->first() != null) <a href="{{route('inscricao.arquivo', ['inscricao_id' => $inscricao->id, 'documento_nome' => $documento])}}" target="_blank" style="float: right; cursor:pointer; background: #24cee8; margin-right: 5px; border-radius:50%;  padding:10px;"><img src="{{asset('img/icon download preto.svg')}}" alt="arquivo atual" style="width: 25px;"></a>@else <a target="_blank"  style="float: right; cursor:pointer; background: #b8c3c5; margin-right: 5px; border-radius:50%;  padding:10px;"><img src="{{asset('img/icon download preto.svg')}}" alt="arquivo atual" style="width: 25px;"></a>@endif
                            </div>
                            <div class="col-md-8">
                                @if($documento == 'certificado_conclusao')
                                    <label for="{{$documento}}" style="color: black; font-weight: bolder;">Certificado de Conclusão do Ensino Médio ou Certidão de Exame Supletivo do Ensino Médio ou Certificação de Ensino Médio através do ENEM ou documento equivalente. </label>
                                @elseif($documento == 'historico')
                                    <label for="{{$documento}}" style="color: black; font-weight: bolder;"> Histórico Escolar do Ensino Médio ou equivalente. </label>
                                @elseif($documento == 'nascimento_ou_casamento')
                                    <label for="{{$documento}}" style="color: black; font-weight: bolder;">Registro de Nascimento ou Certidão de Casamento. </label>
                                @elseif($documento == 'cpf')
                                    <label for="{{$documento}}" style="color: black; font-weight: bolder;"> Cadastro de Pessoa Física (CPF) - pode estar no RG. </label>
                                @elseif($documento == 'rg')
                                    <label for="{{$documento}}" style="color: black; font-weight: bolder;"> Carteira de Identidade (RG) - Frente e verso. </label>
                                @elseif($documento == 'quitacao_eleitoral')
                                    <label for="{{$documento}}" style="color: black; font-weight: bolder;">Comprovante de quitação com o Serviço Eleitoral no último turno de votação. </label>
                                @elseif($documento == 'quitacao_militar')
                                    <label for="{{$documento}}" style="color: black; font-weight: bolder;">Comprovante de quitação com o Serviço Militar, para candidatos do sexo masculino que tenham de 18 a 45 anos - Frente e verso. </label>
                                @elseif($documento == 'foto')
                                    <label for="{{$documento}}" style="color: black; font-weight: bolder;">Uma foto 3x4 atual. </label>
                                @elseif($documento == 'autodeclaracao')
                                    <label for="{{$documento}}" style="color: black; font-weight: bolder;">Autodeclaração de cor/etnia. </label>
                                @elseif($documento == 'comprovante_renda')
                                    <label for="{{$documento}}" style="color: black; font-weight: bolder;">Comprovante de renda, ou de que não possui renda, de cada membro do grupo familiar, seja maior ou menor de idade. </label>
                                @elseif($documento == 'laudo_medico')
                                    <label for="{{$documento}}" style="color: black; font-weight: bolder;">Laudo médico. </label>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
                <form method="post" action="{{route('inscricao.status.efetivado',['sisu_id' => $inscricao->chamada->sisu->id, 'chamada_id' => $inscricao->chamada->id, 'curso_id' => $inscricao->curso->id])}}">
                    @csrf
                    <input type="hidden" name="inscricaoID" value="{{$inscricao->id}}">
                    <input type="hidden" name="curso" value="{{$inscricao->curso->id}}">
                    <input type="hidden" name="efetivar" id="inputEfetivar" value="">
                    <div class="form-row justify-content-center">
                        <div class="col-md-12" style="text-align: center">
                            <button id="efetivarBotao1" type="submit" class="btn btn-success btn-cota" style="margin-top: 10px; width: 100%" onclick="atualizarInputEfetivar(false)" {{$inscricao->cd_efetivado == false ? 'disabled' : '' }}>{{$inscricao->cd_efetivado == true ? 'Desfazer efetivar' : 'Não efetivado' }}</button>
                            <button id="efetivarBotao2" type="submit" class="btn btn-primary btn-claro" style="margin-top: 10px; width: 100%" onclick="atualizarInputEfetivar(true)" {{$inscricao->cd_efetivado == true ? 'disabled' : '' }}>{{$inscricao->cd_efetivado == true ? 'Efetivato' : 'Efetivar' }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{--<div class="container" style="padding-top: 5rem; padding-bottom: 8rem;">
        <div class="form-row justify-content-center">
            <div class="col-md-10">
                <div class="card" style="width: 100%;">
                    <div class="card-body">
                        <div class="form-row">
                            <div class="col-md-8">
                                <h5 class="card-title">Análise de documentos</h5>
                                <h6 class="card-subtitle mb-2 text-muted">Candidados > Analisar documentação</h6>
                            </div>
                        </div>
                        @error('error')
                            <div class="alert alert-danger" role="alert">
                                {{$message}}
                            </div>
                        @enderror
                        @if(session('success'))
                            <div class="col-md-12" style="margin-top: 5px;">
                                <div class="alert alert-success" role="alert">
                                    <p>{{session('success')}}</p>
                                </div>
                            </div>
                        @endif
                        <form method="POST" id="analisar-documentos" action="{{route('inscricao.analisar.documentos', $inscricao->id)}}">
                            <input type="hidden" name="inscricao_id" value="{{$inscricao->id}}">
                            <input type="hidden" name="curso_id" value="{{$curso->id}}">
                            <input type="hidden" name="chamada_id" value="{{$chamada->id}}">
                            <input type="hidden" name="sisu_id" value="{{$chamada->sisu->id}}">
                            @csrf
                            <table class="table">
                                <tbody>
                                    @foreach ($documentos as $documento)
                                        <tr>
                                            <td>
                                                <div class="form-row">
                                                    @if($documento == 'certificado_conclusao')
                                                        <label for="{{$documento}}" style="color: black; font-weight: bolder;"><span style="color: red; font-weight: bold;">*</span> Certificado de Conclusão do Ensino Médio ou Certidão de Exame Supletivo do Ensino Médio ou Certificação de Ensino Médio através do ENEM ou documento equivalente. </label>
                                                    @elseif($documento == 'historico')
                                                        <label for="{{$documento}}" style="color: black; font-weight: bolder;"><span style="color: red; font-weight: bold;">*</span> Histórico Escolar do Ensino Médio ou equivalente. </label>
                                                    @elseif($documento == 'nascimento_ou_casamento')
                                                        <label for="{{$documento}}" style="color: black; font-weight: bolder;"><span style="color: red; font-weight: bold;">*</span> Registro de Nascimento ou Certidão de Casamento. </label>
                                                    @elseif($documento == 'cpf')
                                                        <label for="{{$documento}}" style="color: black; font-weight: bolder;"> Cadastro de Pessoa Física (CPF) - pode estar no RG. </label>
                                                    @elseif($documento == 'rg')
                                                        <label for="{{$documento}}" style="color: black; font-weight: bolder;"><span style="color: red; font-weight: bold;">*</span> Carteira de Identidade (RG) - Frente e verso. </label>
                                                    @elseif($documento == 'quitacao_eleitoral')
                                                        <label for="{{$documento}}" style="color: black; font-weight: bolder;"><span style="color: red; font-weight: bold;">*</span> Comprovante de quitação com o Serviço Eleitoral no último turno de votação. </label>
                                                    @elseif($documento == 'quitacao_militar')
                                                        <label for="{{$documento}}" style="color: black; font-weight: bolder;"><span style="color: red; font-weight: bold;">*</span> Comprovante de quitação com o Serviço Militar, para candidatos do sexo masculino que tenham de 18 a 45 anos - Frente e verso. </label>
                                                    @elseif($documento == 'foto')
                                                        <label for="{{$documento}}" style="color: black; font-weight: bolder;"><span style="color: red; font-weight: bold;">*</span> Uma foto 3x4 atual. </label>
                                                    @elseif($documento == 'autodeclaracao')
                                                        <label for="{{$documento}}" style="color: black; font-weight: bolder;"><span style="color: red; font-weight: bold;">*</span> Autodeclaração de cor/etnia. </label>
                                                    @elseif($documento == 'comprovante_renda')
                                                        <label for="{{$documento}}" style="color: black; font-weight: bolder;"><span style="color: red; font-weight: bold;">*</span> Comprovante de renda, ou de que não possui renda, de cada membro do grupo familiar, seja maior ou menor de idade. </label>
                                                    @elseif($documento == 'laudo_medico')
                                                        <label for="{{$documento}}" style="color: black; font-weight: bolder;"><span style="color: red; font-weight: bold;">*</span> Laudo médico. </label>
                                                    @endif
                                                    @if($inscricao->arquivos()->where('nome', $documento)->first() != null) <a href="{{route('inscricao.arquivo', ['inscricao_id' => $inscricao->id, 'documento_nome' => $documento])}}" target="_blank"><img src="{{asset('img/file-pdf-solid.svg')}}" alt="arquivo atual" style="width: 16px;"></a> @endif
                                                </div>

                                                <div class="form-group">
                                                    @if ($documento != 'cpf')
                                                        <small><span style="color: red; font-weight: bold;">*</span> avalie o documento: </small><br>
                                                    @else
                                                        <small>avalie o documento: </small><br>
                                                    @endif
                                                    <label for="aceito">{{ __('aceito') }}</label>
                                                    <input type="radio" name="analise_{{$documento}}" value="aceito" @if ($documento != 'cpf') required @endif @if(old('analise_{{$documento}}') || (($inscricao->arquivos()->where('nome', $documento)->first() != null && $inscricao->arquivos()->where('nome', $documento)->first()->avaliacao != null && $inscricao->arquivos()->where('nome', $documento)->first()->avaliacao->avaliacao == \App\Models\Avaliacao::AVALIACAO_ENUM['aceito']))) checked @endif>

                                                    <label for="recusado">{{ __('recusado') }}</label>
                                                    <input type="radio" name="analise_{{$documento}}" value="recusado" @if ($documento != 'cpf') required @endif @if(old('analise_{{$documento}}') || (($inscricao->arquivos()->where('nome', $documento)->first() != null && $inscricao->arquivos()->where('nome', $documento)->first()->avaliacao != null && $inscricao->arquivos()->where('nome', $documento)->first()->avaliacao->avaliacao == \App\Models\Avaliacao::AVALIACAO_ENUM['recusado']))) checked @endif>
                                                    <input type="hidden" name="documentos[]" value="{{$documento}}">
                                                </div>

                                                <div class="form-group">
                                                    <label for="comentario_{{$documento}}">{{ __('Comentário') }}</label>
                                                    <textarea id="comentario_{{$documento}}" class="form-control @error('comentario'.$documento) is-invalid @enderror" type="text" name="comentario_{{$documento}}" autofocus autocomplete="comentario_{{$documento}}">@if(old('comentario_'.$documento)!=null){{old('comentario_'.$documento)}}@else @if(($inscricao->arquivos()->where('nome', $documento)->first() != null && $inscricao->arquivos()->where('nome', $documento)->first()->avaliacao != null)){{$inscricao->arquivos()->where('nome', $documento)->first()->avaliacao->comentario}}@endif @endif</textarea>

                                                    @error('comentario_{{$documento}}')
                                                        <div id="validationServer03Feedback" class="invalid-feedback">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </form>
                        <div class="card-footer">
                            <div class="form-row justify-content-center">
                                <div class="col-md-6" style="text-align: right">
                                    <button type="submit" class="btn btn-success" form="analisar-documentos" style="width: 100%">Enviar análise</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>--}}
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
        var $iFrame = $('iframe');
        $.ajax({
            url:"{{route('inscricao.documento.ajax')}}",
            type:"get",
            data: {"inscricao_id": inscricao_id, "documento_nome": documento_nome},
            dataType:'json',
            success: function(documento) {
                if(documento.id == null){
                    if($("#mensagemVazia").is(":hidden")){
                        $("#mensagemVazia").show();
                    }
                }else{
                    $iFrame.attr('src', documento.caminho);
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

    $("#textoComent").click(function() {
        if($("#divComent").is(":hidden")){
            $("#divComent").show();
        }else{
            $("#divComent").hide();
        }
    });
</script>
