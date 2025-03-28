<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('sieges', function (Blueprint $table) {
            $table->foreignId('seance_id')->nullable()->constrained('seances')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('sieges', function (Blueprint $table) {
            $table->dropForeign(['seance_id']);
            $table->dropColumn('seance_id');
        });
    }

};
