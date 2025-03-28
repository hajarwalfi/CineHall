<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // database/migrations/YYYY_MM_DD_add_siege_ids_to_reservations_table.php
    public function up()
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->json('siege_ids')->nullable();
        });
    }

    public function down()
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropColumn('siege_ids');
        });
    }

};
