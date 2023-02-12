<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Listagem</title>
    <style type="text/css">
        @page {
            margin: 0px;
        }
        body {
            margin-right: 50px;
            margin-left: 50px;
            margin-bottom: 80px;
            margin-top: 6.4cm;
        }

        header {
            text-align: center;
            position: fixed;
            top: 0cm;
            left: 0cm;
            right: 0cm;
            height: 4.5cm;
        }
        .titulo {
            position: relative;
            top: -20px;
            font-size: 12px;
            font-weight: bolder;
            color: #393c47;
            font-family: 'Times New Roman', Times, serif;
        }
        .subtitulo {
            font-weight: normal;
            position: fixed;
            top: 185px;
            font-size: 11px;
            color: #393c47;
            font-family: 'Times New Roman', Times, serif;
            left: 50%;
            transform: translateX(-50%);
        }
        .quebrar_pagina {
            page-break-after: always;
        }
        table{
            margin-top: 10px;
            padding-bottom: 0px;
            border-collapse: collapse;
            width: 100%;
            position: relative;
        }
        table th {
            font-weight: 100;
            font-size: 10px;
        }
        table thead {
            background-color: #393c47;
            color: white;
        }
        tr {
            border: none;
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
            background-color: #d3d5dc;
        }
        .acao_afirmativa {
            font-size: 12px;
            text-align: justify;
            margin: 12px;
            position: relative;
            left: 5px;
        }
    </style>

</head>
<body>
    <header>
        <img src="{{public_path('img/cabeçalhoSISU5.fw.png')}}" width="100%" alt="">
    </header>
    <div>
        @php
            $semestre = "indefinido";
        @endphp
        @foreach ($candidatosIngressantesCursos as $i => $curso)
            @if($curso->count() <= 40)
                @php
                    $exibirNomeCurso = true;
                @endphp
                @if($exibirNomeCurso)
                    @php
                        $inscricao = App\Models\Inscricao::find($curso->first()['id']);
                    @endphp
                    <h3 class="subtitulo" style="text-align: center">
                        <span style="font-weight: bold; word-break: keep-all" >
                            RELAÇÃO DOS CANDIDATOS INGRESSANTES - CADASTRO EFETIVADO
                        </span><br>
                        <span style="font-weight: bold;">
                            @if(!is_null($inscricao->curso->semestre))
                                Semestre de ingresso: {{$chamada->sisu->edicao}}.{{$inscricao->curso->semestre}}
                                @php
                                    $semestre = "indefinido";
                                @endphp
                            @elseif($semestre == "indefinido")
                                Semestre de ingresso: {{$chamada->sisu->edicao}}.1
                                @php
                                    $semestre = "1";
                                @endphp
                            @elseif($semestre == "1")
                                Semestre de ingresso: {{$chamada->sisu->edicao}}.2
                                @php
                                    $semestre = "indefinido";
                                @endphp
                            @endif
                        </span><br>
                        <span>
                            Curso: {{$inscricao->curso->nome}} - @switch($inscricao->curso->turno)
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
                            @endswitch
                        </span>
                    </h3>
                    @php
                        $exibirNomeCurso = false;
                    @endphp
                @endif
                <div class="body">
                    <div id="modalidade" style="page-break-inside: avoid;">
                        <table>
                            <thead>
                                <tr class="esquerda">
                                    <th>Seq.</th>
                                    <th>CPF</th>
                                    <th>Cota de classificação</th>
                                    <th>Cota de inscrição</th>
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
                                    @php
                                        $ocupada = $inscricao['cota_vaga_ocupada_id'];
                                        $inscricao = App\Models\Inscricao::find($inscricao['id']);
                                        $inscricao->cota_vaga_ocupada_id = $ocupada;
                                    @endphp
                                    <tr class="@if($k % 2 == 0)back-color-1 @else back-color-2 @endif">
                                        <th>{{$k+1}}</th>
                                        <th>{{$inscricao->candidato->getCpfPDF()}}</th>
                                        <th>{{$inscricao->cotaRemanejada->cod_cota}}</th>
                                        <th>{{$inscricao->cota->cod_cota}}</th>
                                        <th class="esquerda">{{$inscricao->candidato->no_inscrito}}</th>
                                        <th>MATRICULADO</th>
                                        <th>{{$inscricao->nu_nota_candidato}}</th>
                                    </tr>
                                    @php
                                        $k += 1;
                                    @endphp
                                @endforeach
                            </tbody>
                        </table>
                    </div>
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
                @php
                    $inscricao = App\Models\Inscricao::find($curso->first()['id']);
                @endphp
                <h3 class="subtitulo" style="text-align: center; top: 185px;">
                    <span style="font-weight: bold;">
                        RELAÇÃO DE CANDIDATOS - CADASTRO RESERVA (SUPLENTES)
                    </span><br>
                    <span>
                        Curso: {{$inscricao->curso->nome}} - @switch($inscricao->curso->turno)
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
                        @endswitch
                    </span>
                </h3>
                @php
                    $exibirNomeCurso = false;
                @endphp
            @endif
            <div class="body">
                <div id="modalidade" style="page-break-inside: avoid;">
                    <table>
                        <thead>
                            <tr class="esquerda">
                                <th>Seq.</th>
                                <th>CPF</th>
                                <th>Cota de inscrição</th>
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
                                @php
                                    $ocupada = $inscricao['cota_vaga_ocupada_id'];
                                    $inscricao = App\Models\Inscricao::find($inscricao['id']);
                                    $inscricao->cota_vaga_ocupada_id = $ocupada;
                                @endphp
                                <tr class="@if($k % 2 == 0)back-color-1 @else back-color-2 @endif">
                                    <th>{{$k+1}}</th>
                                    <th>{{$inscricao->candidato->getCpfPDF()}}</th>
                                    <th>{{$inscricao->cota->cod_cota}}</th>
                                    <th class="esquerda">{{$inscricao->candidato->no_inscrito}}</th>
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
            </div>
            <br/><div class="quebrar_pagina"></div>
        @endforeach
    </div>
</body>
</html>
