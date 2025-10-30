<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Golongan extends Model
{
    use HasFactory;

    protected $table = 'golongans';

    protected $fillable = [
        'nama',
        'cabang_id',
        'max_usia',
    ];

    /**
     * Relasi ke tabel Cabang
     */
    public function cabang()
    {
        return $this->belongsTo(Cabang::class);
    }
}
