<x-guest-layout>
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
                            <div style="color: var(--textcolor2); font-size: 14px;">
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
                    <div class="col-md-6 mt-4">
                        <div class="text-center">
                            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3941.7060201187555!2d-36.49670028521417!3d-8.906898843605621!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x7070c593b2b96d3%3A0x9e8a2fd11fab3580!2sUFAPE%20-%20Universidade%20Federal%20do%20Agreste%20de%20Pernambuco!5e0!3m2!1spt-BR!2sbr!4v1642423274932!5m2!1spt-BR!2sbr" width="400" height="300" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                        </div>
                        <div class="text-center">
                            <div class=" text-center  my-2 pt-1 pb-3">
                                <img class="aling-middle" width="20" src="{{asset('img/Icon awesome-map-marker-alt.svg')}}" alt="icone-busca">
                                <span style="font-size: 14px; color: var(--textcolor2);">Av. Bom Pastor, s/n - Boa Vista,<br> Garanhuns - PE, 55292-270</span>
                                <div>
                                    <img class="aling-middle" width="20" src="{{asset('img/Icon ionic-ios-call.svg')}}" alt="icone-busca">
                                    <span style="font-size: 14px; color: var(--textcolor2);">(00) 99999-9999</span>
                                </div>
                                <div>
                                    <img class="aling-middle" width="20" src="{{asset('img/Icon material-email.svg')}}" alt="icone-busca">
                                    <span style="font-size: 14px; color: var(--textcolor2);">exemplo@exemplo.com</span>
                                </div>
                            </div>
                        </div>
                    </div>
               </div>
            </div>
        </div>
    </div>

    
</x-guest-layout>