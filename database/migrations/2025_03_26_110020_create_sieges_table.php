<?php
// database/migrations/xxxx_xx_xx_create_sieges_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sieges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('salle_id')->constrained()->onDelete('cascade');
            $table->string('rangee');
            $table->integer('numero');
            $table->enum('type', ['Standard', 'VIP', 'Couple'])->default('Standard');
            $table->unsignedBigInteger('est_couple_avec')->nullable();
            $table->timestamps();
            $table->unique(['salle_id', 'rangee', 'numero']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('sieges');
    }
};
