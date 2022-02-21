<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Comprovante</title>
</head>
<body style="color: black;">
    <p>
        Comprovante de envio dos documentos para a inscrição de nº {{$inscricao->id}}.
        Envio realizado em {{date('d/m/Y', strtotime($inscricao->updated_at))}} às {{date('H:i:s', strtotime($inscricao->updated_at))}}.
        <br>Documentos:
    </p>
    <p>
        <ul>
            @if ($documentos_requisitados->contains('declaracao_veracidade'))
                <li>
                    Declaração de Veracidade - {{$inscricao->arquivo('declaracao_veracidade') != null ? 'enviado' : 'não enviado'}}
                </li>
            @endif
            @if ($documentos_requisitados->contains('certificado_conclusao'))
                <li>
                    Certificado de conclusão - {{$inscricao->arquivo('certificado_conclusao') != null ? 'enviado' : 'não enviado'}}
                </li>
            @endif
            @if($documentos_requisitados->contains('historico'))
                <li>
                    Histórico - {{$inscricao->arquivo('historico') != null ? 'enviado' : 'não enviado'}}
                </li>
            @endif
            @if($documentos_requisitados->contains('nascimento_ou_casamento'))
                <li>
                    Certidão de nascimento ou casamento - {{$inscricao->arquivo('nascimento_ou_casamento') != null ? 'enviado' : 'não enviado'}}
                </li>
            @endif
            @if($documentos_requisitados->contains('rg'))
                <li>
                    RG - {{$inscricao->arquivo('rg') != null ? 'enviado' : 'não enviado'}}
                </li>
            @endif
            @if($documentos_requisitados->contains('cpf'))
                <li>
                    CPF - {{$inscricao->arquivo('cpf') != null ? 'enviado' : 'não enviado'}}
                </li>
            @endif
            @if($documentos_requisitados->contains('quitacao_eleitoral'))
                <li>
                    Quitação eleitoral - {{$inscricao->arquivo('quitacao_eleitoral') != null ? 'enviado' : 'não enviado'}}
                </li>
            @endif
            @if($documentos_requisitados->contains('quitacao_militar'))
                <li>
                    Reservista (caso sexo seja masculino) - {{$inscricao->arquivo('quitacao_militar') != null ? 'enviado' : 'não enviado'}}
                </li>
            @endif
            @if($documentos_requisitados->contains('foto'))
                <li>
                    Foto 3x4 - {{$inscricao->arquivo('foto') != null ? 'enviado' : 'não enviado'}}
                </li>
            @endif
            @if ($documentos_requisitados->contains('declaracao_cotista'))
                <li>
                    Declaração de cotista - {{$inscricao->arquivo('declaracao_cotista') != null ? 'enviado' : 'não enviado'}}
                </li>
            @endif
            @if ($documentos_requisitados->contains('heteroidentificacao'))
                <li>
                    Documento de heteroidentificação - {{$inscricao->arquivo('heteroidentificacao') != null ? 'enviado' : 'não enviado'}}
                </li>
            @endif
            @if ($documentos_requisitados->contains('comprovante_renda'))
                <li>
                    Comprovante de renda - {{$inscricao->arquivo('comprovante_renda') != null ? 'enviado' : 'não enviado'}}
                </li>
            @endif
            @if ($documentos_requisitados->contains('rani'))
                <li>
                    Registro Administrativo de Nascimento de Indígena (RANI) ou declaração de vínculo com comunidade indígena brasileira - {{$inscricao->arquivo('rani') != null ? 'enviado' : 'não enviado'}}
                </li>
            @endif
            @if ($documentos_requisitados->contains('laudo_medico'))
                <li>
                    Laudo Médico e exames de comprovação da condição de beneficiário da reserva de vaga para pessoas com deficiência - {{$inscricao->arquivo('laudo_medico') != null ? 'enviado' : 'não enviado'}}
                </li>
            @endif
        </ul>
    </p>
    <p>
        Protocolo do comprovante - {{$protocolo}}
    </p><br>
    <p>
        Atenciosamente,<br><br>
        Ingressa - Sistema de Gestão de Matrículas do SiSU<br>
        Laboratório Multidisciplinar de Tecnologias Sociais<br>
        Universidade Federal do Agreste de Pernambuco
    </p>
</body>
</html>