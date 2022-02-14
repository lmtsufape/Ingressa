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
            font-size: 16px;
            font-weight: bolder;
            color: #03284d;
        }
        .subtitulo {
            font-weight: normal;
            position: inherit;
            font-size: 16px;
            color: #03284d;
            text-align: center;
            margin: -18px;
            margin-bottom: 10px;
            padding: 0px;
        }
        .quebrar_pagina {
            page-break-after: always;
        }
        #body{
            position: relative;
            top: 60px;
        }
        table{
            margin-top: 10px;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-collapse: collapse;
            width: 100%;
            position: relative;
            border: solid 1px rgb(126, 126, 126);
            border-radius: 5px;
        }
        table th {
            font-weight: 100;
            font-size: 14px;
        }
        table thead {
            border-top: 1px solid rgb(126, 126, 126);
            border-bottom: 1px solid rgb(126, 126, 126);
            background-color: #021c35;
            color: white;
        }
        tr {
            border: none;
        }
        td {
            border-right: solid 1px #03284d;
            border-left: solid 1px #03284d;
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
        <img src="{{public_path('img/cabecalho_listagem.png')}}" width="100%" alt="">
        <span class="titulo">
            LISTA DE RESULTADO DA ETAPA DE ANÁLISE DOCUMENTAL<br><span style="font-weight: normal; text-transform:uppercase;" >{{$chamada->nome}}</span><br>
        </span>
    </div>
    <div id="body">
        @foreach ($collect_inscricoes as $i => $curso)
            @if ($curso->count() > 0)
                @php
                    $inscricao = App\Models\Inscricao::find($curso->first()['id']);
                @endphp
                <h3 class="subtitulo">Curso: {{$inscricao->curso->nome}} - @switch($inscricao->curso->turno)
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
                </h3>
                <div class="body">
                    <div id="modalidade">
                        <table>
                            <thead>
                                <tr class="esquerda">
                                    <th>Seq.</th>
                                    <th>CPF</th>
                                    <th>AF</th>
                                    <th>NOME</th>
                                    <th>RESULTADO</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($curso as $k =>  $inscricao)
                                    @php
                                        $inscricao = App\Models\Inscricao::find($inscricao['id']);
                                    @endphp
                                    <tr class="@if($k % 2 == 0)back-color-1 @else back-color-2 @endif">
                                        <th>{{$k+1}}</th>
                                        <th>{{$inscricao->candidato->getCpfPDF()}}</th>
                                        <th>{{$inscricao->cota->cod_cota}}</th>
                                        <th class="esquerda">{{$inscricao->candidato->user->name}}</th>
                                        @if($inscricao->cd_efetivado == \App\Models\Inscricao::STATUS_VALIDACAO_CANDIDATO['cadastro_validado'])
                                            <th>VALIDADO</th>
                                        @else
                                            <th>INVALIDADO<br>MOTIVO(S): {{$inscricao->justificativa}}</th>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <br/><div class="quebrar_pagina"></div>
            @endif
        @endforeach
    </div>
</body>
</html>
