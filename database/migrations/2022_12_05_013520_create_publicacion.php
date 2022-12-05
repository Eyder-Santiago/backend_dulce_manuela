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
        Schema::create('publicacion', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('producto_id');
            $table->mediumText('descripcion')->nullable(false)->default('');
            $table->integer('likes')->nullable(false)->default(0);
            $table->mediumText('comentario')->nullable(false)->default('');
            $table->integer('estado')->nullable(false)->default(0);
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
        Schema::dropIfExists('publicacion');
    }
};
