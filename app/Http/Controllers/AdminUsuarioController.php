<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminUsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = User::where("estado", 1);
        if ($request->has('param')) {
            $query->where('nombre', 'like', "%" . $request->get("param") . "%");
        }

        return $query->get()->toJson();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $retorno = json_decode($request->getContent());
        
        $usuario = new User();
        $usuario->fill((array)$retorno);
        $usuario->birth_date = $retorno->birthDate;
        $usuario->num_celular = $retorno->numCelular;
        $usuario->password = Hash::make($retorno->password);

        $usuario->save();        
        $retorno->recibido = "OK";
        return response()->json($retorno);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $usuario)
    {
        $retorno = json_decode($request->getContent());
        $passwordAnt = $usuario->password;
        $usuario->fill((array)$retorno);
        $usuario->birth_date = $retorno->birthDate;
        $usuario->num_celular = $retorno->numCelular;
        if (!empty($retorno->password)) {
            $usuario->password = Hash::make($retorno->password);
        }
        else {
            $usuario->password = $passwordAnt;
        }
        
        $usuario->save();        
        $retorno->recibido = "OK";
        return response()->json($retorno);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $usuario)
    {
        $usuario->tokens()->delete();
        $usuario->delete();
    }
}
