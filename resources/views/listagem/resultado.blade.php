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
            margin-bottom: 10px;
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
            top: 20px;
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
            RELAÇÃO DOS CANDIDATOS VALIDADOS ({{$chamada->nome}} - {{$chamada->sisu->edicao}})<br>
        </span>
    </div>
    <div>
        @foreach ($collect_inscricoes as $i => $collect)
            @if ($collect->count() > 0)
                @foreach ($collect as $j => $inscricoes)
                    <h3 class="subtitulo">Curso: {{$inscricoes[0]->curso->nome}} - @switch($inscricoes[0]->curso->turno)
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
                    <div class="body">
                        <div id="modalidade">
                            <h4 class="acao_afirmativa" style="position: relative; left: 10px; right: 10px;">@if($inscricoes[0]->no_modalidade_concorrencia == "que tenham cursado integralmente o ensino médio em qualquer uma das escolas situadas nas microrregiões do Agreste ou do Sertão de Pernambuco." ||
                                $inscricoes[0]->no_modalidade_concorrencia == "AMPLA CONCORRÊNCIA" || $inscricoes[0]->no_modalidade_concorrencia == "Ampla concorrência") Ampla concorrência / Ação afirmativa @else Ação afirmativa: {{$inscricoes[0]->cota->cod_cota}} - {{$inscricoes[0]->no_modalidade_concorrencia}} @endif</h4>
                            <table>
                                <thead>
                                    <tr class="esquerda">
                                        <th>Seq.</th>
                                        <th>AF</th>
                                        <th>CPF</th>
                                        <th>NOME</th>
                                        <th>RESULTADO DA ANÁLISE DOCUMENTAL</th>
                                        <th>JUSTIFICATIVA</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($inscricoes as $k =>  $inscricao)
                                        <tr class="@if($k % 2 == 0)back-color-1 @else back-color-2 @endif">
                                            <th>{{$k+1}}</th>
                                            <th>{{$inscricao->cota->cod_cota}}</th>
                                            <th>{{$inscricao->candidato->getCpfPDF()}}</th>
                                            <th class="esquerda">{{$inscricao->candidato->user->name}}</th>
                                            @if($inscricao->cd_efetivado == \App\Models\Inscricao::STATUS_VALIDACAO_CANDIDATO['cadastro_validado'])
                                                <th>CADASTRO VALIDADO</th>
                                                <th>-</th>
                                            @else
                                                <th>CADASTRO INVALIDADO</th>
                                                <th>{{$inscricao->justificativa}}</th>
                                            @endif

                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @if ($i != $collect_inscricoes->count() - 1)
                    <br/><div class="quebrar_pagina"></div>
                    @else
                        @if ($j != $collect->count() - 1)
                        <br/><div class="quebrar_pagina"></div>
                        @endif
                    @endif
                @endforeach
            @endif
        @endforeach
    </div>
</body>
</html>
