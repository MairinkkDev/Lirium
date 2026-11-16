<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class VerificaNivelAcesso
{
    /**
     * Verifica o nível de acesso do usuário.
     */
    public function handle(Request $request, Closure $next, int $nivelMinimo): Response
    {
        if (!Auth::check() || Auth::user()->idNivelAcesso < $nivelMinimo) {
            abort(403, 'Você não tem permissão para acessar esta área.');
        }

        return $next($request);
    }
}
