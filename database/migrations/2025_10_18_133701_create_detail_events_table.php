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
        Schema::create('detail_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tahunevent_id')->constrained()->cascadeOnDelete();
            $table->string('nama_kegiatan_aktif');
            $table->date('waktu_pelaksanaan_mulai')->nullable();
            $table->date('waktu_pelaksanaan_selesai')->nullable();
            $table->string('tempat_pelaksanaan')->nullable();
            $table->date('batas_umur_tanggal_patokan')->nullable();
            $table->dateTime('pendaftaran_mulai')->nullable();
            $table->dateTime('pendaftaran_selesai')->nullable();
            $table->dateTime('verif1_mulai')->nullable();
            $table->dateTime('verif1_selesai')->nullable();
            $table->dateTime('verif2_mulai')->nullable();
            $table->dateTime('verif2_selesai')->nullable();
            $table->dateTime('sanggah_mulai')->nullable();
            $table->dateTime('sanggah_selesai')->nullable();
            $table->dateTime('pengumuman_verifikasi')->nullable();
            $table->dateTime('technical_meeting')->nullable();
            $table->string('logo_path')->nullable();
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
        Schema::dropIfExists('detail_events');
    }
};
