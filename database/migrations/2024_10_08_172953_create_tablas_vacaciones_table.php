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
        Schema::create('tablas_vacaciones', function (Blueprint $table) {
            $table->bigIncrements('id_dias');
            $table->float('ingreso');
            $table->float('termino');
            $table->integer('dias_disponibles');
            $table->integer('acumulado');
            $table->bigInteger('ley_id')->unsigned();
            $table->foreign('ley_id')->references('id_ley')->on('leyes_vacaciones')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tablas_vacaciones');
    }
};
