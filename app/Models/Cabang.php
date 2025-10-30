<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cabang extends Model
{
    use HasFactory;

    protected $table = 'cabangs';

    /**
     * Atribut yang boleh diisi secara massal.
     *
     * - nama: nama cabang lomba
     * - tahunevent_id: ID tahun event yang menjadi induk cabang
     */
    protected $fillable = [
        'nama',
        'tahunevent_id',
    ];

    /**
     * Relasi ke Tahun Event.
     * Setiap cabang terkait dengan satu tahun event.
     */
    public function tahunevent()
    {
        return $this->belongsTo(Tahunevent::class);
    }
}
