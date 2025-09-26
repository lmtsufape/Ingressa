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
            font-size: 12px;
            font-weight: bolder;
            color: #393c47;
            font-family: 'Times New Roman', Times, serif;
        }

        .subtitulo {
            font-weight: normal;
            position: inherit;
            font-size: 12px;
            color: #393c47;
            font-family: 'Times New Roman', Times, serif;
            text-align: center;
            margin: -18px;
            margin-bottom: 10px;
            padding: 0px;
        }

        .quebrar_pagina {
            page-break-after: always;
        }

        #body {
            position: relative;
            top: 60px;
        }

        table {
            margin-top: 10px;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-collapse: collapse;
            width: 100%;
            position: relative;
        }

        table th {
            font-weight: 100;
            font-size: 12px;
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

        #footer .page:after {
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

        .body {}

        .acao_afirmativa {
            text-align: justify;
            margin: 15px;
            position: relative;
            left: 5px;
        }

        a {
            word-break: break-word;
        }
    </style>

</head>

<body style="font-family: Arial, Helvetica, sans-serif;">
    <div id="head">
        <img src="{{ public_path('img/cabeçalhoSISU5.fw.png') }}" width="100%" alt="">
    </div>
    <div id="body">
        @foreach ($collect_inscricoes as $i => $curso)
            @if ($curso->count() > 0)
                @php
                    $inscricao = App\Models\Inscricao::find($curso->first()['id']);
                @endphp
                <h3 class="subtitulo" style="font-weight: bolder;">
                    RESULTADO DA ETAPA DE ANÁLISE DOCUMENTAL<br><span
                        style="font-weight: normal; text-transform:uppercase;">{{ $chamada->nome }}</span><br>
                    <span style="font-weight: normal;">Curso: {{ $inscricao->curso->nome }} -
                        @switch($inscricao->curso->turno)
                            @case(App\Models\Curso::TURNO_ENUM['Matutino'])
                                Matutino
                            @break

                            @case(App\Models\Curso::TURNO_ENUM['Vespertino'])
                                Vespertino
                            @break

                            @case(App\Models\Curso::TURNO_ENUM['Noturno'])
                                Noturno
                            @break

                            @case(App\Models\Curso::TURNO_ENUM['Integral'])
                                Integral
                            @break
                        @endswitch
                    </span>
                </h3>
                <div class="body">
                    <div id="modalidade">
                        <table>
                            <thead>
                                <tr class="esquerda">
                                    <th>Seq.</th>
                                    <th>CPF</th>
                                    <th>AF</th>
                                    <th>Nome</th>
                                    <th>Pendênciais<br>documentais</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $cont = 1;
                                @endphp
                                @foreach ($curso as $k => $inscricao)
                                    @php
                                        $inscricao = App\Models\Inscricao::find($inscricao['id']);
                                    @endphp
                                    <tr
                                        class="@if ($k % 2 == 0) back-color-1 @else back-color-2 @endif">
                                        <th>{{ $cont }}</th>
                                        <th>{{ $inscricao->candidato->getCpfPDF() }}</th>
                                        <th>{{ $inscricao->cota->cod_novo }}</th>
                                        <th class="esquerda">{{ !empty($inscricao->candidato->no_social) ? $inscricao->candidato->no_social : $inscricao->candidato->no_inscrito}}</th>
                                        <th>
                                            @if (
                                                $inscricao->status == \App\Models\Inscricao::STATUS_ENUM['documentos_aceitos_com_pendencias'] ||
                                                    $inscricao->status == \App\Models\Inscricao::STATUS_ENUM['documentos_aceitos_sem_pendencias']
                                            )
                                                Sem pendências
                                            @elseif($inscricao->status == \App\Models\Inscricao::STATUS_ENUM['documentos_pendentes'])
                                                Não enviado
                                            @elseif($inscricao->status == \App\Models\Inscricao::STATUS_ENUM['documentos_invalidados'])
                                                <div style="font-weight: normal;">
                                                    @foreach ($inscricao->arquivos as $i => $arquivo)
                                                        @if (
                                                            $arquivo->avaliacao != null &&
                                                                $arquivo->avaliacao->comentario != null &&
                                                                $arquivo->avaliacao->avaliacao == \App\Models\Avaliacao::AVALIACAO_ENUM['recusado'] &&
                                                                ($arquivo->nome != 'laudo_medico' && $arquivo->nome != 'fotografia' && $arquivo->nome != 'heteroidentificacao'))
                                                            <span
                                                                style="font-weight: bold">{{ $arquivo->getNomeDoc() }}</span>:
                                                            {!! str_replace(['<p>', '</p>'], '', $arquivo->avaliacao->comentario) !!}.<br>
                                                        @endif
                                                    @endforeach
                                                    @if ($inscricao->arquivos()->where('nome', 'heteroidentificacao')->first() != null)
                                                        @php
                                                            $heteroidentificacao = $inscricao->arquivos()->where('nome', 'heteroidentificacao')->first();
                                                            $fotografia = $inscricao->arquivos()->where('nome', 'fotografia')->first();
                                                        @endphp
                                                        PARECER DA BANCA DE HETEROIDENTIFICAÇÃO -
                                                        @if (
                                                            $heteroidentificacao->avaliacao != null &&
                                                                $heteroidentificacao->avaliacao->comentario != null &&
                                                                $heteroidentificacao->avaliacao->avaliacao == \App\Models\Avaliacao::AVALIACAO_ENUM['recusado']
                                                        )
                                                            <span
                                                                style="font-weight: bold">{{ $heteroidentificacao->getNomeDoc() }}</span>:
                                                            {!! str_replace(['<p>', '</p>'], '', $heteroidentificacao->avaliacao->comentario) !!}.<br>
                                                        @endif
                                                        @if (
                                                            $fotografia->avaliacao != null &&
                                                                $fotografia->avaliacao->comentario != null &&
                                                                $fotografia->avaliacao->avaliacao == \App\Models\Avaliacao::AVALIACAO_ENUM['recusado']
                                                        )
                                                            <span
                                                                style="font-weight: bold">{{ $fotografia->getNomeDoc() }}</span>:
                                                            {!! str_replace(['<p>', '</p>'], '', $fotografia->avaliacao->comentario) !!}.<br>
                                                        @endif
                                                        @if (
                                                            $heteroidentificacao->avaliacao->avaliacao == \App\Models\Avaliacao::AVALIACAO_ENUM['aceito'] &&
                                                                $fotografia->avaliacao->avaliacao == \App\Models\Avaliacao::AVALIACAO_ENUM['aceito']
                                                        )
                                                            Documentação aceita.<br>
                                                        @endif
                                                    @endif
                                                    @if ($inscricao->arquivos()->where('nome', 'laudo_medico')->first() != null)
                                                        @php
                                                            $medico = $inscricao->arquivos()->where('nome', 'laudo_medico')->first();
                                                        @endphp
                                                        PARECER DA EQUIPE MÉDICA -
                                                        @if ($medico->avaliacao != null && $medico->avaliacao->comentario != null)
                                                            <span
                                                                style="font-weight: bold">{{ $medico->getNomeDoc() }}</span>:
                                                            {!! str_replace(['<p>', '</p>'], '', $medico->avaliacao->comentario) !!}.<br>
                                                        @elseif($medico->avaliacao->avaliacao == \App\Models\Avaliacao::AVALIACAO_ENUM['aceito'])
                                                            Documentação aceita.<br>
                                                        @endif
                                                    @endif
                                                </div>
                                            @endif
                                        </th>
                                    </tr>
                                    @php
                                        $cont += 1;
                                    @endphp
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @unless ($curso === $collect_inscricoes->last())
                    <br>
                    <div class="quebrar_pagina"></div>
                @endunless
            @endif
        @endforeach
    </div>
</body>

</html>
