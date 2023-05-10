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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id');
            $table->string('nombre');
            $table->string('codigo')->nullable();
            $table->string('unidad');
            $table->string('und')->nullable();
            $table->string('moneda')->nullable();
            $table->double('maximo')->nullable();
            $table->double('minimo')->nullable();
            $table->double('NoIGV');
            $table->double('SiIGV');
            $table->tinyInteger('status')->default('0')->comment('0=visible,1=oculto');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
