<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
public function up(): void {
Schema::create('reservations', function (Blueprint $table) {
$table->id();
$table->foreignId('user_id')->constrained()->onDelete('cascade');
$table->foreignId('seance_id')->constrained()->onDelete('cascade');
$table->enum('statut', ['pending', 'confirmed', 'expired', 'canceled'])->default('pending');
$table->timestamp('expiration_at')->nullable();
$table->timestamps();
});
}

public function down(): void {
Schema::dropIfExists('reservations');
}
};
