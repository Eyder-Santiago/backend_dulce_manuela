<?php

use App\Http\Controllers\AdminProductoController;
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

Route::group(['middleware' => ['validar-token']], function() {
    Route::resources(
        [
            'productos' => AdminProductoController::class,
        ],
        [
            // 'as' => 'admin',
            'except' => ['index', 'show', 'create', 'edit']
        ]
    );
});

