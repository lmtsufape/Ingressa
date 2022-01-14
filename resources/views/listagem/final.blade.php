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
        #head{
            font-size: 14px;
            text-align: left;
            width: 100%;
            height: auto;
            position: fixed;
            top: -80px;
            left: 0;
            right: 0;
        }
        #head img {
            display: inline-block;
            float: left;
        }
        #head #head-span-left {
            text-align: left;
            float: left;
            padding-top: 12px;
            margin-left: 10px;
            padding-bottom: 12px;
        }
        #head #head-span-rigth {
            float: right;
            text-align: right;
            padding-top: 12px;
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
        }
        table thead {
            border-top: 1px solid rgb(126, 126, 126);
            border-bottom: 1px solid rgb(126, 126, 126);
            background-color: rgb(71, 71, 71);
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
            background-color: rgb(235, 235, 235);
        }
        .back-color-2 {
            background-color: rgb(196, 196, 196);
        }
    </style>

</head>
<body>
    <div id="head">
        <img src="{{asset('img/logo_ufape_blue.png')}}" width="35px" alt="">
        <span id="head-span-left">
            UNIVERSIDADE FEDERAL DO AGRESTE DE PERNAMBUCO<br>
            PRÓ-REITORIA DE ENSINO DE GRADUAÇÃO<br>
            SETOR DE ESCOLARIDADE<br>
            RELAÇÃO DOS CANDIDATOS INGRESSANTES <span style="text-transform:uppercase">{{$chamada->sisu->edicao}}</span><br>
        </span>
        <span id="head-span-rigth">
            DATA: {{date('d/m/Y', strtotime(today()))}}<br>
            PAG:
        </span>
    </div>
    <div id="body">
        @php
            $semestre = "indefinido";
        @endphp
        @foreach ($candidatosIngressantesCursos as $curso)
            @if($curso->count() <= 40)
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
        @endforeach
    </div>
</body>
</html>
