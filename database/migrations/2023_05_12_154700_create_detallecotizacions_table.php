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
        Schema::create('detallecotizacions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cotizacion_id');
            $table->unsignedBigInteger('product_id'); 
            $table->integer('cantidad');
            $table->double('preciounitario');
            $table->double('preciounitariomo'); 
            $table->double('preciofinal');
            $table->double('servicio');
            $table->foreign('product_id')->references('id')->on('products');//->onDelete('cascade');
            $table->foreign('cotizacion_id')->references('id')->on('cotizacions')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detallecotizacions');
    }
};
