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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            // Email tidak lagi diberikan constraint unique global karena email
            // hanya harus unik per event. Unik per event ditangani melalui
            // validasi aplikasi di PesertaController.
            $table->string('email');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('role', ['administrator', 'admin_desa', 'peserta']);
            $table->foreignId('desa_id')->constrained();
            $table->date('tanggal_lahir');
            // NIK peserta tidak lagi memiliki constraint unique di tabel users. Peserta
            // boleh mendaftar pada beberapa event yang berbeda dengan NIK yang sama.
            // Oleh karena itu, kita sengaja tidak menambahkan constraint unique di sini.
            $table->string('nik', 16);
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
};
