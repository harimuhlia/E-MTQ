<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model untuk detail event.
 *
 * Setiap event memiliki informasi seperti nama kegiatan, jadwal pendaftaran,
 * jadwal pelaksanaan, verifikasi, masa sanggah, pengumuman, dan technical meeting.
 * Event tidak lagi terkait dengan tabel tahun_event; tahun diambil dari tanggal
 * pendaftaran atau pelaksanaan. Slug digunakan sebagai identitas unik untuk
 * routing dan pengelolaan.
 */
class DetailEvent extends Model
{
    use HasFactory;

    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'slug',
        'nama_kegiatan_aktif',
        'waktu_pelaksanaan_mulai',
        'waktu_pelaksanaan_selesai',
        'tempat_pelaksanaan',
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

    /**
     * Casting tanggal/waktu ke tipe data yang sesuai.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'waktu_pelaksanaan_mulai' => 'date',
        'waktu_pelaksanaan_selesai' => 'date',
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

    /**
     * Relasi ke cabang. Satu event memiliki banyak cabang.
     */
    public function cabangs()
    {
        return $this->hasMany(Cabang::class, 'detail_event_id');
    }

    /**
     * Relasi ke peserta event (pivot dengan detail_event_id).
     */
    public function participants()
    {
        return $this->hasMany(EventParticipant::class, 'detail_event_id');
    }

    /**
     * Menentukan status event berdasarkan periode pendaftaran.
     *
     * @return string
     */
    public function status(): string
    {
        $now = now();
        if ($this->pendaftaran_mulai && $this->pendaftaran_selesai) {
            if ($now->between($this->pendaftaran_mulai, $this->pendaftaran_selesai)) {
                return 'Aktif';
            }
            if ($now->lt($this->pendaftaran_mulai)) {
                return 'Mendatang';
            }
            return 'Selesai';
        }
        return 'Jadwal Belum Lengkap';
    }

    /**
     * Mendapatkan kelas badge bootstrap berdasarkan status event.
     *
     * @return string
     */
    public function statusClass(): string
    {
        return match ($this->status()) {
            'Aktif' => 'success',
            'Mendatang' => 'warning',
            'Selesai' => 'secondary',
            default => 'dark',
        };
    }
}