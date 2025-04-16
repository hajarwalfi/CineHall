<?php
// database/migrations/xxxx_xx_xx_create_reservations_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seance_id')->constrained()->onDelete('cascade');
            $table->foreignId('siege_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('statut', ['Réservé', 'Payé', 'Annulé'])->default('Réservé');
            $table->string('code', 8)->unique()->nullable();
            $table->timestamp('expire_at')->nullable();
            $table->timestamps();
            $table->unique(['seance_id', 'siege_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('reservations');
    }
};
