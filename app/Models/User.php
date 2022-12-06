<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nombre',
        'apellido',
        'correo',
        'password',
        'direccion',
        'birth_date',
        'num_celular',
        'estado',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        //'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends = ['birthDate', 'numCelular'];

    public function getBirthDateAttribute()
    {
        return $this->attributes["birth_date"];
    }

    public function getNumCelularAttribute()
    {
        return $this->attributes["num_celular"];
    }

    public function tokens() {
        return $this->hasMany(Token::class);
    }

    public function pedidos() {
        return $this->hasMany(Pedido::class);
    }
}
