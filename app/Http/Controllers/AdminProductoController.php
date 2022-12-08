<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;

class AdminProductoController extends Controller
{
    private $rutaImagenes = "/almacenamiento/imagenes/productos/";
    
    private function crearCarpetas($ruta) {
        $partes = explode("/", $ruta);
        $rutaCarpeta = "";
        foreach ($partes as $parte) {
            if ($parte === "") {
                continue;
            }

            $rutaCarpeta .= "/" . $parte;

            if (!is_dir(public_path($rutaCarpeta))) {
                mkdir(public_path($rutaCarpeta));
            }
        }
    }

    private function obtenerDatosImagen($imagen, $ruta) {
        list($encabezado, $contenido) = explode(",", $imagen);
        $extension = explode(";", $encabezado);
        $extension = explode("/", reset($extension));
        return [
            $ruta . uniqid() . "." . end($extension),
            $contenido
        ];
    }

    private function guardarImagen($imagen, $contenido) {
        $rutaArchivo = public_path($imagen);        
        file_put_contents($rutaArchivo, base64_decode($contenido));        
    }

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
        $producto->fill((array)$retorno);
        if (!empty($retorno->imagen)) {
            list($nombreArchivo, $contenido) = $this->obtenerDatosImagen($retorno->imagen, $this->rutaImagenes);
            $this->crearCarpetas($this->rutaImagenes);
            $this->guardarImagen($nombreArchivo, $contenido);
            $producto->url_imagen = asset($nombreArchivo);
        }
        else {
            $producto->url_imagen = $retorno->urlImagen;
        }

        $producto->save();
        $retorno->recibido = "OK";
        return response()->json($producto);
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
        $producto->fill((array)$retorno);

        if (!empty($retorno->imagen)) {
            list($nombreArchivo, $contenido) = $this->obtenerDatosImagen($retorno->imagen, $this->rutaImagenes);
            $this->crearCarpetas($this->rutaImagenes);
            $this->guardarImagen($nombreArchivo, $contenido);
            $producto->url_imagen = asset($nombreArchivo);
        }
        else {
            $producto->url_imagen = $retorno->urlImagen;
        }

        $producto->save();
        return response()->json($producto);
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
