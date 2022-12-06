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
        Schema::create('pedido', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable(false);
            $table->integer('cantidad_productos')->nullable(false)->default(0);
            $table->integer('precio_total')->nullable(false)->default(0);
            $table->string('medio_pago')->nullable(true)->default("");
            $table->mediumText('informacion_pago')->nullable(true);
            $table->string("estado")->nullable(false)->default("nuevo");
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pedido');
    }
};
