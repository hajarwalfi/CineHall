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
        Schema::create('sieges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('salle_id')->constrained('salles')->onDelete('cascade');
            $table->string('numero');
            $table->enum('statut', ['disponible', 'réservé'])->default('disponible');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sieges');
    }
};
