<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    public function index(Request $request) {
        $query = Producto::query();
        if ($request->has('param')) {
            $query->where('nombre', 'like', "%" . $request->get("param") . "%");
        }

        return $query->get()->toJson();
    }
}
