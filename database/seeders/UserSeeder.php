<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Desa;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $desa1 = Desa::first();
        $desa2 = Desa::skip(1)->first() ?? $desa1;

        // NOTE: users.status_verifikasi enum is: pending | verified | rejected
        User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@emtq.test',
            'password' => Hash::make('password'),
            'role' => 'administrator',
            'desa_id' => $desa1->id,
            'tanggal_lahir' => '2000-01-01',
            'nik' => '3200000000000001',
            'status_verifikasi' => 'verified',
        ]);

        User::create([
            'name' => 'Admin Desa',
            'email' => 'admindesa@emtq.test',
            'password' => Hash::make('password'),
            'role' => 'admin_desa',
            'desa_id' => $desa2->id,
            'tanggal_lahir' => '2001-01-01',
            'nik' => '3200000000000002',
            'status_verifikasi' => 'verified',
        ]);

        User::create([
            'name' => 'Peserta Dummy',
            'email' => 'peserta@emtq.test',
            'password' => Hash::make('password'),
            'role' => 'peserta',
            'desa_id' => $desa2->id,
            'tanggal_lahir' => '2005-01-01',
            'nik' => '3200000000000003',
            'status_verifikasi' => 'pending',
        ]);
    }
}
