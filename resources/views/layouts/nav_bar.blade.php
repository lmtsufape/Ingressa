<div id="header">
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm py-1">
        <div class="container-fluid px-lg-5 justify-content-between">
            <a class="navbar-brand" href="{{route('index')}}">LOGO</a>
            <div class="ml-auto justify-content-end">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>
            <div class="collapse navbar-collapse justify-content-end navbarNav" id="navbarSupportedContent">
                <ul class="navbar-nav ml-auto">
                    @auth
                        <li class="nav-item">
                            <a class="nav-link mx-3 @if(request()->routeIs('dashboard')) active @endif" href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
                        </li>
                        @if(auth()->user()->role == \App\Models\User::ROLE_ENUM['admin'])
                            <li class="nav-item">
                                <a class="nav-link mx-3 @if(request()->routeIs('usuarios.*')) active @endif" href="{{route('usuarios.index')}}">{{ __('Analistas') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link mx-3 @if(request()->routeIs('sisus.*')) active @endif" href="{{route('sisus.index')}}">{{ __('SiSU') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link mx-3 @if(request()->routeIs('cursos.*')) active @endif" href="{{route('cursos.index')}}">{{ __('Cursos') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link mx-3 @if(request()->routeIs('cotas.*')) active @endif" href="{{route('cotas.index')}}">{{ __('Cotas') }}</a>
                            </li>
                        @elseif(auth()->user()->role == \App\Models\User::ROLE_ENUM['analista'])
                            @php
                                $sisu = \App\Models\Sisu::latest()->first();
                            @endphp
                            <li class="nav-item">
                                <a class="nav-link mx-3 @if(request()->routeIs('sisus.*')) active @endif" href="{{route('sisus.show', ['sisu' => $sisu->id])}}">{{ __('SiSU') }}</a>
                            </li>
                        @elseif(auth()->user()->role == \App\Models\User::ROLE_ENUM['candidato'])
                            <li class="nav-item">
                                <a class="nav-link mx-3 @if(request()->routeIs('inscricaos.*')) active @endif" href="{{route('inscricaos.index')}}">{{ __('Minhas Inscrições') }}</a>
                            </li>
                        @endif
                        <li class="nav-item mx-3 dropdown">
                            <a class="nav-link dropdown-toggle @if(request()->routeIs('profile.*')) active @endif" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Olá, <b>{{auth()->user()->name}}</b>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-lg-end" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="{{route('profile.show')}}">{{ __('Profile') }}</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><!-- Authentication -->
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf

                                        <button class="dropdown-item" href="{{ route('logout') }}"
                                                 onclick="event.preventDefault();
                                                        this.closest('form').submit();">
                                            {{ __('Log Out') }}
                                        </button >
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link mx-3" href="#">Contato</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link mx-3" href="#">Sobre</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link mx-3" href="{{route('logar')}}">Login</a>
                        </li>
                        {{-- <li class="nav-item">
                            <a class="nav-link mx-3 @if(request()->routeIs('login')) active @endif" href="{{route('login')}}">Login</a>
                        </li> --}}
                    @endauth
                </ul>
            </div>
        </div>
    </nav>
</div>
