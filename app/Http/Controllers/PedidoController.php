<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PedidoController extends Controller
{
    public function listaPedidos(Request $request) {
        $pedidos = Pedido::orderBy('created_at');
        if ($request->has('idUsuario')) {
            $pedidos->where('user_id', $request->get('idUsuario'));
        }

        return $pedidos->get()->toJson();
    }

    public function crearPedido(Request $request) {
        $retorno = json_decode($request->getContent());

        $conteo = Pedido::where('user_id', $retorno->idUsuario)
            ->whereIn('estado', ['nuevo', 'pendiente'])
            ->count();

        if ($conteo > 0) {
            return response()->json([
                "exitoso" => 0,
                "mensaje" => "Tiene un pedido en curso. Debe pagar o cancelar el pedido para realizar uno nuevo",
            ]);
        }

        DB::beginTransaction();

        try {
            $pedido = new Pedido([
                'user_id' => $retorno->idUsuario,
                'estado' => "pagado",
                'medio_pago' => $retorno->medioPago,
                'informacion_pago' => $retorno->informacionPago,
            ]);

            $pedido->save();

            $cantidadTotal = 0;
            $precioTotal = 0;

            foreach ($retorno->detalles as $detalle) {
                $producto = Producto::find($detalle->idProducto);
                if (empty($producto)) {
                    throw new \Exception("El producto no existe");
                }

                if ($producto->stock < $detalle->cantidad) {
                    throw new \Exception("El producto no tiene suficiente stock");
                }

                $subtotal = $producto->precio * $detalle->cantidad;

                $detalle = new Detalle([
                    'pedido_id' => $pedido->id,
                    'producto_id' => $producto->id,
                    'cantidad' => $detalle->cantidad,
                    'precio_unitario' => $producto->precio,
                    'precio_total' => $subtotal
                ]);

                $detalle->save();

                $cantidadTotal += $detalle->cantidad;
                $precioTotal += $subtotal;

                $producto->stock -= $detalle->cantidad;
                $producto->save();
            }

            $pedido->cantidad_productos = $cantidadTotal;
            $pedido->precio_total = $precioTotal;
            $pedido->save();

            DB::commit();

            $pedido->load('detalles.producto');

            return response()->json([
                "exitoso" => 1,
                "pedido" => $pedido,
            ]);

        }
        catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                "exitoso" => 0,
                "mensaje" => $th->getMessage(),
            ]);
        }
    }

    public function cancelarPedido(Request $request) {
        $retorno = json_decode($request->getContent());
        $pedido = Pedido::where('user_id', $retorno->idUsuario)
            ->whereIn('estado', ['nuevo', 'pendiente'])
            ->first();
        
        if (empty($pedido)) {
            return response()->json([
                "exitoso" => 0,
                "mensaje" => "No hay pedidos para cancelar",
            ]);
        }

        $pedido->estado = "cancelado";
        $pedido->save();

        return response()->json([
            "exitoso" => 1,
            "pedido" => $pedido,
        ]);
    }

    public function pagarPedido(Request $request) {
        $retorno = json_decode($request->getContent());
        $pedido = Pedido::where('user_id', $retorno->idUsuario)
            ->whereIn('estado', ['nuevo', 'pendiente'])
            ->first();
        
        if (empty($pedido)) {
            return response()->json([
                "exitoso" => 0,
                "mensaje" => "No hay pedidos por pagar",
            ]);
        }

        $pedido->estado = "pagado";
        $pedido->medio_pago = $retorno->medioPago;
        $pedido->informacion_pago = $retorno->informacionPago;
        $pedido->save();

        return response()->json([
            "exitoso" => 1,
            "pedido" => $pedido,
        ]);
    }
}
