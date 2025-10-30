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
        Schema::create('event_participants', function (Blueprint $table) {
            $table->id();
            // Peserta yang mendaftar. Mengacu pada tabel users.
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            // Tahun event yang diikuti.
            $table->foreignId('tahunevent_id')->constrained('tahunevents')->onDelete('cascade');
            // Cabang lomba yang diikuti.
            $table->foreignId('cabang_id')->constrained('cabangs')->onDelete('cascade');
            // Golongan lomba yang diikuti.
            $table->foreignId('golongan_id')->constrained('golongans')->onDelete('cascade');
            // Status verifikasi pendaftaran: pending, verified, rejected.
            $table->enum('status_verifikasi', ['pending', 'verified', 'rejected'])->default('pending');
            // Catatan verifikasi dari panitia (operator/superadmin), misal alasan ditolak atau revisi.
            $table->text('catatan_verifikasi')->nullable();
            // Permintaan perubahan data dari peserta sebelum verifikasi final.
            $table->text('request_message')->nullable();
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
        Schema::dropIfExists('event_participants');
    }
};