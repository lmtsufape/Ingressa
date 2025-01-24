<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;

class AtualizarDados
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();
        if($user->role == User::ROLE_ENUM['candidato'] && $user->candidato->atualizar_dados)
        {
            $candidato = $user->candidato;
            $inscricao = $candidato->inscricoes->last();
            return redirect()->route('candidato.edit', compact('candidato', 'inscricao'));
        }

        return $next($request);
    }
}
