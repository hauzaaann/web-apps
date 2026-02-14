<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Add foreign key to kelas table
        Schema::table('kelas', function (Blueprint $table) {
            $table->foreign('wali_kelas_id')->references('id')->on('guru')->onDelete('set null');
        });

        // Add foreign key to mapel table
        Schema::table('mapel', function (Blueprint $table) {
            $table->foreign('guru_id')->references('id')->on('guru')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('kelas', function (Blueprint $table) {
            $table->dropForeign(['wali_kelas_id']);
        });

        Schema::table('mapel', function (Blueprint $table) {
            $table->dropForeign(['guru_id']);
        });
    }
};