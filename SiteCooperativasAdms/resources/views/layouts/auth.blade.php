
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">

    <title>@yield('titulo', 'Reciclum')</title>

    @include('layouts.partials.estilos')

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Raleway:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

    <style>
        html,
        body {
            margin: 0;
            min-height: 100%;
            font-family: 'Raleway', sans-serif;
            background: #002202 !important;
        }

        .auth-page {
            min-height: 100vh;
            min-height: 100dvh;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-sizing: border-box;
            padding: 30px 20px;
            background: #002202 !important;
        }

        .auth-card {
            background: #fff;
        }

        /* Configuração exclusiva da tela de cadastro */
        .auth-page.auth-cadastro-page {
            width: 100% !important;
            min-height: 100vh !important;
            min-height: 100dvh !important;
            height: auto !important;
            padding: 35px 24px !important;
            display: flex !important;
            align-items: flex-start !important;
            justify-content: center !important;
            box-sizing: border-box !important;
        }

        .auth-page.auth-cadastro-page > .auth-card.auth-cadastro-wrapper {
            width: 100% !important;
            max-width: none !important;
            min-width: 0 !important;
            height: auto !important;
            min-height: 0 !important;
            margin: 0 !important;
            padding: 0 !important;
            background: transparent !important;
            border: none !important;
            border-radius: 0 !important;
            box-shadow: none !important;
            overflow: visible !important;
            box-sizing: border-box !important;
            flex: 1 1 auto !important;
        }

        @media (max-width: 700px) {
            .auth-page.auth-cadastro-page {
                padding: 16px 12px !important;
            }
        }
    </style>
</head>

<body>

    <div class="auth-page @yield('auth-page-class')">
        <div class="auth-card @yield('auth-card-class')">
            @yield('content')
        </div>
    </div>
  @stack('scripts')
</body>
</html>
