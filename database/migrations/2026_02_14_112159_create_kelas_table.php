<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('kelas', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kelas');
            $table->unsignedBigInteger('wali_kelas_id')->nullable();
            $table->timestamps();
            
            // Foreign key will be added after guru table is created
        });
    }

    public function down()
    {
        Schema::dropIfExists('kelas');
    }
};