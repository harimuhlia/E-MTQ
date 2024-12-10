<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ketentuanusias', function (Blueprint $table) {
            $table->id();
            $table->string('nama_cabang');
            $table->string('nama_golongan');
            $table->date('usia_minimal');
            $table->date('usia_maksimal');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ketentuanusias');
    }
};
