<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;

    protected $table = "pedido";

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'cantidad_productos',
        'precio_total',
        'medio_pago',
        'informacion_pago',
        'estado',
    ];

    public function usuario() {
        return $this->belongsTo(User::class);
    }

    public function detalles() {
        return $this->hasMany(DetallePedido::class);
    }

    protected $appends = ['idUsuario', 'cantidadProductos', 'precioTotal', 'medioPago', 'informacionPago', 'fecha'];

    public function getIdUsuarioAttribute() {
        return $this->attributes["user_id"];
    }

    public function getCantidadProductosAttribute() {
        return $this->attributes["cantidad_productos"];
    }

    public function getPrecioTotalAttribute() {
        return $this->attributes["precio_total"];
    }

    public function getMedioPagoAttribute() {
        return $this->attributes["medio_pago"];
    }

    public function getInformacionPagoAttribute() {
        return $this->attributes["informacion_pago"];
    }

    public function getFechaAttribute() {
        return $this->attributes["created_at"];
    }
}
