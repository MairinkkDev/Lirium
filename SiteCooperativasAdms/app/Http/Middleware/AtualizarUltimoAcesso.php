<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AtualizarUltimoAcesso
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $usuario = Auth::user();

            if (is_null($usuario->ultimoAcessoUsuario) || $usuario->ultimoAcessoUsuario->lt(now()->subMinute())) {
                $usuario->timestamps = false;
                $usuario->ultimoAcessoUsuario = now();
                $usuario->save();
            }
        }

        return $next($request);
    }
}