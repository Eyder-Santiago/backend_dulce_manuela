<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetallePedido extends Model
{
    use HasFactory;
    protected $table = "detalle_pedido";

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'pedido_id',
        'producto_id',
        'cantidad',
        'precio_unitario',
        'precio_total',
    ];

    public function pedido() {
        return $this->belongsTo(Pedido::class);
    }

    public function producto() {
        return $this->belongsTo(Producto::class);
    }

    protected $appends = ['idPedido', 'idProducto', 'precioUnitario', 'precioTotal'];

    public function getIdPedidoAttribute() {
        return $this->attributes["pedido_id"];
    }

    public function getIdProductoAttribute() {
        return $this->attributes["producto_id"];
    }

    public function getPrecioUnitarioAttribute() {
        return $this->attributes["precio_unitario"];
    }

    public function getPrecioTotalAttribute() {
        return $this->attributes["precio_total"];
    }
}
