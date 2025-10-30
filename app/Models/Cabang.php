<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\DetailEvent;
use App\Models\Golongan;

class Cabang extends Model
{
    use HasFactory;

    protected $table = 'cabangs';

    /**
     * Atribut yang boleh diisi secara massal.
     *
     * - nama: nama cabang lomba
     * - detail_event_id: ID event detail yang menjadi induk cabang
     */
    protected $fillable = [
        'nama',
        'detail_event_id',
    ];

    /**
     * Relasi ke Detail Event.
     * Setiap cabang terkait dengan satu event detail.
     */
    public function detailEvent()
    {
        return $this->belongsTo(DetailEvent::class, 'detail_event_id');
    }

    /**
     * Relasi ke Golongan.
     * Satu cabang memiliki banyak golongan.
     */
    public function golongans()
    {
        return $this->hasMany(Golongan::class);
    }
}
