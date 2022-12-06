<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_pedido', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pedido_id')->nullable(false);
            $table->unsignedBigInteger('producto_id')->nullable(false);
            $table->integer('cantidad')->nullable(false)->default(0);
            $table->integer('precio_unitario')->nullable(false)->default(0);
            $table->integer('precio_total')->nullable(false)->default(0);

            $table->foreign('pedido_id')->references('id')->on('pedido');
            $table->foreign('producto_id')->references('id')->on('producto');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detalle_pedido');
    }
};
