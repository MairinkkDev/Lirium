<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    @include('partials.perfilLogado')

     <section>
<a href="/meuPerfil"> meu perfil</a>
    </section>

    <section>
<a href="/eventos"> Eventos </a>
    </section>

      <section>
<a href="/publicacao"> publicações </a>
    </section>
    
        <section>
  <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit">Sair</button>
    </form>
    </section>
    
</body>
</html>