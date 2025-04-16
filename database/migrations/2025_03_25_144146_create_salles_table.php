<?php
// database/migrations/xxxx_xx_xx_create_salles_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('salles', function (Blueprint $table) {
            $table->id();
            $table->string('numero');
            $table->string('nom');
            $table->integer('capacite');
            $table->boolean('supporte_vip')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('salles');
    }
};
