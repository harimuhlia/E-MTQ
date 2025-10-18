<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Peserta;
use App\Models\Desa;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        $desa = Desa::create([
            'nama_desa' => 'Desa Sukamaju',
        ]);
        $admin = User::create([
            'name' => 'Administrator',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'administrator',
            'terms' => now()->toDateTimeString(),
        ]);
        $adminDesa = User::create([
            'name' => 'Admin Desa Sukamaju',
            'email' => 'admindesa@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin_desa',
            'terms' => now()->toDateTimeString(),
        ]);
        $pesertaUser = User::create([
            'name' => 'Peserta Samuel',
            'email' => 'peserta@example.com',
            'password' => Hash::make('password'),
            'role' => 'peserta',
            'terms' => now()->toDateTimeString(),
        ]);
        Peserta::create([
            'user_id' => $pesertaUser->id,
            'desa_id' => $desa->id,
            'password' => Hash::make('password'), // kalau memang tabel pesertas tetap punya kolom password
            'tanggal_lahir' => '2005-02-15',
            'nik' => '3201234567890123',
            'foto' => null,
        ]);
    }
}
