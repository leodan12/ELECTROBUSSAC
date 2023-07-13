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
        Schema::create('enviocarros', function (Blueprint $table) {
            $table->id();
            $table->string('fecha');
            $table->integer('cantidad');
            $table->string('observacion');
            $table->unsignedBigInteger('carro_id');
            $table->foreign('carro_id')->references('id')->on('carros');
            $table->tinyInteger('status')->default('0')->comment('0=visible,1=oculto');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enviocarros');
    }
};
