<?php

use App\Http\Controllers\AdminProductoController;
use App\Http\Controllers\AdminUsuarioController;
use App\Http\Controllers\CuentaController;
use App\Http\Controllers\ProductoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', [CuentaController::class, 'hacerLogin'])->name('login');
Route::any('/productos', [ProductoController::class, 'index'])->name('productos.index');
Route::any('/usuarios', [CuentaController::class, 'listaUsuarios'])->name('usuarios.index');
Route::post('/registrar', [CuentaController::class, 'registrarUsuario'])->name('usuario.registrar');
Route::put('/password', [CuentaController::class, 'actualizarPassword'])->name('actualizar.password');
Route::put('/perfil/{usuario}', [CuentaController::class, 'actualizarUsuario'])->name('actualizar.password');

Route::group(['middleware' => ['validar-token'], 'prefix' => 'admin'], function() {
    Route::resources(
        [
            'productos' => AdminProductoController::class,
            'usuarios' => AdminUsuarioController::class,
        ],
        [
            //'as' => 'admin',
            'except' => ['show', 'create', 'edit']
        ]
    );
});
