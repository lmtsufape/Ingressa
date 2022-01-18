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
        <div class="fundo2 px-5"> 
            <div class="row justify-content-center">
                <div class="col-md-8 shadow-sm caixa p-3"> 
                    <div style="border-bottom: 1px solid #f5f5f5; color: var(--primaria); font-size: 25px; font-weight: 600;" class="mb-1">
                        Sobre
                    </div>
                    <div style="color: var(--textcolor); font-size: 18px; font-weight: 600;" class="my-1">
                        Apresentação:
                    </div> 
                    <div style="color: var(--textcolor2); font-size: 15px;">
                        O sistema Ingressa foi pensado para atender a demanda da UFAPE em gerir, de forma independente, todos os processos de matrícula do SiSU.
                    </div>
                    <div style="color: var(--textcolor); font-size: 18px; font-weight: 600;" class="my-1">
                        Principais funções:
                    </div>
                    <div>
                        <ul style="list-style-type: disc !important; padding-left:1em !important; margin-left:1em;">
                            <li style="color: var(--textcolor2); font-size: 15px;">Cadastro automático dos candidatos por meio de importações de arquivos .csv;</li>
                            <li style="color: var(--textcolor2); font-size: 15px;">Envio de documentos;</li>
                            <li style="color: var(--textcolor2); font-size: 15px;">Avaliação de documentos enviados;
                            </li>
                            <li style="color: var(--textcolor2); font-size: 15px;">Produção automática de listas de convocados das chamadas;</li>
                            <li style="color: var(--textcolor2); font-size: 15px;">Produção automática de listas de candidatos com cadastro validado ou invalidado;
                            </li>
                            <li style="color: var(--textcolor2); font-size: 15px;">Produção automática de listas de ingressantes e suplentes da edição do SiSU.</li>
                        </ul>
                    </div>
                    <div style="color: var(--textcolor); font-size: 18px; font-weight: 600;" class="my-1">
                        Registro:
                    </div>
                    <div style="color: var(--textcolor2); font-size: 15px;">
                        Em definição.
                    </div>
                    <div style="color: var(--textcolor); font-size: 18px; font-weight: 600;" class="my-1">
                        Código-fonte:
                    </div>
                    <div>
                        <a href="https://github.com/lmts-ufape/gestao-matriculas-sisu" target="_blanck" class="link-dark" style="font-size: 15px;">GitHub</a>
                    </div>
                    <div style="color: var(--textcolor); font-size: 18px; font-weight: 600;" class="my-1">
                        Licença de uso:
                    </div>
                    <div style="color: var(--textcolor2); font-size: 15px;">
                        Em definição.
                    </div>
                    <div style="color: var(--textcolor); font-size: 18px; font-weight: 600;" class="my-1">
                        Equipe:
                    </div>
                    <div style="color: var(--textcolor); font-size: 17px; font-weight: 600;" class="my-1">
                        Docentes:
                    </div>
                    <div>
                        <ul style="list-style-type: disc !important; padding-left:1em !important; margin-left:1em;">
                            <li><a href="http://lattes.cnpq.br/9517716593738845" target="_blanck" class="link-dark" style="font-size: 15px;">Dr. Anderson Fernandes de Alencar</a></li>
                            <li><a href="http://lattes.cnpq.br/7448139435512224" target="_blanck" class="link-dark" style="font-size: 15px;">Dr. Igor Medeiros Vanderlei</a></li>
                            <li><a href="http://lattes.cnpq.br/2498961747789618" target="_blanck" class="link-dark" style="font-size: 15px;">Dr. Jean Carlos Texeira de Araujo</a></li>
                            <li><a href="http://lattes.cnpq.br/3111765717865989" target="_blanck" class="link-dark" style="font-size: 15px;">Dr. Mariel José Pimentel de Andrade</a></li>
                            <li><a href="http://lattes.cnpq.br/4654692334430085" target="_blanck" class="link-dark" style="font-size: 15px;">Dr. Rodrigo Gusmão de Carvalho Rocha</a></li>
                        </ul>
                    </div>
                    <div style="color: var(--textcolor); font-size: 17px; font-weight: 600;" class="my-1">
                        Desenvolvedores:
                    </div>
                    <div>
                        <ul style="list-style-type: disc !important; padding-left:1em !important; margin-left:1em;">
                            <li><a href="https://www.linkedin.com/in/carlos-andr%C3%A9-611766196/" target="_blanck" class="link-dark" style="font-size: 15px;">Carlos André de Almeida Cavalcante</a></li>
                            <li><a href="https://br.linkedin.com/in/kelwin-jonas-1b8656214" target="_blanck" class="link-dark" style="font-size: 15px;">Kelwin Jonas Silva Santos</a></li>
                            <li><a href="https://www.linkedin.com/in/jo-fernando" target="_blanck" class="link-dark" style="font-size: 15px;">José Fernando Mendes da Costa</a></li>
                        </ul>
                    </div>
                    <div style="color: var(--textcolor); font-size: 17px; font-weight: 600;" class="my-1">
                        Designer:
                    </div>
                    <div>
                        <ul style="list-style-type: disc !important; padding-left:1em !important; margin-left:1em;">
                            <li><a href="#" target="_blanck" class="link-dark" style="font-size: 15px;">Ana Beatriz Almeida Vanderlei</a></li>
                        </ul>
                    </div>
                    <div style="color: var(--textcolor); font-size: 17px; font-weight: 600;" class="my-1">
                        Gerenciadora de tarefas:
                    </div>
                    <div>
                        <ul style="list-style-type: disc !important; padding-left:1em !important; margin-left:1em;">
                            <li><a href="#" target="_blanck" class="link-dark" style="font-size: 15px;">Maria Virgínia Mendonça</a></li>
                        </ul>
                    </div>
                    </div>
                </div>
            </div>
        </div>
        @component('layouts.footer')@endcomponent
    </body>
</html>