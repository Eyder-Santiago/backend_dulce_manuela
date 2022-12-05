<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $table = "producto";

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nombre',
        'precio',
        'stock',
        'url_imagen',
        'descripcion',
        'estado',
    ];

    protected $appends = ['urlImagen'];

    public function getUrlImagenAttribute()
    {
        return $this->attributes["url_imagen"];
    }
}
