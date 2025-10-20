<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Peserta;
use App\Models\Desa;
use App\Models\Tahunevent;
use App\Models\DetailEvent;

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
        Desa::firstOrCreate(['nama' => 'Desa Sukamaju']);
        Desa::firstOrCreate(['nama' => 'Desa Sukamulya']);

        $desa1 = Desa::first();

        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password'),
                'role' => 'administrator',
                'terms' => now()->toDateTimeString(), // pakai string karena migrasi kamu string
                'desa_id' => $desa1->id,               // migrasi kamu mewajibkan desa_id (isi aja salah satu)
                'tanggal_lahir' => '1990-01-01',
                'nik' => '1000000000000001',
                'foto' => null,
            ]
        );

        $desa2 = Desa::where('nama', 'Desa Sukamaju')->first();

        User::updateOrCreate(
            ['email' => 'admindesa@example.com'],
            [
                'name' => 'Admin Desa Sukamaju',
                'password' => Hash::make('password'),
                'role' => 'admin_desa',
                'terms' => now()->toDateTimeString(),
                'desa_id' => $desa2->id,
                'tanggal_lahir' => '1995-05-05',
                'nik' => '1000000000000002',
                'foto' => null,
            ]
        );
        $desa3 = Desa::where('nama', 'Desa Sukamulya')->first();

        User::updateOrCreate(
            ['email' => 'peserta@example.com'],
            [
                'name' => 'Samuel',
                'password' => Hash::make('password'),
                'role' => 'peserta',
                'terms' => now()->toDateTimeString(),
                'desa_id' => $desa3->id,
                'tanggal_lahir' => '2005-02-15',
                'nik' => '1000000000000003',
                'foto' => null,
            ]
        );

        Tahunevent::updateOrCreate(
            ['tahun_event' => 2025],
            ['slug' => 'MTQ Ke-31', 'is_active' => true]
        );

        Tahunevent::updateOrCreate(
            ['tahun_event' => 2027],
            ['slug' => 'MTQ Ke-33', 'is_active' => false]
        );

        $te2025 = TahunEvent::where('tahun_event', 2025)->first();
        $te2027 = TahunEvent::where('tahun_event', 2027)->first();

        if ($te2025) {
            DetailEvent::updateOrCreate(
                ['tahunevent_id' => $te2025->id],
                [
                    'nama_kegiatan_aktif' => 'MTQ Cabang Tilawah',
                    'waktu_pelaksanaan_mulai' => '2025-03-10',
                    'waktu_pelaksanaan_selesai' => '2025-03-14',
                    'tempat_pelaksanaan' => 'Gedung Serbaguna Kabupaten',
                    'batas_umur_tanggal_patokan' => '2008-01-01',

                    'pendaftaran_mulai' => '2025-01-10 08:00:00',
                    'pendaftaran_selesai' => '2025-01-20 23:59:00',

                    'verif1_mulai' => '2025-01-22 08:00:00',
                    'verif1_selesai' => '2025-01-24 17:00:00',

                    'verif2_mulai' => '2025-01-26 08:00:00',
                    'verif2_selesai' => '2025-01-28 17:00:00',

                    'sanggah_mulai' => '2025-01-29 08:00:00',
                    'sanggah_selesai' => '2025-01-30 17:00:00',

                    'pengumuman_verifikasi' => '2025-02-01 10:00:00',
                    'technical_meeting' => '2025-03-05 09:00:00',

                    'logo_path' => null,
                ]
            );
        }

        if ($te2027) {
            DetailEvent::updateOrCreate(
                ['tahunevent_id' => $te2027->id],
                [
                    'nama_kegiatan_aktif' => 'MTQ Cabang Hafalan',
                    'waktu_pelaksanaan_mulai' => '2027-04-11',
                    'waktu_pelaksanaan_selesai' => '2027-04-15',
                    'tempat_pelaksanaan' => 'GOR Kecamatan',
                    'batas_umur_tanggal_patokan' => '2008-01-01',

                    'pendaftaran_mulai' => '2027-02-01 08:00:00',
                    'pendaftaran_selesai' => '2027-02-15 23:59:00',

                    'verif1_mulai' => '2027-02-17 08:00:00',
                    'verif1_selesai' => '2027-02-19 17:00:00',

                    'verif2_mulai' => '2027-02-21 08:00:00',
                    'verif2_selesai' => '2027-02-23 17:00:00',

                    'sanggah_mulai' => '2027-02-24 08:00:00',
                    'sanggah_selesai' => '2027-02-25 17:00:00',

                    'pengumuman_verifikasi' => '2027-03-01 10:00:00',
                    'technical_meeting' => '2027-04-07 09:00:00',

                    'logo_path' => null,
                ]
            );
        }

    }
}
