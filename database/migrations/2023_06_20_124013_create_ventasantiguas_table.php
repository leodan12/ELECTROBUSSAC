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
        Schema::create('ventasantiguas', function (Blueprint $table) {
            $table->id();
            $table->string('tipo')->nullable();
            $table->string('producto')->nullable();
            $table->string('preciounitatiosinigv')->nullable();
            $table->string('preciototalsinigv')->nullable();
            $table->string('moneda')->nullable();
            $table->integer('cantidad')->nullable();
            $table->string('unidad')->nullable();
            $table->string('factura')->nullable();
            $table->string('fecha')->nullable();
            $table->string('detalle')->nullable();
            $table->string('cliente')->nullable();
            $table->string('empresa')->nullable();
            $table->string('devolucion')->nullable();
            $table->integer('codigo')->nullable();
            $table->string('boletafactura')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ventasantiguas');
    }
};
