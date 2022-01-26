<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Período aberto</title>
    </head>
    <body style="color: black;">
        <p>Olá {{$user->name}},</p>
        <p>
            Está aberto o período para {{$data->getNomeEvento()}}, confira mais informações clicando <a href="{{route('index')}}">aqui</a>.
        </p>
    </body>
</html>