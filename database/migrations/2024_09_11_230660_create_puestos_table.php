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
        Schema::create('puestos', function (Blueprint $table) {
            $table->bigIncrements('id_puesto');
            $table->string('nombre');
            $table->string('descripcion');
            $table->bigInteger('area_id')->unsigned();
            $table->foreign('area_id')->references('id_area')->on('areas')->onDelete('cascade');
            $table->enum('estatus',['1','0']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('puestos');
    }
};
