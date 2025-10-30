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
        Schema::create('golongans', function (Blueprint $table) {
            $table->id();
            // Nama golongan, misal "Surah Al-Falaq", "Surah Al-Quraisy", dsb.
            $table->string('nama');
            // Relasi ke cabang (tiap golongan merupakan bagian dari sebuah cabang tertentu)
            $table->foreignId('cabang_id')->constrained('cabangs')->onDelete('cascade');
            // Batas usia maksimal peserta untuk golongan ini (dalam tahun). Nullable karena beberapa golongan mungkin tidak memiliki batas khusus.
            $table->integer('max_usia')->nullable();
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
        Schema::dropIfExists('golongans');
    }
};
