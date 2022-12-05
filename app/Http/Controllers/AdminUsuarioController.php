<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

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
        $usuario->fill((array)$retorno);
        $usuario->birth_date = $retorno->birthDate;
        $usuario->num_celular = $retorno->numCelular;

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
