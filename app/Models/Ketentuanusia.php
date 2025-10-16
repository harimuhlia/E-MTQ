<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KetentuanUsia extends Model
{
    use HasFactory;

    protected $table = 'ketentuanusia';

    protected $fillable = [
    'cabang_id', 'golongan_id', 'min_usia', 'max_usia',
    ];

    protected $casts = [
        'min_usia' => 'date',
        'max_usia' => 'date',
    ];

    /**
     * Relasi ke tabel Cabang
     */
    public function cabang()
    {
        return $this->belongsTo(Cabang::class);
    }

    /**
     * Relasi ke tabel Golongan
     */
    public function golongan()
    {
        return $this->belongsTo(Golongan::class);
    }
}
