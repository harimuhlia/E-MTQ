<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nomorcabang extends Model
{
    use HasFactory;
    protected $fillable = ['kode_cabang','nama_cabang', 'slug'];

    public static function generateSlug($nama_cabang)
    {
        return strtolower(str_replace(' ', '-', $nama_cabang));
    }

    public function golongans()
    {
        return $this->hasMany(Golongan::class);
    }

    public function ketentuanusia()
    {
        return $this->hasMany(Ketentuanusia::class);
    }

}
