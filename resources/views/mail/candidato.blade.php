@component('mail::message')
# Olá, {{$inscricao->candidato->user->name}}!

{!!$conteudo!!}
@include('mail.footer')
@endcomponent