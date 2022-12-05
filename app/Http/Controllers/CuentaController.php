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

        //Token::where('user_id', $user->id)->delete();
        $user->tokens()->delete();

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

    public function listaUsuarios(Request $request) {
        $query = User::where("estado", 1);
        if ($request->has('param')) {
            $query->where('nombre', 'like', "%" . $request->get("param") . "%");
        }

        return $query->get()->toJson();
    }

    public function registrarUsuario(Request $request) {
        $retorno = json_decode($request->getContent());
        
        $usuario = new User();
        $usuario->nombre = $retorno->nombre;
        $usuario->apellido = $retorno->apellido;
        $usuario->email = $retorno->email;
        $usuario->direccion = $retorno->direccion;
        $usuario->birth_date = $retorno->birthDate;
        $usuario->num_celular = $retorno->numCelular;
        $usuario->password = $retorno->password;
        $usuario->estado= $retorno->estado;

        $usuario->save();        
        $retorno->recibido = "OK";
        return response()->json($retorno);
    }

    public function actualizarPassword(Request $request) {
        $retorno = json_decode($request->getContent());
        $retorno->recibido = "OK";

        $usuario = User::find($retorno->id);
        $usuario->password = $retorno->password;
        $usuario->save();

        return response()->json($retorno);
    }

    public function actualizarUsuario(Request $request, User $usuario) {
        $retorno = json_decode($request->getContent());
        $usuario->fill((array)$retorno);
        $usuario->birth_date = $retorno->birthDate;
        $usuario->num_celular = $retorno->numCelular;

        $usuario->save();        
        $retorno->recibido = "OK";
        return response()->json($retorno);
    }
}
