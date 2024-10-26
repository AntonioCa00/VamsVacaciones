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
        Schema::create('areas', function (Blueprint $table) {
            $table->bigIncrements('id_area');
            $table->string('nombre');
            $table->string('descripcion');
            $table->bigInteger('division_id')->unsigned();
            $table->foreign('division_id')->references('id_division')->on('divisiones')->onDelete('cascade');
            $table->enum('estatus',['1','0']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('areas');
    }
};
