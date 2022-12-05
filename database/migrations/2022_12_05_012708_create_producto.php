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
        Schema::create('producto', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 30)->nullable(false)->default('');
            $table->integer('precio')->nullable(false)->default(0);
            $table->integer('stock')->nullable(false)->default(0);
            $table->string('imagen_url', 200)->nullable(false)->default('');
            $table->string('descripcion', 200)->nullable(false)->default('');
            $table->integer('estado')->nullable(false)->default(0);
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
        Schema::dropIfExists('table_producto');
    }
};
