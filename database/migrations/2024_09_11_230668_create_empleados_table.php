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
        Schema::create('empleados', function (Blueprint $table) {
            $table->bigIncrements('id_empleado');
            $table->integer('numero_empleado');
            $table->string('contrasena');
            $table->string('nombres');
            $table->string('apellido_paterno');
            $table->string('apellido_materno');
            $table->date('fecha_nacimiento');
            $table->bigInteger('puesto_id')->unsigned();
            $table->date('fecha_ingreso');
            $table->bigInteger('horario_id')->unsigned();
            $table->string('rol');
            $table->enum('estatus',['1','0']); // 0-> Inactivo y 1->Activo
            $table->foreign('puesto_id')->references('id_puesto')->on('puestos')->onDelete('cascade');
            $table->foreign('horario_id')->references('id_horario')->on('horarios')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empleados');
    }
};
