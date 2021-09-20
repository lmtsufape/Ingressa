<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Candidatos por curso') }}
        </h2>
    </x-slot>
    <div class="container" style="padding-top: 5rem; padding-bottom: 8rem;">
        <div class="form-row justify-content-center">
            <div class="col-md-10">
                <div class="card" style="width: 100%;">
                    <div class="card-body">
                        <div class="form-row">
                            <div class="col-md-8">
                                <h5 class="card-title">Candidatos por curso da {{$chamada->nome}} da edição {{$chamada->sisu->edicao}} cadastrados</h5>
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
                                        <th scope="col">#</th>
                                        <th scope="col">Nome</th>
                                        <th scope="col">Turno</th>
                                        <th scope="col">Opções</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($cursos as $i => $curso)
                                        <tr>
                                            <td>{{$i+1}}</td>
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
                                                <a class="btn btn-success shadow-sm" href="{{route('chamadas.candidatos.curso', ['sisu_id' => $chamada->sisu->id, 'chamada_id' => $chamada->id, 'curso_id' => $curso->id])}}"><img src="{{ asset('img/icon_candidato.svg') }}" alt="Candidatos inscritos no sisu {{$chamada->sisu->edicao}}" width="23.5px" ></a>
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
