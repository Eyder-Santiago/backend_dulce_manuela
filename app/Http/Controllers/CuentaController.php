<?php

namespace App\Http\Controllers;

use App\Models\Token;
use App\Models\User;
use Illuminate\Http\Request;

class CuentaController extends Controller
{
    public function hacerLogin(Request $request) {
        $retorno = json_decode($request->getContent());
        $retorno->recibido = 'OK';

        $email = $retorno->email;
        $password = $retorno->password;
        $user = User::where('email', $email)->first();
        if (empty($user)) {
            $retorno->respuesta = [
                'valida' => 'N',
                'mensaje' => 'El correo no es válido',
            ];
            return response()->json($retorno);
        }

        if ($user->estado == 0) {
            $retorno->respuesta = [
                'valida' => 'N',
                'mensaje' => 'El usuario no es válido',
            ];
            return response()->json($retorno);
        }

        if ($user->password != $password) {
            $retorno->respuesta = [
                'valida' => 'N',
                'mensaje' => 'El password no es válido',
            ];
            return response()->json($retorno);
        }

        Token::where('user_id', $user->id)->delete();

        $token = new Token();
        $token->user_id = $user->id;
        $token->valor = uniqid();
        $token->save();

        $retorno->respuesta = [
            'valida' => 'S',
            'mensaje' => 'OK',
            'token' => $token->valor,
            'id_usuario' => $user->id,
        ];

        return response()->json($retorno);
    }
}
