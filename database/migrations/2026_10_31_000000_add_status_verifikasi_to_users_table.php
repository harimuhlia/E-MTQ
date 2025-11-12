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
        Schema::table('users', function (Blueprint $table) {
            // Tambahkan kolom status_verifikasi dengan nilai default 'pending'
            $table->enum('status_verifikasi', ['pending', 'verified', 'rejected'])->default('pending');
            // Kolom catatan untuk menyimpan catatan verifikasi (optional)
            $table->text('catatan_verifikasi')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['status_verifikasi', 'catatan_verifikasi']);
        });
    }
};