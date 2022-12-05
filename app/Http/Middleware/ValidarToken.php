<?php

namespace App\Http\Middleware;

use App\Models\Token;
use Closure;
use Illuminate\Http\Request;

class ValidarToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $usuarioId = $request->get('id_usuario');
        $valor = $request->get('valor');
        $token = Token::where('user_id', $usuarioId)
            ->where('valor', $valor)
            ->whereRaw('created_at >= cast((now() + interval -(30) day) as date) limit 0, 1')
            ->take(1)
            ->first();
        if (empty($token)) {
            return response()->json(['error' => ' Token no válido'], 401);
        }
        
        return $next($request);
    }
}
