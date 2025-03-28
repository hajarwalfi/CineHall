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
        Schema::table('sieges', function (Blueprint $table) {
            $table->dropForeign(['seance_id']); // Use an array for the column name
            $table->dropColumn('seance_id'); // If you want to remove the column too
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sieges', function (Blueprint $table) {
            //
        });
    }
};
