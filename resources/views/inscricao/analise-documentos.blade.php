<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Analisar documentação') }}
        </h2>
    </x-slot>
    <div class="container" style="padding-top: 5rem; padding-bottom: 8rem;">
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
                                <div class="col-md-6"></div>
                                    <div class="col-md-6" style="text-align: right">
                                        <button type="submit" class="btn btn-success" form="analisar-documentos" style="width: 100%">Enviar análise</button>
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
