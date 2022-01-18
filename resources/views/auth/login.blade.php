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
            <div class="container">
                <div class="py-3 px-4 row ms-0 justify-content-between">
                    <div class="col-md-7">
                        <div class="text-center "style="font-size: 55px;">
                            <img width="250px" src="{{asset('img/Ingressa.svg')}}">
                        </div>
                        <div class="mt-4 tituloEntrada">
                            1- Envie seus documentos
                        </div>
                        <!--deixar o texto justificado-->
                        <div class="textoEntrada mt-2 text-justify" style="text-align: justify;">
                            <p>Para enviar um documento por esta plataforma, primeiro você deve digitalizá-lo utilizando um scanner, uma câmera digital ou um celular. Em seguida, salvar o arquivo em seu computador ou celular. Finalmente, entrar no link correspondente ao documento em questão (os links estão nomeados de acordo com cada documento a ser enviado), anexar o arquivo escaneado correspondente e clicar em ENVIAR. <a href="{{route('envio.docs')}}">Continuar lendo...</a></p>
                        </div>
                    </div>
                    <div class="col-md-3 caixa shadow p-3 bg-white">
                        @if(session('success'))
                            <div class="row">
                                <div class="col-md-12">
                                    <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
                                        <symbol id="check-circle-fill" fill="currentColor" viewBox="0 0 16 16">
                                            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                                        </symbol>
                                    </svg>

                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Success:"><use xlink:href="#check-circle-fill"/></svg>{{session('success')}}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="data bordinha">
                            Entrar
                        </div>
                        <div class="mt-2 subtexto">
                            Entre com o seu e-mail e senha na conta. Caso não tenha uma senha, realize o primeiro acesso.
                        </div>
                        <div class="row">
                            <form id="login-form" class="my-4" method="POST" action="{{route('login')}}">
                                @csrf
                                <div class="form-group textoInput">
                                    <label for="email">E-mail</label>
                                    <input class="form-control form-control-sm caixaDeTexto @error('email') is-invalid @enderror" id="email" name="email" value="{{old('email')}}" type="text" placeholder="E-mail" required>
                                
                                    @error('email')
                                        <div id="validationServer03Feedback" class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="form-group mt-2 textoInput">
                                    <label for="password">Senha</label>
                                    <input class="form-control form-control-sm caixaDeTexto @error('password') is-invalid @enderror" type="password" id="password" name="password" value="{{old('password')}}" type="text" placeholder="Senha" required>
                                
                                    @error('password')
                                        <div id="validationServer03Feedback" class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="mt-3">
                                    <div class="form-check mb-0 pb-0 checkbox">
                                    <input class="form-check-input" type="checkbox" id="remember_me" name="remember" @if(old('remember') != null) checked @endif>
                                    <label class="form-check-label" for="defaultCheck1">
                                        Lembre-se de mim
                                    </label>
                                    </div>
                                    <a href="{{route('password.request')}}" class="link">Esqueceu seu acesso?</a>
                                </div>
                            </form>
                        </div>
                        <div class="row">
                            <div class="col-md-12 text-center" style="margin-bottom: 10px;">
                                <button type="submit" class="btn botaoEntrar col-md-10" form="login-form" style="width: 100%;">Entrar</button> 
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <a href="{{route('primeiro.acesso')}}" type="button" class="btn botaoEntrar col-md-10" style="width: 100%;">Primeiro acesso</a> 
                            </div>
                        </div>
                    </div>
                </div> 
            </div>
        </div>
        @component('layouts.footer')@endcomponent
    </body>
</html>
