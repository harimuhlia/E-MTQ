<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DetailEvent;

class DetailEventSeeder extends Seeder
{
    public function run(): void
    {
        // Event 1: dibuat agar statusnya "Selesai" (pelaksanaan sudah lewat)
        DetailEvent::create([
            'slug' => 'emtq-ke-20',
            'nama_kegiatan_aktif' => 'E-MTQ Ke-20',
            'waktu_pelaksanaan_mulai' => now()->subDays(10)->toDateString(),
            'waktu_pelaksanaan_selesai' => now()->subDays(5)->toDateString(),
            'tempat_pelaksanaan' => 'Aula Kecamatan',
            'pendaftaran_mulai' => now()->subDays(20),
            'pendaftaran_selesai' => now()->subDays(12),
            'verif1_mulai' => now()->subDays(11),
            'verif1_selesai' => now()->subDays(10),
            'verif2_mulai' => now()->subDays(9),
            'verif2_selesai' => now()->subDays(8),
            'sanggah_mulai' => now()->subDays(7),
            'sanggah_selesai' => now()->subDays(6),
            'pengumuman_verifikasi' => now()->subDays(6),
            'technical_meeting' => now()->subDays(13),
            'logo_path' => null,
        ]);

        // Event 2: dibuat agar statusnya "Mendatang" (pelaksanaan dan pendaftaran di masa depan)
        DetailEvent::create([
            'slug' => 'emtq-ke-21',
            'nama_kegiatan_aktif' => 'E-MTQ Ke-21',
            'waktu_pelaksanaan_mulai' => now()->addDays(20)->toDateString(),
            'waktu_pelaksanaan_selesai' => now()->addDays(25)->toDateString(),
            'tempat_pelaksanaan' => 'Gedung Serbaguna',
            'pendaftaran_mulai' => now()->addDays(1),
            'pendaftaran_selesai' => now()->addDays(10),
            'verif1_mulai' => now()->addDays(11),
            'verif1_selesai' => now()->addDays(12),
            'verif2_mulai' => now()->addDays(13),
            'verif2_selesai' => now()->addDays(14),
            'sanggah_mulai' => now()->addDays(15),
            'sanggah_selesai' => now()->addDays(16),
            'pengumuman_verifikasi' => now()->addDays(17),
            'technical_meeting' => now()->addDays(18),
            'logo_path' => null,
        ]);

        // Event 3: dibuat agar statusnya "Aktif" (pendaftaran sedang berjalan) untuk testing
        DetailEvent::create([
            'slug' => 'emtq-ke-22',
            'nama_kegiatan_aktif' => 'E-MTQ Ke-22',
            'waktu_pelaksanaan_mulai' => now()->addDays(7)->toDateString(),
            'waktu_pelaksanaan_selesai' => now()->addDays(9)->toDateString(),
            'tempat_pelaksanaan' => 'Lapangan Kecamatan',
            'pendaftaran_mulai' => now()->subDays(1),
            'pendaftaran_selesai' => now()->addDays(3),
            'verif1_mulai' => now()->addDays(4),
            'verif1_selesai' => now()->addDays(5),
            'verif2_mulai' => now()->addDays(5),
            'verif2_selesai' => now()->addDays(6),
            'sanggah_mulai' => now()->addDays(6),
            'sanggah_selesai' => now()->addDays(6)->addHours(12),
            'pengumuman_verifikasi' => now()->addDays(6)->addHours(13),
            'technical_meeting' => now()->addDays(6)->addHours(18),
            'logo_path' => null,
        ]);
    }
}
