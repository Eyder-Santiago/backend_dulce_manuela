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
        $xToken = json_decode($request->header('x-token'));
        $token = Token::where('user_id', $xToken->idUsuario)
            ->where('valor', $xToken->valor)
            ->whereRaw('created_at >= cast((now() + interval -(30) day) as date)')
            ->take(1)
            ->first();
        if (empty($token)) {
            return response()->json(['error' => ' Token no válido'], 401);
        }
        
        return $next($request);
    }
}
