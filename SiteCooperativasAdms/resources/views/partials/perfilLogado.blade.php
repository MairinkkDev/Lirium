@auth
    @php
        $usuario = auth()->user();
        $anos = $usuario->created_at
            ? (int) floor($usuario->created_at->diffInYears(now()))
            : null;
    @endphp

    <div>
        <p>Olá, <strong>{{ $usuario->nomeUsuario }}</strong></p>

        <p>
            @if($usuario->created_at)
                @if($anos >= 1)
                    Membro há {{ $anos }} {{ $anos === 1 ? 'ano' : 'anos' }}
                @else
                    Membro há menos de 1 ano
                @endif
                desde {{ $usuario->created_at->format('Y') }}
            @else
                <small>Data de cadastro não registrada</small>
            @endif
        </p>
    </div>
@endauth