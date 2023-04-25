<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Creamos la tabla starships y sus atributos
     */
    public function up(): void
    {
        Schema::create('starships', function (Blueprint $table) {
        $table->bigInteger('id');
        $table->string('name');
        $table->string('model');
        $table->string('manufacturer');
        $table->string('cost_in_credits');
        $table->string('length');
        $table->string('max_atmosphering_speed');
        $table->string('crew');
        $table->string('passengers');
        $table->string('cargo_capacity');
        $table->string('consumables');
        $table->string('hyperdrive_rating');
        $table->string('MGLT');
        $table->string('starship_class');
        $table->string('created');
        $table->string('edited');
        $table->string('url');
        $table->timestamps();
        $table->primary('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('starships');
    }
};
