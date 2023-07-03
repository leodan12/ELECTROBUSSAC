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
        Schema::create('ingresos', function (Blueprint $table) {
            $table->id();
            $table->string('moneda');
            $table->string('factura')->nullable();
            $table->string('formapago');
            $table->string('observacion')->nullable();
            $table->double('costoventa');
            $table->double('tasacambio')->nullable();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('cliente_id');
            $table->string('fecha');
            $table->string('fechav')->nullable();
            $table->string('pagada');

            $table->string('nrooc')->nullable();
            $table->string('guiaremision')->nullable();
            $table->string('fechapago')->nullable();
            $table->double('acuenta1')->nullable();
            $table->double('acuenta2')->nullable();
            $table->double('acuenta3')->nullable();
            $table->double('saldo')->nullable();
            $table->double('montopagado')->nullable();

            $table->foreign('company_id')->references('id')->on('companies');//->onDelete('cascade');
            $table->foreign('cliente_id')->references('id')->on('clientes');//->onDelete('cascade');
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ingresos');
    }
};
