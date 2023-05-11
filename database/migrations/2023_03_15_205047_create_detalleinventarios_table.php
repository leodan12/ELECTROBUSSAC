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
        Schema::create('detalleinventarios', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('inventario_id');
            $table->unsignedBigInteger('company_id');
            $table->integer('stockempresa');
            $table->tinyInteger('status')->default('0')->comment('0=visible,1=oculto');
            $table->foreign('company_id')->references('id')->on('companies');//->onDelete('cascade');
            $table->foreign('inventario_id')->references('id')->on('inventarios');//->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalleinventarios');
    }
};
