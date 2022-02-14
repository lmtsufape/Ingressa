<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Laravel') }}</title>

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
            <div class="fundo2 px-5">
                <div class="row justify-content-center">
                    <div class="col-md-8 caixa shadow-sm p-2 px-3">
                    <div class="row">
                        <div class="col-md-6 my-1">
                                <div class="row">
                                    <div style="border-bottom: 1px solid #f5f5f5; color: var(--primaria); font-size: 25px; font-weight: 600;" class="mb-1">
                                        Contato
                                    </div>
                                </div>
                                <div class="row">
                                    @if(session('success'))
                                        <div class="col-md-12" style="margin-top: 5px;">
                                            <div class="alert alert-success" role="alert">
                                                <p>{{session('success')}}</p>
                                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div class="row">
                                    <div style="color: rgb(53, 53, 53); font-size: 14px;">
                                        Se tiver alguma dúvida sobre o processo seletivo, encontrou algum problema no sistema ou tem alguma reclamação a fazer, entre em contato com o DRCA.
                                    </div>
                                </div>
                                <form method="POST" action="{{route('enviar.mensagem')}}">
                                    @csrf
                                    <div class="form-row">
                                        <div class="col-md-12 form-group">
                                            <label for="nome_completo" class="py-2">Nome completo</label>
                                            <input type="text" class="form-control campoDeTexto @error('nome_completo') is-invalid @enderror" name="nome_completo" placeholder="Fulano" required>
                                            @error('nome_completo')
                                                <div id="validationServer03Feedback" class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-12 form-group">
                                            <label for="email" class="py-2">E-mail</label>
                                            <input type="email" class="form-control campoDeTexto @error('email') is-invalid @enderror" name="email" placeholder="exemplo@gmail.com" required>
                                            @error('email')
                                                <div id="validationServer03Feedback" class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-12 form-group">
                                            <label for="assunto" class="py-2">Assunto</label>
                                            <input type="text" class="form-control campoDeTexto @error('assunto') is-invalid @enderror" name="assunto" placeholder="Assunto do e-mail" required>
                                            @error('assunto')
                                                <div id="validationServer03Feedback" class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-12 form-group">
                                            <label for="mensagem" class="py-2">Mensagem</label>
                                            <textarea class="form-control campoDeTexto @error('mensagem') is-invalid @enderror" name="mensagem" placeholder="Escreva sua mensagem aqui..." cols="30" rows="3" required></textarea>
                                            @error('mensagem')
                                                <div id="validationServer03Feedback" class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row justify-content-center" style="">
                                        <div class="col-md-4 form-group">
                                            <button type="submit" class="btn botaoVerde my-2"><span class="px-4">Enviar</span></button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-md-6 mt-4" style="color: rgb(53, 53, 53);">
                                <div class="text-center">
                                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3941.7060201187555!2d-36.49670028521417!3d-8.906898843605621!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x7070c593b2b96d3%3A0x9e8a2fd11fab3580!2sUFAPE%20-%20Universidade%20Federal%20do%20Agreste%20de%20Pernambuco!5e0!3m2!1spt-BR!2sbr!4v1642423274932!5m2!1spt-BR!2sbr" width="400" height="300" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                                </div>
                                <div class="text-center">
                                    <div class=" text-center  my-2 pt-1 pb-3">
                                        <img class="aling-middle" width="20" src="{{asset('img/Icon awesome-map-marker-alt.svg')}}" alt="icone-busca">
                                        <span style="font-size: 14px;">Av. Bom Pastor, s/n - Boa Vista,<br> Garanhuns - PE, 55292-270</span>
                                        <div>
                                            <img class="aling-middle" width="20" src="{{asset('img/Icon material-email.svg')}}" alt="icone-busca">
                                            <span style="font-size: 14px;">matriculas.sisu@ufape.edu.br</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </div>
                    </div>
                </div>
            </div>
        @component('layouts.footer')@endcomponent
    </body>
</html>
