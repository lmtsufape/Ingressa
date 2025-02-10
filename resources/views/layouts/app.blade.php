<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        @livewireStyles
        <!-- Styles -->
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">

        <link href="{{asset('bootstrap/css/bootstrap.css')}}" rel="stylesheet">
        <link rel="stylesheet" href="{{asset('css/main.css')}}">
        <link rel="stylesheet" href="{{asset('bootstrap-select/css/bootstrap-select.min.css')}}">
        <link rel="icon" type="imagem/png" href="{{asset('img/logo-icon.png')}}">

        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}" defer></script>
        <script src="https://unpkg.com/@popperjs/core@2" ></script>
        <script src="{{asset('bootstrap/js/bootstrap.js')}}"></script>
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.4.2/umd/popper.min.js"></script>
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

        <script src="{{asset('js/jquery-3.6.0.min.js')}}"></script>
        <script src="{{asset('bootstrap-select/js/bootstrap-select.min.js')}}"></script>

        <script src="{{asset('ckeditor/ckeditor.js')}}"></script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.js"></script>
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
        <x-jet-banner />
        @component('layouts.nav_bar')@endcomponent
        <div class="min-h-screen bg-gray-100 p-1">

            {{-- @livewire('navigation-menu') --}}

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        @stack('modals')

        @component('layouts.footer')@endcomponent
        @livewireScripts
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        @stack('scripts')

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
    </body>
</html>
