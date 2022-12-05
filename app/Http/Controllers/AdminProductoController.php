<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;

class AdminProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $query = Producto::query();
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
        $producto = new Producto();
        $producto->nombre= $retorno->nombre;
        $producto->precio= $retorno->precio;
        $producto->stock= $retorno->stock;
        $producto->imagen_url= $retorno->urlImagen;
        $producto->descripcion= $retorno->descripcion;
        $producto->estado= $retorno->estado;

        $producto->save();
        $retorno->recibido = "OK";
        return response()->json($retorno);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Producto $producto)
    {
        $retorno = json_decode($request->getContent());
        $retorno->recibido = "OK";

        $producto->nombre= $retorno->nombre;
        $producto->precio= $retorno->precio;
        $producto->stock= $retorno->stock;
        $producto->imagen_url= $retorno->urlImagen;
        $producto->descripcion= $retorno->descripcion;
        $producto->estado= $retorno->estado;

        $producto->save();
        return response()->json($retorno);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function destroy(Producto $producto)
    {
        $producto->delete();
    }
}
