@component('mail::message')
# Olá, {{$inscricao->candidato->no_inscrito}}!

{!!$conteudo!!}
@include('mail.footer')
@endcomponent