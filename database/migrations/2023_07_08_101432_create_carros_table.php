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
        Schema::create('carros', function (Blueprint $table) {
            $table->id();
            $table->string('chasis');
            $table->double('porcentajedescuento');
            $table->string('redenviada');
            $table->string('bonificacion');
            $table->string('mesbonificacion');
            $table->unsignedBigInteger('produccioncarro_id');
            $table->foreign('produccioncarro_id')->references('id')->on('produccioncarros')->onDelete('cascade');
            $table->tinyInteger('status')->default('0')->comment('0=visible,1=oculto');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carros');
    }
};
