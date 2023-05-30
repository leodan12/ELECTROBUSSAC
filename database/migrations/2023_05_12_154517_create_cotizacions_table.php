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
        Schema::create('cotizacions', function (Blueprint $table) {
            $table->id(); 
            $table->string('fecha'); 
            $table->string('fechav');
            $table->string('numero'); 
            $table->string('moneda');  
            $table->string('formapago');
            $table->string('persona')->nullable();
            $table->string('observacion')->nullable();
            $table->string('diascredito')->nullable();
            $table->double('costoventasinigv');
            $table->double('costoventaconigv')->nullable();
            $table->double('tasacambio')->nullable();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('cliente_id');
            $table->string('vendida');
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
        Schema::dropIfExists('cotizacions');
    }
};
