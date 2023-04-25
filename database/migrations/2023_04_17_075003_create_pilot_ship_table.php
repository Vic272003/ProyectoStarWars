<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Creamos la tabla pilot_ship añadiéndole las claves foráneas correspondientes
     */
    public function up(): void
    {
        Schema::create('pilot_ship', function (Blueprint $table) {
        $table->id();
        $table->bigInteger('pilot_id');
        $table->bigInteger('starship_id');
        $table->timestamps();

        
        });
        Schema::table('pilot_ship', function (Blueprint $table) {
            $table->foreign('pilot_id')->references('id')->on('pilots')->onDelete('cascade');
            $table->foreign('starship_id')->references('id')->on('starships')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pilot_ship');
    }
};
