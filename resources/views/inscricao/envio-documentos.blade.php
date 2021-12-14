<x-app-layout>
    <div class="fundo2 px-5 row justify-content-center">
        <div class="col-md-8">
            <div class="row justify-content-center">
                <div class="col-md-12 cabecalhoCurso p-2 px-3 align-items-center"
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
                                <span class="tituloTabelas ps-1">{{ $inscricao->curso->nome }}-</span><span
                                    style="font-weight: 600;"
                                    class="tituloTabelas">{{ $inscricao->curso->getTurno() }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-md-12 corpo p-2 px-4">
                    <div class="mt-2">
                        <span class="tituloEnvio">Instruções para o envio de documentos</span>
                    </div>
                    <div class="subtexto mt-2 mb-4"> Toda a documentação deverá ser enviada na forma de arquivos
                        digitalizados (.pdf),
                        de boa qualidade (sem cortes, rasuras ou emendas) e com todas as informações
                        legíveis com tamanho máximo de 64MB cada. Os arquivos enviados que não sejam
                        de boa qualidade ou que estejam ilegíveis não serão validados. Caso não possa
                        enviar algum documento dispensável, assinalar o termo de compromisso dos arquivos.</div>
                    <livewire:enviar-documentos :inscricao="$inscricao" :documentos="$documentos"/>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="col-md-12 shadow-sm p-2 px-3" style="background-color: white; border-radius: 00.5rem;">
                <div style="font-size: 21px;" class="tituloModal">
                    Legenda
                </div>
                <ul class="list-group list-unstyled">
                    <li>
                        <div title="Envio de documento" class="d-flex align-items-center listagemLista my-2 pt-1 pb-3">
                            <img class="aling-middle" width="33" src="{{asset('img/upload2.svg')}}" alt="icone-upload2">
                            <div style="font-size: 13px;" class="tituloLista aling-middle mx-3">
                                Enviar de documento
                            </div>
                        </div>
                    </li>
                    <li>
                        <div title="Envio de documento" class="d-flex align-items-center listagemLista my-2 pt-1 pb-3">
                            <img class="aling-middle" width="33" src="{{asset('img/download2.svg')}}" alt="icone-upload2">
                            <div style="font-size: 13px;" class="tituloLista aling-middle mx-3">
                                Baixar documento enviado
                            </div>
                        </div>
                    </li>
                    <li>
                        <div title="Envio de documento" class="d-flex align-items-center listagemLista my-2 pt-1 pb-3">
                            <img class="aling-middle" width="33" src="{{asset('img/upload3.svg')}}" alt="icone-upload2">
                            <div style="font-size: 13px;" class="tituloLista aling-middle mx-3">
                                Documento não enviado
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</x-app-layout>
