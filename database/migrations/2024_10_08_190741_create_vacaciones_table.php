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
        Schema::create('vacaciones', function (Blueprint $table) {
            $table->bigIncrements('id_vacacion');
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->integer('dias_tomados');
            $table->string('observaciones');
            $table->string('pdf');
            $table->enum('estatus',['1','0']);
            $table->bigInteger('empleado_id')->unsigned();
            $table->foreign('empleado_id')->references('id_empleado')->on('empleados')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vacaciones');
    }
};
