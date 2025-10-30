<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model pivot untuk pendaftaran peserta per event.
 *
 * Setiap baris menyimpan hubungan antara pengguna (user) dengan sebuah
 * tahun event, cabang, dan golongan tertentu, serta status verifikasi.
 */
class EventParticipant extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'tahunevent_id',
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
     * Relasi ke tahun event.
     */
    public function tahunevent()
    {
        return $this->belongsTo(Tahunevent::class);
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