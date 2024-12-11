<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Golongan extends Model
{
    use HasFactory;
    protected $fillable = ['kode_golongan','nama_golongan', 'slug', 'nomorcabang_id'];

    public static function generateSlug($nama_golongan)
    {
        return strtolower(str_replace(' ', '-', $nama_golongan));
    }

    public function nomorcabang()
    {
        return $this->belongsTo(Nomorcabang::class);
    }

}
