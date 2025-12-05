<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Styles -->
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
        <link href="{{asset('bootstrap/css/bootstrap.css')}}" rel="stylesheet">
        <link rel="stylesheet" href="{{asset('css/main.css')}}">
        <link rel="icon" type="imagem/png" href="{{asset('img/logo-icon.png')}}">

        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}" defer></script>
        <script src="https://unpkg.com/@popperjs/core@2" defer></script>
        <script src="{{asset('bootstrap/js/bootstrap.js')}}" defer></script>
        <script src="{{asset('js/jquery-3.6.0.min.js')}}"></script>
        <script src="{{asset('js/jquery.mask.min.js')}}"></script>
    </head>
    <body class="font-sans antialiased">
        <!-- Barra Brasil -->
        <div id="barra-brasil" style="background:#7F7F7F; height: 20px; padding:0 0 0 10px;display:block;">
            <ul id="menu-barra-temp" style="list-style:none;">
                <li style="display:inline; float:left;padding-right:10px; margin-right:10px; border-right:1px solid #EDEDED">
                    <a href="http://brasil.gov.br" style="font-family:sans,sans-serif; text-decoration:none; color:white;">Portal do Governo Brasileiro</a>
                </li>
                <li>
                <a style="font-family:sans,sans-serif; text-decoration:none; color:white;" href="http://epwg.governoeletronico.gov.br/barra/atualize.html">Atualize sua Barra de Governo</a>
                </li>
            </ul>
        </div>
        @include('layouts.nav_bar')
        <div class="font-sans text-gray-900 antialiased">
            {{ $slot }}
        </div>
        @include('layouts.footer')

        <script>
            $(document).ready(function () {
                var btn = document.getElementsByClassName("submeterFormBotao");
                if(btn.length > 0){
                    $(document).on('submit', 'form', function() {
                        $('button').attr('disabled', 'disabled');
                        for (var i = 0; i < btn.length; i++) {
                        btn[i].textContent = 'Aguarde...';
                        btn[i].style.backgroundColor = btn[i].style.backgroundColor + 'd8';
                    }
                    });
                }
            })
        </script>
        <script defer="defer" src="//barra.brasil.gov.br/barra.js" type="text/javascript"></script>
        @livewireScripts
    </body>
</html>
