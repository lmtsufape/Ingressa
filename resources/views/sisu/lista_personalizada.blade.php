<x-app-layout>
    <div class="fundo2 px-5">
        <div class="container">
            @if(session('error'))
                <div class="col-md-12">
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Success:"><use xlink:href="#check-circle-fill"/></svg>{{session('error')}}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            @endif
            @if(session('success'))
                <div class="col-md-12">
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Success:"><use xlink:href="#check-circle-fill"/></svg>{{session('success')}}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            @endif
            <div class="row tituloBorda justify-content-between">
                <div class="d-flex align-items-center justify-content-between mx-0 px-0">
                    <span class="align-middle titulo">Ingressantes e reservas do curso de {{$curso->nome}} @if($curso->semestre != null) - {{$curso->semestre}}ª entrada @endif</span>
                    <span class="aling-middle">
                        <a href="{{route('lista.personalizada', $sisu)}}" title="Voltar" style="cursor: pointer;"><img class="m-1 " width="40" src="{{asset('img/Grupo 1687.svg')}}" alt="Icone de voltar"></a>
                    </span>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-md-12 shadow-sm">
                    <div class="row justify-content-center">
                        <div class="col-md-12 cabecalhoCurso p-2 px-3 align-items-center" style="background-color: {{$curso->cor_padrao != null ? $curso->cor_padrao : 'black'}}">
                            <div class="row justify-content-between">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <img style="border:2px solid white; border-radius: 50%;"  src="{{asset('storage/'.$curso->icone)}}"
                                    alt="" width="45" class="img-flex">
                                    <div>
                                    <span class="tituloTabelas ps-1 mb-0 pb-0">{{$curso->nome}} - {{$turno}} - @if($curso->semestre != null) {{$curso->semestre}}ª entrada @else 1ª entrada @endif</span>
                                    </div>
                                </div>
                            </div>
                            </div>
                        </div>
                    </div>

                    <div class="row justify-content-center">
                        <div class="col-md-12 corpo p-2 px-3">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th class="text-center">CPF</th>
                                        <th class="text-center">Cota Classificação</th>
                                        <th class="text-center">Cota Inscricação</th>
                                        <th scope="col">Nome</th>
                                        <th class="text-center">Situação</th>
                                        <th class="text-center">Nota</th>
                                        <th class="text-center">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($candidatosIngressantes->count() <= 40)
                                        @php
                                            $k = 1;
                                        @endphp
                                        @foreach ($candidatosIngressantes as $inscricao)
                                            <tr>
                                                <th class="align-middle"> {{$k}}</th>
                                                <td class="align-middle">{{$inscricao->candidato->nu_cpf_inscrito}}</td>
                                                <td class="align-middle text-center">{{$inscricao->cotaClassificacao->cod_novo}}</td>
                                                <td class="align-middle text-center">{{$inscricao->cota->cod_novo}}</td>
                                                <td class="align-middle">{{$inscricao->candidato->no_inscrito}}</td>
                                                <td class="align-middle">MATRICULADO</td>
                                                <td class="align-middle">{{$inscricao->nu_nota_candidato}}</td>
                                                <td class="align-middle"><button class="btn botao my-2 py-1" data-bs-toggle="modal" data-bs-target="#modalEditarInscricao{{$inscricao->id}}"><span class="px-2">Editar</span></button></td>
                                            </tr>
                                            @php
                                                $k += 1;
                                            @endphp
                                        @endforeach
                                    @else
                                        @php
                                            $k = 1;
                                        @endphp
                                        @foreach ($candidatosIngressantes as $inscricao)
                                            @if($inscricao->semestre_entrada == 1)
                                                <tr>
                                                    <th class="align-middle">{{$k}}</th>
                                                    <td class="align-middle">{{$inscricao->candidato->nu_cpf_inscrito}}</td>
                                                    <td class="align-middle text-center">{{$inscricao->cotaClassificacao->cod_novo}}</td>
                                                    <td class="align-middle text-center">{{$inscricao->cota->cod_novo}}</td>
                                                    <td class="align-middle">{{$inscricao->candidato->no_inscrito}}</td>
                                                    <td class="align-middle">MATRICULADO</td>
                                                    <td class="align-middle">{{$inscricao->nu_nota_candidato}}</td>
                                                    <td class="align-middle"><button class="btn botao my-2 py-1" data-bs-toggle="modal" data-bs-target="#modalEditarInscricao{{$inscricao->id}}"><span class="px-2">Editar</span></button></td>
                                                </tr>
                                                @php
                                                    $k += 1;
                                                @endphp
                                            @endif
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            @if ($candidatosIngressantes->count() > 40)
                <div class="row mt-2 justify-content-center">
                    <div class="col-md-12 cabecalhoCurso p-2 px-3 align-items-center" style="background-color: {{$curso->cor_padrao != null ? $curso->cor_padrao : 'black'}}">
                    <div class="row justify-content-between">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                            <img style="border:2px solid white; border-radius: 50%;"  src="{{asset('storage/'.$curso->icone)}}"
                                alt="" width="45" class="img-flex">
                            <div>
                                <span class="tituloTabelas ps-1 mb-0 pb-0">{{$curso->nome}} - {{$turno}} - 2ª entrada</span>
                            </div>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>

                <div class="row justify-content-center">
                    <div class="col-md-12 corpo p-2 px-3">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th class="text-center">CPF</th>
                                    <th class="text-center">Cota Classificação</th>
                                    <th class="text-center">Cota Inscricação</th>
                                    <th scope="col">Nome</th>
                                    <th class="text-center">Situação</th>
                                    <th class="text-center">Nota</th>
                                    <th class="text-center">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $k = 1;
                                @endphp
                                @foreach ($candidatosIngressantes as $inscricao)
                                    @if($inscricao->semestre_entrada == 2)
                                        <tr>
                                            <th class="align-middle"> {{$k}}</th>
                                            <td class="align-middle">{{$inscricao->candidato->nu_cpf_inscrito}}</td>
                                            <td class="align-middle text-center">{{$inscricao->cotaClassificacao->cod_novo}}</td>
                                            <td class="align-middle text-center">{{$inscricao->cota->cod_novo}}</td>
                                            <td class="align-middle">{{$inscricao->candidato->no_inscrito}}</td>
                                            <td class="align-middle">MATRICULADO</td>
                                            <td class="align-middle">{{$inscricao->nu_nota_candidato}}</td>
                                            <td class="align-middle"><button class="btn botao my-2 py-1" data-bs-toggle="modal" data-bs-target="#modalEditarInscricao{{$inscricao->id}}"><span class="px-2">Editar</span></button></td>
                                        </tr>
                                        @php
                                            $k += 1;
                                        @endphp
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            @if ($candidatosReserva->count() > 0)
                <div class="row mt-2 justify-content-center">
                    <div class="col-md-12 cabecalhoCurso p-2 px-3 align-items-center" style="background-color: {{$curso->cor_padrao != null ? $curso->cor_padrao : 'black'}}">
                    <div class="row justify-content-between">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                            <img style="border:2px solid white; border-radius: 50%;"  src="{{asset('storage/'.$curso->icone)}}"
                                alt="" width="45" class="img-flex">
                            <div>
                                <span class="tituloTabelas ps-1 mb-0 pb-0">{{$curso->nome}} - {{$turno}} - Reserva</span>
                            </div>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>

                <div class="row justify-content-center">
                    <div class="col-md-12 corpo p-2 px-3">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th class="text-center">CPF</th>
                                    <th class="text-center">Cota Inscricação</th>
                                    <th scope="col">Nome</th>
                                    <th class="text-center">Situação</th>
                                    <th class="text-center">Nota</th>
                                    <th class="text-center">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $k = 1;
                                @endphp
                                @foreach ($candidatosReserva as $inscricao)
                                    <tr>
                                        <th class="align-middle"> {{$k}}</th>
                                        <td class="align-middle">{{$inscricao->candidato->nu_cpf_inscrito}}</td>
                                        <td class="align-middle text-center">{{$inscricao->cota->cod_novo}}</td>
                                        <td class="align-middle">{{$inscricao->candidato->no_inscrito}}</td>
                                        <td class="align-middle">RESERVA</td>
                                        <td class="align-middle">{{$inscricao->nu_nota_candidato}}</td>
                                        <td class="align-middle"><button class="btn botao my-2 py-1" data-bs-toggle="modal" data-bs-target="#modalEditarInscricao{{$inscricao->id}}"><span class="px-2">Editar</span></button></td>
                                    </tr>
                                    @php
                                        $k += 1;
                                    @endphp
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>
    @foreach ($candidatosIngressantes as $inscricao)
        <!-- Modal editar situção da inscricao -->
        <div class="modal fade" id="modalEditarInscricao{{$inscricao->id}}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-dialog">
                    <div class="modal-content modalFundo p-3">
                        <div class="col-md-12 tituloModal">Editar situação da inscrição de {{$inscricao->candidato->user->name}}</div>
                            <div class="pt-3 pb-2 textoModal">
                                <form method="POST" id="editar-situacao-inscricao-form-{{$inscricao->id}}" action="{{route('inscricao.situacao.update', $inscricao->id)}}">
                                    @csrf
                                    <input type="hidden" name="inscricao" value="{{$inscricao->id}}">
                                    <div class="form-row">
                                        <div class="col-md-12 form-group">
                                            <label class="pb-2" for="v">{{ __('Cota de classificação:') }}</label>
                                            <select name="cota_classificacao" id="cota_classificacao" class="form-control campoDeTexto @error('cota_classificacao') is-invalid @enderror">
                                                @foreach ($cotas as $cota)
                                                    <option @if(old('cota_classificacao') == $cota->id || $inscricao->cotaClassificacao->id == $cota->id) selected @endif value="{{$cota->id}}">{{$cota->cod_novo}}</option>
                                                @endforeach
                                            </select>
            
                                            @error('cota_classificacao')
                                                <div id="validationServer03Feedback" class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    @if($curso->semestre == null)
                                    <div class="form-row">
                                        <div class="col-md-12 form-group">
                                            <label class="pb-2" for="v">{{ __('Semestre de entrada:') }}</label>
                                            <select name="semestre" id="semestre" class="form-control campoDeTexto @error('semestre') is-invalid @enderror">
                                                <option @if(old('semestre') == 1 || $inscricao->semestre_entrada == 1) selected @endif value="1">1ª entrada</option>
                                                <option @if(old('semestre') == 2 || $inscricao->semestre_entrada == 2) selected @endif value="2">2ª entrada</option>
                                                <option @if($inscricao->semestre_entrada == null) selected @endif value={{null}}>Reserva</option>
                                            </select>
            
                                            @error('semestre')
                                                <div id="validationServer03Feedback" class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    @else
                                    <div class="form-row">
                                        <div class="col-md-12 form-group">
                                            <label class="pb-2" for="v">{{ __('Semestre de entrada:') }}</label>
                                            <select name="semestre" id="semestre" class="form-control campoDeTexto @error('semestre') is-invalid @enderror">
                                                @if($curso->semestre == 1)
                                                <option @if(old('semestre') == 1 || $inscricao->semestre_entrada == 1) selected @endif value="1">1ª entrada</option>
                                                @else
                                                <option @if(old('semestre') == 2 || $inscricao->semestre_entrada == 2) selected @endif value="2">2ª entrada</option>
                                                @endif
                                                <option @if($inscricao->semestre_entrada == null) selected @endif value={{null}}>Reserva</option>
                                            </select>
            
                                            @error('semestre')
                                                <div id="validationServer03Feedback" class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    @endif
                                </form>
                                <div class="row justify-content-between mt-4">
                                    <div class="col-md-3">
                                        <button type="button" class="btn botao my-2 py-1" data-bs-dismiss="modal"><span class="px-4">Cancelar</span></button>
                                    </div>
                                    <div class="col-md-4">
                                        <button type="submit" class="btn botaoVerde my-2 py-1 submeterFormBotao" form="editar-situacao-inscricao-form-{{$inscricao->id}}"><span class="px-4">Editar</span></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    @foreach ($candidatosReserva as $inscricao)
        <!-- Modal editar situção da inscricao -->
        <div class="modal fade" id="modalEditarInscricao{{$inscricao->id}}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-dialog">
                    <div class="modal-content modalFundo p-3">
                        <div class="col-md-12 tituloModal">Editar situação da inscrição de {{$inscricao->candidato->user->name}}</div>
                            <div class="pt-3 pb-2 textoModal">
                                <form method="POST" id="editar-situacao-inscricao-form-{{$inscricao->id}}" action="{{route('inscricao.situacao.update', $inscricao->id)}}">
                                    @csrf
                                    <input type="hidden" name="inscricao" value="{{$inscricao->id}}">
                                    <div class="form-row">
                                        <div class="col-md-12 form-group">
                                            <label class="pb-2" for="v">{{ __('Cota de classificação:') }}</label>
                                            <select name="cota_classificacao" id="cota_classificacao" class="form-control campoDeTexto @error('cota_classificacao') is-invalid @enderror">
                                                <option value="" selected disabled>-- Selecione a cota de classificação --</option>
                                                @foreach ($cotas as $cota)
                                                    <option @if(old('cota_classificacao') == $cota->id) selected @endif value="{{$cota->id}}">{{$cota->cod_novo}}</option>
                                                @endforeach
                                            </select>
            
                                            @error('cota_classificacao')
                                                <div id="validationServer03Feedback" class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    @if($curso->semestre == null)
                                    <div class="form-row">
                                        <div class="col-md-12 form-group">
                                            <label class="pb-2" for="v">{{ __('Semestre de entrada:') }}</label>
                                            <select name="semestre" id="semestre" class="form-control campoDeTexto @error('semestre') is-invalid @enderror">
                                                <option @if(old('semestre') == 1 || $inscricao->semestre_entrada == 1) selected @endif value="1">1ª entrada</option>
                                                <option @if(old('semestre') == 2 || $inscricao->semestre_entrada == 2) selected @endif value="2">2ª entrada</option>
                                                <option @if($inscricao->semestre_entrada == null) selected @endif value={{null}}>Reserva</option>
                                            </select>
            
                                            @error('semestre')
                                                <div id="validationServer03Feedback" class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    @else
                                    <div class="form-row">
                                        <div class="col-md-12 form-group">
                                            <label class="pb-2" for="v">{{ __('Semestre de entrada:') }}</label>
                                            <select name="semestre" id="semestre" class="form-control campoDeTexto @error('semestre') is-invalid @enderror">
                                                @if($curso->semestre == 1)
                                                <option @if(old('semestre') == 1 || $inscricao->semestre_entrada == 1) selected @endif value="1">1ª entrada</option>
                                                @else
                                                <option @if(old('semestre') == 2 || $inscricao->semestre_entrada == 2) selected @endif value="2">2ª entrada</option>
                                                @endif
                                                <option @if($inscricao->semestre_entrada == null) selected @endif value={{null}}>Reserva</option>
                                            </select>
            
                                            @error('semestre')
                                                <div id="validationServer03Feedback" class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    @endif
                                </form>
                                <div class="row justify-content-between mt-4">
                                    <div class="col-md-3">
                                        <button type="button" class="btn botao my-2 py-1" data-bs-dismiss="modal"><span class="px-4">Cancelar</span></button>
                                    </div>
                                    <div class="col-md-4">
                                        <button type="submit" class="btn botaoVerde my-2 py-1 submeterFormBotao" form="editar-situacao-inscricao-form-{{$inscricao->id}}"><span class="px-4">Editar</span></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</x-app-layout>