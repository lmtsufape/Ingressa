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
            <div class="row">
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
                                                <td class="align-middle">{{$inscricao->candidato->getCpfPDF()}}</td>
                                                <td class="align-middle text-center">{{$inscricao->cotaClassificacao->cod_cota}}</td>
                                                <td class="align-middle text-center">{{$inscricao->cota->cod_cota}}</td>
                                                <td class="align-middle">{{$inscricao->candidato->no_inscrito}}</td>
                                                <td class="align-middle">MATRICULADO</td>
                                                <td class="align-middle">{{$inscricao->nu_nota_candidato}}</td>
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
                                                    <td class="align-middle">{{$inscricao->candidato->getCpfPDF()}}</td>
                                                    <td class="align-middle text-center">{{$inscricao->cotaClassificacao->cod_cota}}</td>
                                                    <td class="align-middle text-center">{{$inscricao->cota->cod_cota}}</td>
                                                    <td class="align-middle">{{$inscricao->candidato->no_inscrito}}</td>
                                                    <td class="align-middle">MATRICULADO</td>
                                                    <td class="align-middle">{{$inscricao->nu_nota_candidato}}</td>
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
                                            <td class="align-middle">{{$inscricao->candidato->getCpfPDF()}}</td>
                                            <td class="align-middle text-center">{{$inscricao->cotaClassificacao->cod_cota}}</td>
                                            <td class="align-middle text-center">{{$inscricao->cota->cod_cota}}</td>
                                            <td class="align-middle">{{$inscricao->candidato->no_inscrito}}</td>
                                            <td class="align-middle">MATRICULADO</td>
                                            <td class="align-middle">{{$inscricao->nu_nota_candidato}}</td>
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
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $k = 1;
                                @endphp
                                @foreach ($candidatosReserva as $inscricao)
                                    <tr>
                                        <th class="align-middle"> {{$k}}</th>
                                        <td class="align-middle">{{$inscricao->candidato->getCpfPDF()}}</td>
                                        <td class="align-middle text-center">{{$inscricao->cota->cod_cota}}</td>
                                        <td class="align-middle">{{$inscricao->candidato->no_inscrito}}</td>
                                        <td class="align-middle">RESERVA</td>
                                        <td class="align-middle">{{$inscricao->nu_nota_candidato}}</td>
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
</x-app-layout>