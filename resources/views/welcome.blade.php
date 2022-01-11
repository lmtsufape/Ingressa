<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        {{-- <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet"> --}}

        <!-- Styles -->
        <style>
            /*! normalize.css v8.0.1 | MIT License | github.com/necolas/normalize.css */html{line-height:1.15;-webkit-text-size-adjust:100%}body{margin:0}a{background-color:transparent}[hidden]{display:none}html{font-family:system-ui,-apple-system,BlinkMacSystemFont,Segoe UI,Roboto,Helvetica Neue,Arial,Noto Sans,sans-serif,Apple Color Emoji,Segoe UI Emoji,Segoe UI Symbol,Noto Color Emoji;line-height:1.5}*,:after,:before{box-sizing:border-box;border:0 solid #e2e8f0}a{color:inherit;text-decoration:inherit}svg,video{display:block;vertical-align:middle}video{max-width:100%;height:auto}.bg-white{--bg-opacity:1;background-color:#fff;background-color:rgba(255,255,255,var(--bg-opacity))}.bg-gray-100{--bg-opacity:1;background-color:#f7fafc;background-color:rgba(247,250,252,var(--bg-opacity))}.border-gray-200{--border-opacity:1;border-color:#edf2f7;border-color:rgba(237,242,247,var(--border-opacity))}.border-t{border-top-width:1px}.flex{display:flex}.grid{display:grid}.hidden{display:none}.items-center{align-items:center}.justify-center{justify-content:center}.font-semibold{font-weight:600}.h-5{height:1.25rem}.h-8{height:2rem}.h-16{height:4rem}.text-sm{font-size:.875rem}.text-lg{font-size:1.125rem}.leading-7{line-height:1.75rem}.mx-auto{margin-left:auto;margin-right:auto}.ml-1{margin-left:.25rem}.mt-2{margin-top:.5rem}.mr-2{margin-right:.5rem}.ml-2{margin-left:.5rem}.mt-4{margin-top:1rem}.ml-4{margin-left:1rem}.mt-8{margin-top:2rem}.ml-12{margin-left:3rem}.-mt-px{margin-top:-1px}.max-w-6xl{max-width:72rem}.min-h-screen{min-height:100vh}.overflow-hidden{overflow:hidden}.p-6{padding:1.5rem}.py-4{padding-top:1rem;padding-bottom:1rem}.px-6{padding-left:1.5rem;padding-right:1.5rem}.pt-8{padding-top:2rem}.fixed{position:fixed}.relative{position:relative}.top-0{top:0}.right-0{right:0}.shadow{box-shadow:0 1px 3px 0 rgba(0,0,0,.1),0 1px 2px 0 rgba(0,0,0,.06)}.text-center{text-align:center}.text-gray-200{--text-opacity:1;color:#edf2f7;color:rgba(237,242,247,var(--text-opacity))}.text-gray-300{--text-opacity:1;color:#e2e8f0;color:rgba(226,232,240,var(--text-opacity))}.text-gray-400{--text-opacity:1;color:#cbd5e0;color:rgba(203,213,224,var(--text-opacity))}.text-gray-500{--text-opacity:1;color:#a0aec0;color:rgba(160,174,192,var(--text-opacity))}.text-gray-600{--text-opacity:1;color:#718096;color:rgba(113,128,150,var(--text-opacity))}.text-gray-700{--text-opacity:1;color:#4a5568;color:rgba(74,85,104,var(--text-opacity))}.text-gray-900{--text-opacity:1;color:#1a202c;color:rgba(26,32,44,var(--text-opacity))}.underline{text-decoration:underline}.antialiased{-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale}.w-5{width:1.25rem}.w-8{width:2rem}.w-auto{width:auto}.grid-cols-1{grid-template-columns:repeat(1,minmax(0,1fr))}@media (min-width:640px){.sm\:rounded-lg{border-radius:.5rem}.sm\:block{display:block}.sm\:items-center{align-items:center}.sm\:justify-start{justify-content:flex-start}.sm\:justify-between{justify-content:space-between}.sm\:h-20{height:5rem}.sm\:ml-0{margin-left:0}.sm\:px-6{padding-left:1.5rem;padding-right:1.5rem}.sm\:pt-0{padding-top:0}.sm\:text-left{text-align:left}.sm\:text-right{text-align:right}}@media (min-width:768px){.md\:border-t-0{border-top-width:0}.md\:border-l{border-left-width:1px}.md\:grid-cols-2{grid-template-columns:repeat(2,minmax(0,1fr))}}@media (min-width:1024px){.lg\:px-8{padding-left:2rem;padding-right:2rem}}@media (prefers-color-scheme:dark){.dark\:bg-gray-800{--bg-opacity:1;background-color:#2d3748;background-color:rgba(45,55,72,var(--bg-opacity))}.dark\:bg-gray-900{--bg-opacity:1;background-color:#1a202c;background-color:rgba(26,32,44,var(--bg-opacity))}.dark\:border-gray-700{--border-opacity:1;border-color:#4a5568;border-color:rgba(74,85,104,var(--border-opacity))}.dark\:text-white{--text-opacity:1;color:#fff;color:rgba(255,255,255,var(--text-opacity))}.dark\:text-gray-400{--text-opacity:1;color:#cbd5e0;color:rgba(203,213,224,var(--text-opacity))}}
        </style>

        {{-- <style>
            body {
                font-family: 'Nunito', sans-serif;
            }
        </style> --}}

        @livewireStyles

        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}" defer></script>
        <script src="{{asset('bootstrap/js/bootstrap.js')}}"></script>

        <link href="{{asset('bootstrap/css/bootstrap.css')}}" rel="stylesheet">
        <link rel="stylesheet" href="{{asset('css/main.css')}}">
    </head>
    <body class="">
        @component('layouts.nav_bar')@endcomponent
        <div class="fundo px-5 py-5">
            <div class="py-3 px-4 row ms-0 justify-content-between">
                @if($edicao_atual != null)
                    <div class="col-md-3 shadow p-3 caixa">
                        <div class="row mx-1 justify-content-between lis">
                            <div class="d-flex align-items-center data justify-content-between mx-0 px-0">
                                <span class="aling-middle " style="font-size: 22px;">Datas Importantes</span>
                            </div>
                        </div>
                        @if(is_null($chamadas))
                            <div class="col-md-12 text-center">
                                <img class="img-fluid py-4" width="270" src="{{asset('img/Grupo 1652.svg')}}">
                            </div>
                            <div class="col-md-12 text-center legenda" style="font-weight: bolder;">
                                Nenhuma chamada criada
                            </div>
                        @else
                            @if ($chamadas->first()->datasChamada->count() == 0)
                                <div class="col-md-12 text-center">
                                    <img class="img-fluid py-4" width="270" src="{{asset('img/Grupo 1652.svg')}}">
                                </div>
                                <div class="col-md-12 text-center legenda" style="font-weight: bolder;">
                                    Nenhuma data criada
                                </div>
                            @else
                                @foreach ($chamadas as $chamada)
                                    @php
                                        $exibirTitulo = true;
                                    @endphp
                                    @if ($chamada->datasChamada->count() > 0)
                                        @if($exibirTitulo)
                                            <div style="color: var(--textcolors); font-size: 19px; font-weight: 600;" class="mt-2">{{$chamada->nome}}</div>
                                        @endif
                                        @php
                                            $exibirTitulo = false;
                                        @endphp
                                        <ul class="list-group list-unstyled">
                                            @foreach ($chamada->datasChamada as $data)
                                                <li>
                                                    <div class="d-flex align-items-center listagemLista my-2 pt-1 pb-3">
                                                        @if ($data->tipo == $tipos_data['convocacao'])
                                                            <img class="img-card-data" src="{{asset('img/icon-chamada.svg')}}" alt="Icone de convocação" width="45">
                                                        @elseif($data->tipo == $tipos_data['envio'])
                                                            <img class="img-card-data" src="{{asset('img/icon-envioDoc.svg')}}" alt="Icone de envio" width="45">
                                                        @elseif($data->tipo == $tipos_data['analise'])
                                                            <img class="img-card-data" src="{{asset('img/icon-analiseDoc (2).svg')}}" alt="Icone de analise" width="45">
                                                        @elseif($data->tipo == $tipos_data['resultado_parcial'])
                                                            <img class="img-card-data" src="{{asset('img/icon-resultadoParcial.svg')}}" alt="Icone de resultado parcial" width="45">
                                                        @elseif($data->tipo == $tipos_data['reenvio'])
                                                            <img class="img-card-data" src="{{asset('img/icon-envioDoc.svg')}}" alt="Icone de reenvio" width="45">
                                                        @elseif($data->tipo == $tipos_data['analise_reenvio'])
                                                            <img class="img-card-data" src="{{asset('img/icon-analiseRetificacao.svg')}}" alt="Icone de analise do reenvio" width="45">
                                                        @elseif($data->tipo == $tipos_data['resultado_final'])
                                                            <img class="img-card-data" src="{{asset('img/icon-resultadoFinal.svg')}}" alt="Icone de resultado final" width="45">
                                                        @endif

                                                        <div class="">
                                                            <div class="tituloLista aling-middle mx-3">
                                                                {{$data->titulo}}
                                                            </div>
                                                            <div class="aling-middle mx-3 datinha">
                                                                {{date('d/m/Y',strtotime($data->data_inicio))}} > {{date('d/m/Y',strtotime($data->data_fim))}}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                @endforeach
                            @endif
                        @endif
                    </div>

                    <div class="col-md-8 pt-0">
                        <div class="col-md-12 tituloBorda">
                            <div class="d-flex align-items-center justify-content-between mx-0 px-0">
                                <span class="align-middle titulo">Listagens</span>
                            </div>
                        </div>
                        <div class="col-md-12 mt-4 p-2 caixa shadow">
                            @if(is_null($chamadas))
                                <div class="text-center" style="margin-bottom: 10px;" >
                                    <img class="img-fluid py-4" width="270" src="{{asset('img/Grupo 1654.svg')}}">
                                    <div class="col-md-12 text-center legenda" style="font-weight: bolder;">
                                        Nenhuma listagem foi adicionada
                                    </div>
                                </div>
                            @else
                                @if($chamadas->first()->listagem->count() == 0)
                                    <div class="text-center" style="margin-bottom: 10px;" >
                                        <img class="img-fluid py-4" width="270" src="{{asset('img/Grupo 1654.svg')}}">
                                        <div class="col-md-12 text-center legenda" style="font-weight: bolder;">
                                            Nenhuma listagem foi adicionada
                                        </div>
                                    </div>
                                @else
                                    @foreach ($chamadas as $chamada)
                                        <ul class="list-group mx-2 list-unstyled">
                                            @foreach ($chamada->listagem as $listagem)
                                                <li>
                                                    <div class="d-flex align-items-center listagemLista my-2 pt-1 pb-3">
                                                        <div class="">
                                                            <div class="mx-2 tituloLista">
                                                                {{$listagem->titulo}} - <span class="destaqueLista">@switch($listagem->tipo)
                                                                    @case(\App\Models\Listagem::TIPO_ENUM['convocacao'])
                                                                        convocação
                                                                        @break
                                                                    @case(\App\Models\Listagem::TIPO_ENUM['pendencia'])
                                                                        pendência
                                                                        @break
                                                                    @case(\App\Models\Listagem::TIPO_ENUM['resultado'])
                                                                        resultado
                                                                        @break
                                                                @endswitch</span>
                                                            </div>
                                                            <div class="row px-1 link">
                                                                <a href="{{asset('storage/' . $listagem->caminho_listagem)}}" target="blanck" style="text-decoration: none;"><img width="13" src="{{asset('img/Icon feather-link.svg')}}">{{asset('storage/' . $listagem->caminho_listagem)}}</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endforeach
                                @endif
                            @endif
                        </div>
                    </div>
                @else
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mt-4 tituloEntrada">
                                Fora do período de ingresso
                            </div>
                            <div class="textoEntrada mt-2 text-justify">
                                O Lorem Ipsum é um texto modelo da indústria tipográfica e de impressão. O Lorem Ipsum tem vindo a ser o texto padrão usado por estas indústrias desde o ano de 1500, quando uma misturou os caracteres de um texto para criar um espécime de livro. Este texto não só sobreviveu 5 séculos, mas também o salto para a tipografia electrónica, mantendo-se essencialmente inalterada. Foi popularizada nos anos 60 com a disponibilização das folhas de Letraset, que continham passagens com Lorem Ipsum, e mais recentemente com os programas de publicação como o Aldus PageMaker que incluem versões do Lorem Ipsum.
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{--<div class="container" style="margin-top: 50px; margin-bottom: 50px;">
            <div class="card text-center">
                <div class="card-header">
                    <span class="titulo pt-0">Bem vindo ao {nome_do_sistema}!</span>
                </div>
                <div class="card-body" style="background-color: rgba(0, 0, 0, 0.03);">
                    @if($edicao_atual != null)
                        <h5 class="card-title titulo pt-0" style="font-size: 34px;">SISU {{$edicao_atual->edicao}}</h5>
                        @foreach ($chamadas as $chamada)
                            <div class="row pb-4">
                                <div class="col-sm-12">
                                    <div class="card" style="text-align: left">
                                        <div class="card-body">
                                            <h5 class="card-title titulo pt-0" style="font-size: 30px;">{{$chamada->nome}}</h5>
                                            <div class="">
                                                <div class="row ms-0 justify-content-between">
                                                    <div class="col-md-4 shadow p-3 caixa">
                                                        <div class="col-md-12 data" style="font-size: 25px;">
                                                            Datas Importantes
                                                        </div>
                                                        @if ($chamada->datasChamada->count() > 0)
                                                            <ul class="list-group list-unstyled">
                                                                @foreach ($chamada->datasChamada as $data)
                                                                    <li>
                                                                        <div class="d-flex align-items-center listagemLista my-2 pt-1 pb-3">
                                                                            @if ($data->tipo == $tipos_data['convocacao'])
                                                                                <img class="img-card-data" src="{{asset('img/icon_convocacao.png')}}" alt="Icone de convocação" width="45">
                                                                            @elseif($data->tipo == $tipos_data['envio'])
                                                                                <img class="img-card-data" src="{{asset('img/icon_envio.png')}}" alt="Icone de envio" width="45">
                                                                            @elseif($data->tipo == $tipos_data['analise'])
                                                                                <img class="img-card-data" src="{{asset('img/icon_resultado.png')}}" alt="Icone de envio" width="45">
                                                                            @elseif($data->tipo == $tipos_data['resultado_parcial'])
                                                                                <img class="img-card-data" src="{{asset('img/icon_resultado.png')}}" alt="Icone de resultados" width="45">
                                                                            @elseif($data->tipo == $tipos_data['reenvio'])
                                                                                <img class="img-card-data" src="{{asset('img/icon_envio.png')}}" alt="Icone de resultados" width="45">
                                                                            @elseif($data->tipo == $tipos_data['analise_reenvio'])
                                                                                <img class="img-card-data" src="{{asset('img/icon_envio.png')}}" alt="Icone de resultados" width="45">
                                                                            @elseif($data->tipo == $tipos_data['resultado_final'])
                                                                                <img class="img-card-data" src="{{asset('img/icon_resultado.png')}}" alt="Icone de resultados" width="45">
                                                                            @endif
                                                                            <div class="">
                                                                                <div class="tituloLista aling-middle mx-3">
                                                                                    {{$data->titulo}}
                                                                                </div>
                                                                                <div class="aling-middle mx-3 datinha">
                                                                                    {{date('d/m/Y',strtotime($data->data_inicio))}} > {{date('d/m/Y',strtotime($data->data_fim))}}
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                        @else
                                                            <div class="col-md-12 text-center">
                                                                <img class="img-fluid py-4" width="270" src="{{asset('img/Grupo 1652.svg')}}">
                                                            </div>
                                                            <div class="col-md-12 text-center legenda">
                                                                Nenhuma data foi adicionada
                                                                @can('isAdmin', \App\Models\User::class)
                                                                    <p><a class="redirecionamento" data-bs-toggle="modal" data-bs-target="#adicionarData">clique aqui</a> <span class="legenda">para adicionar</span></p>
                                                                @endcan
                                                            </div>
                                                        @endif
                                                    </div>

                                                    <div class="col-md-8 pt-0">
                                                        <div class="col-md-12 tituloBorda">
                                                            <span class="titulo pt-0" style="font-size: 28px;">Listagens</span>
                                                        </div>
                                                        <div class="col-md-12 mt-4 p-2 caixa shadow text-center">
                                                            @if($chamada->listagem->count() > 0)
                                                                <ul class="list-group mx-2 list-unstyled">
                                                                    @foreach ($chamada->listagem as $listagem)
                                                                        <li>
                                                                            <div class="d-flex align-items-center listagemLista my-2 pt-1 pb-3">
                                                                                <div class="">
                                                                                    <div class="mx-2 tituloLista">
                                                                                        {{$listagem->titulo}} - <span class="destaqueLista">@switch($listagem->tipo)
                                                                                            @case(\App\Models\Listagem::TIPO_ENUM['convocacao'])
                                                                                                convocação
                                                                                                @break
                                                                                            @case(\App\Models\Listagem::TIPO_ENUM['pendencia'])
                                                                                                pendência
                                                                                                @break
                                                                                            @case(\App\Models\Listagem::TIPO_ENUM['resultado'])
                                                                                                resultado
                                                                                                @break
                                                                                        @endswitch</span>
                                                                                    </div>
                                                                                    <div class="row px-1 link" style="text-align: left;">
                                                                                        <a href="{{asset('storage/' . $listagem->caminho_listagem)}}" target="blanck" style="text-decoration: none;"><img width="13" src="{{asset('img/Icon feather-link.svg')}}">{{asset('storage/' . $listagem->caminho_listagem)}}</a>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </li>
                                                                    @endforeach
                                                                </ul>
                                                            @else
                                                                <img class="img-fluid py-4" width="270" src="{{asset('img/Grupo 1654.svg')}}">
                                                                <div class="col-md-12 text-center legenda" style="margin-bottom: 20px;">
                                                                    Nenhuma listagem foi adicionada
                                                                    @can('isAdmin', \App\Models\User::class)
                                                                        <p><a class="redirecionamento" data-bs-toggle="modal" data-bs-target="#adicionarListagem">clique aqui</a> <span class="legenda">para adicionar</span></p>
                                                                    @endcan
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mt-4 tituloEntrada">
                                    Fora do período de ingresso
                                </div>
                                <div class="textoEntrada mt-2 text-justify">
                                    O Lorem Ipsum é um texto modelo da indústria tipográfica e de impressão. O Lorem Ipsum tem vindo a ser o texto padrão usado por estas indústrias desde o ano de 1500, quando uma misturou os caracteres de um texto para criar um espécime de livro. Este texto não só sobreviveu 5 séculos, mas também o salto para a tipografia electrónica, mantendo-se essencialmente inalterada. Foi popularizada nos anos 60 com a disponibilização das folhas de Letraset, que continham passagens com Lorem Ipsum, e mais recentemente com os programas de publicação como o Aldus PageMaker que incluem versões do Lorem Ipsum.
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>--}}
        @component('layouts.footer')@endcomponent
    </body>
</html>
