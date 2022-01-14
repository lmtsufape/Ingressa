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
            RELAÇÃO DOS CANDIDATOS COM PENDÊNCIAS <span style="text-transform:uppercase">{{$chamada->nome}} - {{$chamada->sisu->edicao}}</span><br>
        </span>
        <span id="head-span-rigth">
            DATA: {{date('d/m/Y', strtotime(today()))}}<br>
            PAG:
        </span>
    </div>
    <div id="body">
        @foreach ($collect_inscricoes as $i => $collect)
            @php
                $exibirNomeCurso = true;
            @endphp
            @if ($collect->count() > 0)
                @foreach ($collect as $j => $inscricoes)
                    @if ($exibirNomeCurso)
                        <h3>Curso: {{$inscricoes[0]->curso->nome}} - @switch($inscricoes[0]->curso->turno)
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
                        <h4 style="position: relative; left: 10px; right: 10px;">@if($inscricoes[0]->no_modalidade_concorrencia == 'que tenham cursado integralmente o ensino médio em qualquer uma das escolas situadas nas microrregiões do Agreste ou do Sertão de Pernambuco.' ||
                        $inscricoes[0]->no_modalidade_concorrencia == 'AMPLA CONCORRÊNCIA' || $inscricoes[0]->no_modalidade_concorrencia == 'Ampla concorrência') Ampla concorrência / Ação afirmativa @else Ação afirmativa: {{$inscricoes[0]->cota->cod_cota}} - {{$inscricoes[0]->no_modalidade_concorrencia}} @endif</h4>
                        <table>
                            <thead>
                                <tr class="esquerda">
                                    <th>AF</th>
                                    <th>CPF</th>
                                    <th>Nome</th>
                                    <th>Pendênciais documentais</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($inscricoes as $k =>  $inscricao)
                                    <tr class="@if($k % 2 == 0)back-color-1 @else back-color-2 @endif">
                                        <th>{{$inscricao->cota->cod_cota}}</th>
                                        <th>{{$inscricao->candidato->getCpfPDF()}}</th>
                                        <th class="esquerda">{{$inscricao->candidato->user->name}}</th>
                                        <th>
                                            @switch($inscricao->cd_efetivado)
                                                @case(\App\Models\Inscricao::STATUS_VALIDACAO_CANDIDATO['cadastro_validado'])
                                                    Sem pendências
                                                    @break
                                                @case(\App\Models\Inscricao::STATUS_VALIDACAO_CANDIDATO['cadastro_invalidado_confirmacao'])
                                                @case(\App\Models\Inscricao::STATUS_VALIDACAO_CANDIDATO['cadastro_invalidado'])
                                                    @php
                                                        $arquivos_invalidos = 0;
                                                        foreach ($inscricao->arquivos as $arquivo) {
                                                            if ($arquivo->avaliacao != null && $arquivo->avaliacao->avaliacao == \App\Models\Avaliacao::AVALIACAO_ENUM['recusado']) {
                                                                $arquivos_invalidos++;
                                                            }
                                                        }
                                                    @endphp
                                                    <div style="font-weight: normal;">
                                                        {!!str_replace(['<p>', '</p>'], "",$inscricao->justificativa)!!};
                                                        @foreach ($inscricao->arquivos as $i => $arquivo)
                                                            @if($arquivo->avaliacao != null && $arquivo->avaliacao->avaliacao == \App\Models\Avaliacao::AVALIACAO_ENUM['recusado'])
                                                                @if($i == $arquivos_invalidos)
                                                                    {!!str_replace(['<p>', '</p>'], "", $arquivo->avaliacao->comentario)!!}.
                                                                @else
                                                                    {!!str_replace(['<p>', '</p>'], "", $arquivo->avaliacao->comentario)!!};
                                                                @endif
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                    @break
                                                @default
                                                    Não enviado
                                                    @break
                                            @endswitch
                                        </th>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endforeach

            @endif
        @endforeach
    </div>
</body>
</html>
