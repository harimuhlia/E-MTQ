<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailEvent extends Model
{
    use HasFactory;
        protected $fillable = [
            'tahunevent_id',
            'nama_kegiatan_aktif',
            'waktu_pelaksanaan_mulai',
            'waktu_pelaksanaan_selesai',
            'tempat_pelaksanaan',
            'batas_umur_tanggal_patokan',
            'pendaftaran_mulai',
            'pendaftaran_selesai',
            'verif1_mulai',
            'verif1_selesai',
            'verif2_mulai',
            'verif2_selesai',
            'sanggah_mulai',
            'sanggah_selesai',
            'pengumuman_verifikasi',
            'technical_meeting',
            'logo_path',
        ];

        // Casting tanggal/waktu
        protected $casts = [
            'waktu_pelaksanaan_mulai' => 'date',
            'waktu_pelaksanaan_selesai' => 'date',
            'batas_umur_tanggal_patokan' => 'date',

            'pendaftaran_mulai' => 'datetime',
            'pendaftaran_selesai' => 'datetime',
            'verif1_mulai' => 'datetime',
            'verif1_selesai' => 'datetime',
            'verif2_mulai' => 'datetime',
            'verif2_selesai' => 'datetime',
            'sanggah_mulai' => 'datetime',
            'sanggah_selesai' => 'datetime',
            'pengumuman_verifikasi' => 'datetime',
            'technical_meeting' => 'datetime',
        ];
}
