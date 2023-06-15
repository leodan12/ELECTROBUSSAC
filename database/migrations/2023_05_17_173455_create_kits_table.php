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
        Schema::create('kits', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('kitproduct_id');
            $table->integer('cantidad');
            $table->double('preciounitario');
            $table->double('preciounitariomo');
            $table->double('preciofinal');
            $table->integer('cantidad2')->nullable();
            $table->double('precio2')->nullable();
            $table->integer('cantidad3')->nullable();
            $table->double('precio3')->nullable();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('kitproduct_id')->references('id')->on('products'); //->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kits');
    }
};
