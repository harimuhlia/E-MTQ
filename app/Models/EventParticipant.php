<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\DetailEvent;

/**
 * Model pivot untuk pendaftaran peserta per event.
 *
 * Setiap baris menyimpan hubungan antara pengguna (user) dengan sebuah
 * detail event, cabang, dan golongan tertentu, serta status verifikasi.
 */
class EventParticipant extends Model
{
    use HasFactory;

    /**
     * Kolom yang dapat diisi secara massal.
     *
     * - user_id: ID pengguna yang mendaftar.
     * - detail_event_id: ID detail event yang diikuti.
     * - cabang_id: ID cabang lomba.
     * - golongan_id: ID golongan lomba.
     * - status_verifikasi: status verifikasi (pending, verified, rejected).
     * - catatan_verifikasi: catatan dari panitia terkait verifikasi.
     * - request_message: permintaan perubahan data dari peserta.
     */
    protected $fillable = [
        'user_id',
        'detail_event_id',
        'cabang_id',
        'golongan_id',
        'status_verifikasi',
        'catatan_verifikasi',
        'request_message',
    ];

    /**
     * Relasi ke user (peserta).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke detail event.
     *
     * Setiap pendaftaran peserta mengacu pada satu detail event.
     */
    public function detailEvent()
    {
        return $this->belongsTo(DetailEvent::class, 'detail_event_id');
    }

    /**
     * Relasi ke cabang.
     */
    public function cabang()
    {
        return $this->belongsTo(Cabang::class);
    }

    /**
     * Relasi ke golongan.
     */
    public function golongan()
    {
        return $this->belongsTo(Golongan::class);
    }
}