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
        Schema::create('facturaproduccions', function (Blueprint $table) {
            $table->id();
            $table->string('fecha')->nullable();
            $table->string('numero')->nullable();
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
        Schema::dropIfExists('facturaproduccions');
    }
};
