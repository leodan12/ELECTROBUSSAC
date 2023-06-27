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
        Schema::create('detalleingresos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ingreso_id');
            $table->unsignedBigInteger('product_id');
            $table->integer('cantidad');
            $table->string('observacionproducto')->nullable();
            $table->double('preciounitario');
            $table->double('preciounitariomo');
            $table->double('servicio');
            $table->double('preciofinal');
            $table->foreign('product_id')->references('id')->on('products');//->onDelete('cascade');
            $table->foreign('ingreso_id')->references('id')->on('ingresos')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalleingresos');
    }
};
