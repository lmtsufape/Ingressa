<div>
    @if($inscricao->isArquivoRecusado($documento))
        <div class="mt-2">
            <div class="alert alert-danger " role="alert">
                <h6 style="font-family: Verdana, Geneva, Tahoma, sans-serif; font-size: 15px;" class="alert-heading">Documento invalidado!</h6>
                <span class="p-zero" style="font-family: Verdana, Geneva, Tahoma, sans-serif; font-size: 13px;" style="color: rgb(197, 0, 0)"><strong>Motivo: </strong>{!!$inscricao->arquivo($documento)->avaliacao->comentario!!}</span>
            </div>
        </div>
    @elseif($inscricao->isArquivoAceito($documento))
        <div class="mt-2">
            <div class="alert alert-success " role="alert">
                <h6 style="font-family: Verdana, Geneva, Tahoma, sans-serif; font-size: 14px;" class="mb-0 alert-heading">Documento validado!</h6>
            </div>
        </div>
    @elseif($inscricao->isArquivoReenviado($documento))
        <div class="mt-2">
            <div class="alert alert-primary " role="alert">
                <h6 style="font-family: Verdana, Geneva, Tahoma, sans-serif; font-size: 14px;" class="mb-0 alert-heading">Documento reenviado!</h6>
            </div>
        </div>
    @endif
</div>
