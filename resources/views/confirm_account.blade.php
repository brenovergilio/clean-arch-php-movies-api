<html>
    <body>
        <p>Olá <b>{{ $data['nomeUsuario'] }}!</b></p>
        <p></p>
        <p>Segue o token para confirmação de email <b>(válido por uma hora)</b>:</p>
        <b>{{ $data['token'] }}</b>
        <p></p>
        <p>Att, <br>
        MoviesAPI!</p>
    </body>
</html>
