<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * This migration updates the event_participants table by replacing the old
     * verification status values with a richer set of states and adding
     * columns to store the paths of uploaded verification documents. The new
     * statuses are:
     * - belum_verifikasi: peserta belum mengunggah berkas verifikasi
     * - sedang_diverifikasi: berkas sudah diunggah dan sedang diperiksa
     * - verifikasi_gagal: verifikasi ditolak oleh panitia
     * - verifikasi_berhasil: verifikasi diterima oleh panitia
     *
     * We drop the existing status_verifikasi column and recreate it with the
     * new enum values. This ensures a clean transition even if the column
     * previously existed with a smaller set of values.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('event_participants', function (Blueprint $table) {
            // Drop the old enum column if it exists
            if (Schema::hasColumn('event_participants', 'status_verifikasi')) {
                $table->dropColumn('status_verifikasi');
            }
        });

        Schema::table('event_participants', function (Blueprint $table) {
            // Recreate the status_verifikasi column with new values
            $table->enum('status_verifikasi', [
                'belum_verifikasi',
                'sedang_diverifikasi',
                'verifikasi_gagal',
                'verifikasi_berhasil'
            ])->default('belum_verifikasi');
            // Paths to uploaded documents; nullable because participants may not have uploaded yet
            $table->string('kk_path')->nullable();
            $table->string('akta_path')->nullable();
            $table->string('ktp_path')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * Drops the added columns and recreates the original status_verifikasi
     * column with the previous values. Note that any documents uploaded
     * will be lost upon rollback.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('event_participants', function (Blueprint $table) {
            $table->dropColumn(['kk_path', 'akta_path', 'ktp_path']);
            $table->dropColumn('status_verifikasi');
        });
        Schema::table('event_participants', function (Blueprint $table) {
            $table->enum('status_verifikasi', ['pending', 'verified', 'rejected'])->default('pending');
        });
    }
};