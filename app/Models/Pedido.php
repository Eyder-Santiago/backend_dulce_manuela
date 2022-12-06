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
}
