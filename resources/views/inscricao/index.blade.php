<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Minhas Inscrições') }}
        </h2>
    </x-slot>
    <div class="container" style="padding-top: 5rem; padding-bottom: 8rem;">
        <div class="form-row justify-content-center">
            <div class="col-md-10">
                <div class="card" style="width: 100%;">
                    <div class="card-body">
                        <div class="form-row">
                            <div class="col-md-8">
                                <h5 class="card-title">Minhas Inscrições do SiSU na UFAPE</h5>
                                <h6 class="card-subtitle mb-2 text-muted">Cursos</h6>
                            </div>
                        </div>
                        <div div class="form-row">
                            @if(session('success'))
                                <div class="col-md-12" style="margin-top: 5px;">
                                    <div class="alert alert-success" role="alert">
                                        <p>{{session('success')}}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">Nome</th>
                                        <th scope="col">Turno</th>
                                        <th scope="col">Situação</th>
                                        <th scope="col">Opções</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($cursos as $i => $curso)
                                        <tr>
                                            <td>{{$curso->nome}}</td>
                                            <td>
                                                @switch($curso->turno)
                                                    @case($turnos['matutino'])
                                                        {{__('Matutino')}}
                                                        @break
                                                    @case($turnos['vespertino'])
                                                        {{__('Vespertino')}}
                                                        @break
                                                    @case($turnos['noturno'])
                                                        {{__('Noturno')}}
                                                        @break
                                                    @case($turnos['integral'])
                                                        {{__('Integral')}}
                                                        @break
                                                @endswitch
                                            </td>
                                            <td>
                                                @switch($inscricoes[$i]->status)
                                                    @case($situacoes['documentos_pendentes'])
                                                        {{__('Pendente')}}
                                                        @break
                                                    @case($situacoes['documentos_enviados'])
                                                        {{__('Em análise')}}
                                                        @break
                                                @endswitch
                                            </td>
                                            <td>
                                                @switch($inscricoes[$i]->status)
                                                    @case($situacoes['documentos_pendentes'])
                                                        @can('dataEnvio', $inscricoes[$i]->chamada)
                                                            <a type="button" class="btn btn-primary" href="{{route('inscricao.documentacao', $inscricoes[$i]->id)}}">
                                                                Enviar documentos
                                                            </a>
                                                        @else 
                                                            <div class="row">
                                                                <div class="col-sm-12">
                                                                    <button class="btn btn-primary" disabled>
                                                                        Enviar documentos
                                                                    </button>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-sm-12">
                                                                    <small>Fora do periodo de envio</small>
                                                                </div>
                                                            </div>
                                                        @endcan
                                                        @break
                                                    @case($situacoes['documentos_enviados'])
                                                        <a type="button" class="btn btn-primary" href="{{route('inscricao.documentacao', $inscricoes[$i]->id)}}">
                                                            Documentos em análise
                                                        </a>
                                                        @break
                                                    @case($situacoes['documentos_aceitos'])
                                                        <a type="button" class="btn btn-primary" href="{{route('inscricao.documentacao', $inscricoes[$i]->id)}}">
                                                            Documentos aceitos
                                                        </a>
                                                        @break
                                                @endswitch
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
