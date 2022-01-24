<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Listagem</title>
    <style type="text/css">
        @page {
            margin: 120px 50px 80px 50px;
        }
        #head {
            background-repeat: no-repeat;
            text-align: center;
            width: 100%;
            position: fixed;
            top: -120px;
            left: 0px;
            right: -15px;
        }

        #head img {
            width: 115%;
        }

        .titulo {
            position: relative;
            top: -70px;
            font-size: 18px;
            font-weight: bolder;
            color: #03284d;
        }

        .subtitulo {
            font-weight: normal;
            position: relative;
            font-size: 18px;
            color: #03284d;
            text-align: center;
            margin-bottom: 8px;
        }

        .quebrar_pagina {
            page-break-after: always;
        }

        #corpo{
            float: left;
            width: 600px;
            position: relative;
            margin: auto;
        }
        table{
            border-collapse: collapse;
            width: 100%;
            position: relative;
        }
        table th {
            font-weight: 100;
            font-size: 15px;
        }
        table thead {
            border-top: 1px solid rgb(126, 126, 126);
            border-bottom: 1px solid rgb(126, 126, 126);
            background-color: #021c35;
            color: white;
        }
        #footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: right;
            border-top: 1px solid gray;
        }
        #footer .page:after{
            content: counter(page);
        }
        #modalidade {
            border: solid 1px rgb(126, 126, 126);
            border-radius: 5px;
            margin-top: 10px;
            margin-bottom: 10px;
            padding-bottom: 10px;
        }
        .esquerda {
            text-align: left;
            float: left;
        }

        .back-color-1 {
            background-color: white;
        }

        .back-color-2 {
            background-color: #d1e7fd;
        }

        .body {
            position: relative;
            top: 15px;
        }

        .acao_afirmativa {
            text-align: justify;
            margin: 15px;
            position: relative; 
            left: 5px;
        }
    </style>

</head>
<body>
    <div id="head">        
        <img src="{{asset('img/cabecalho_listagem.png')}}" width="100%" alt="">
        <span class="titulo">
            RELAÇÃO DOS CANDIDATOS INGRESSANTES {{$chamada->sisu->edicao}}<br>
        </span>
    </div>
    <div id="body">
        @php
            $semestre = "indefinido";
        @endphp
        @foreach ($candidatosIngressantesCursos as $i => $curso)
            @if($curso->count() <= 40)
                @php
                    $exibirNomeCurso = true;
                @endphp
                @if($exibirNomeCurso)
                    <h3 class="subtitulo">Curso: {{$curso->first()->curso->nome}} - @switch($curso->first()->curso->turno)
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
                        @endswitch @if(!is_null($curso->first()->curso->semestre))
                                        <span style="text-align: right;">(Ingressantes de {{$chamada->sisu->edicao}}/{{$curso->first()->curso->semestre}})
                                        </span>
                                        @php
                                            $semestre = "indefinido";
                                        @endphp
                                    @elseif($semestre == "indefinido")
                                        <span style="text-align: right;">(Ingressantes de {{$chamada->sisu->edicao}}/1)
                                        </span>
                                        @php
                                            $semestre = "1";
                                        @endphp
                                    @elseif($semestre == "1")
                                        <span style="text-align: right;">(Ingressantes de {{$chamada->sisu->edicao}}/2)
                                        </span>
                                        @php
                                            $semestre = "indefinido";
                                        @endphp
                                    @endif</h3>
                    
                    @php
                        $exibirNomeCurso = false;
                    @endphp
                @endif
                <div id="modalidade">
                    <table>
                        <thead>
                            <tr class="esquerda">
                                <th>Seq.</th>
                                <th>CPF</th>
                                <th>AF</th>
                                <th>Nome</th>
                                <th>Nota</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $k = 0;
                            @endphp
                            @foreach ($curso as $inscricao)
                                <tr class="@if($k % 2 == 0)back-color-1 @else back-color-2 @endif">
                                    <th>{{$k+1}}</th>
                                    <th>{{$inscricao->candidato->getCpfPDF()}}</th>
                                    <th>{{$inscricao->cota->cod_cota}}</th>
                                    <th>{{$inscricao->candidato->user->name}}</th>
                                    <th>{{$inscricao->nu_nota_candidato}}</th>
                                </tr>
                                @php
                                    $k += 1;
                                @endphp
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if ($i != $curso->count() - 1)
                    <br/><div class="quebrar_pagina"></div>
                @endif
            @endif
        @endforeach

        @foreach ($candidatosReservaCursos as $i => $curso)
            @php
                $exibirNomeCurso = true;
            @endphp
            @if($exibirNomeCurso)
                <h3>Curso: {{$curso->first()->curso->nome}} - @switch($curso->first()->curso->turno)
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
                    @endswitch</h3>
                @php
                    $exibirNomeCurso = false;
                @endphp
            @endif
            <div id="modalidade">
                <table>
                    <thead>
                        <tr class="esquerda">
                            <th>Seq.</th>
                            <th>CPF</th>
                            <th>AF</th>
                            <th>Nome</th>
                            <th>Situação</th>
                            <th>Nota</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $k = 0;
                        @endphp
                        @foreach ($curso as $inscricao)
                            <tr class="@if($k % 2 == 0)back-color-1 @else back-color-2 @endif">
                                <th>{{$k+1}}</th>
                                <th>{{$inscricao->candidato->getCpfPDF()}}</th>
                                <th>{{$inscricao->cota->cod_cota}}</th>
                                <th>{{$inscricao->candidato->user->name}}</th>
                                <th>RESERVA</th>
                                <th>{{$inscricao->nu_nota_candidato}}</th>
                            </tr>
                            @php
                                $k += 1;
                            @endphp
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if ($i != $curso->count() - 1)
                <br/><div class="quebrar_pagina"></div>
            @endif
        @endforeach
    </div>
</body>
</html>
