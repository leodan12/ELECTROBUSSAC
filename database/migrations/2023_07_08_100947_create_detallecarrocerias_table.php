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
        Schema::create('detallecarrocerias', function (Blueprint $table) {
            $table->id();
            $table->integer('cantidad');
            $table->string('unidad');
            $table->unsignedBigInteger('producto_id');
            $table->unsignedBigInteger('carroceria_id');
            $table->foreign('producto_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('carroceria_id')->references('id')->on('carrocerias')->onDelete('cascade');
            $table->tinyInteger('status')->default('0')->comment('0=visible,1=oculto');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detallecarrocerias');
    }
};
