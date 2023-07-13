<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('produccioncarros', function (Blueprint $table) {
            $table->id();
            $table->integer('cantidad');
            $table->string('nombre');
            $table->unsignedBigInteger('modelo_id');
            $table->unsignedBigInteger('carroceria_id');
            $table->unsignedBigInteger('cliente_id');
            $table->foreign('cliente_id')->references('id')->on('clientes');
            $table->foreign('modelo_id')->references('id')->on('modelocarros');
            $table->foreign('carroceria_id')->references('id')->on('carrocerias');
            $table->tinyInteger('status')->default('0')->comment('0=visible,1=oculto');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produccioncarros');
    }
};
