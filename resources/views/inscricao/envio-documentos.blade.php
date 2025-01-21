<x-app-layout>
    <div class="fundo2 px-5">
        <div class="row justify-content-center">
            <div class="col-md-8 order-last order-md-first">
                <div class="row justify-content-center">
                    <div class="col-md-11 cabecalhoCurso p-2 px-3 align-items-center"
                        style="background-color: {{ $inscricao->curso->cor_padrao }}">
                        <div class="row justify-content-between">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <img style="border:2px solid white; border-radius: 50%;" src=" {{ asset('storage/' . $inscricao->curso->icone) }} "
                                        alt=""
                                        width="40"
                                        class="img-flex me-1">
                                    <!--ICONE DO CURSO-->
                                    <!--OS ICONES REFERENTES A ESSA CLASSIFICAÇÃO ESTÃO COMO NOMEDOCURSO_BRANCO-->
                                    <span class="tituloTabelas ps-1">{{$inscricao->curso->nome}} - {{$inscricao->curso->getTurno()}}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row justify-content-center" style="margin-bottom: 20px;">
                    <div class="col-md-11 corpo p-2 px-4">
                        @can('periodoRetificacao', $inscricao->chamada)
                            @if (is_null($inscricao->retificacao))
                                @if($inscricao->isDocumentosInvalidados())
                                    <div class="col-md-12 mt-2">
                                        <div class="alert alert-warning" role="alert">
                                            <h5 class="alert-heading">Período de retificação!</h5>
                                            <p>Envie um novo documento, para aqueles documentos que foram recusados.</p>
                                        </div>
                                    </div>
                                @endif
                            @endif
                        @endcan
                        <div>
                            <span class="tituloEnvio">Instruções para o envio de documentos</span>
                        </div>
                        <div class="subtexto mt-2 mb-4"> Toda a documentação deverá ser enviada na forma de arquivos digitalizados ( No formato .pdf),
                            de boa qualidade (sem cortes, rasuras ou emendas) e com todas as informações legíveis com
                            tamanho máximo de 5 MB cada. Para o procedimento de heteroidentificação, os candidatos
                            beneficiários da reserva de vagas para autodeclarados negros (preto e pardos), deverão enviar,
                            além dos documentos, um vídeo com uma das seguintes extensões ".mp4", ".avi", ".wmv", ".mjpeg", ".mov", ".mkv" 
                            ou ".flv" com, no máximo, 120 MB, e uma fotografia com uma das seguintes extensões
                            ".png", ".jpg", ".jpeg" ou ".bmp" com, no máximo, 90 MB. <br>
                            Os arquivos enviados que não sejam de boa qualidade ou que estejam ilegíveis não serão
                            validados. Caso não possa enviar algum documento dispensável, assinalar o termo de
                            compromisso dos arquivos. <br>
                            <b>ATENÇÃO</b>: Antes de enviar os documentos conferir as especificações da sua modalidade de
                            concorrência descritas no Edital do processo de seleção SiSU 2025 da UFAPE, disponível em:
                            <a href="http://www.ufape.edu.br/sisu" target="_blank">www.ufape.edu.br/sisu</a>.
                        </div>
                        <livewire:enviar-documentos :inscricao="$inscricao" :documentos="$documentos"/>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="row justify-content-start" style="margin-bottom: 20px">

                    <div class="col-md-12 shadow-sm p-2 px-3" style="background-color: white; border-radius: 00.5rem;">
                        <div style="font-size: 21px;" class="tituloModal">
                            Legenda
                        </div>
                        <ul class="list-group list-unstyled">
                            <li>
                                <div title="Envio de documento" class="d-flex align-items-center listagemLista my-1 pt-1 pb-1">
                                    <img class="aling-middle" width="33" src="{{asset('img/upload2.svg')}}" alt="icone-upload2">
                                    <div style="font-size: 13px;" class="tituloLista aling-middle mx-3">
                                        Enviar documento
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div title="Envio de documento" class="d-flex align-items-center listagemLista my-1 pt-1 pb-1">
                                    <img class="aling-middle" width="33" src="{{asset('img/download2.svg')}}" alt="icone-upload2">
                                    <div style="font-size: 13px;" class="tituloLista aling-middle mx-3">
                                        Baixar documento enviado
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div title="Envio de documento" class="d-flex align-items-center listagemLista my-1 pt-1 pb-1">
                                    <img class="aling-middle" width="33" src="{{asset('img/download3.svg')}}" alt="icone-upload2">
                                    <div style="font-size: 13px;" class="tituloLista aling-middle mx-3">
                                        Documento não enviado
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div title="Deletar documento enviado" class="d-flex align-items-center listagemLista my-1 pt-1 pb-1">
                                    <img class="aling-middle" width="33" src="{{asset('img/Grupo 1664.svg')}}" alt="Icone de deletar arquivo">
                                    <div style="font-size: 13px;" class="tituloLista aling-middle mx-3">
                                        Deletar documento enviado
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script>
            window.addEventListener('swal:fire', event => {
                Swal.fire({
                    position: 'bottom-end',
                    icon: event.detail.icon,
                    title: event.detail.title,
                    showConfirmButton: false,
                    timerProgressBar: true,
                    timer: 3000,
                    toast: true,
                    showCancelButton: false,
                    showConfirmButton: false
                })
            });
        </script>
    @endpush
</x-app-layout>
