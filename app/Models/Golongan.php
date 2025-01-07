<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Golongan extends Model
{
    use HasFactory;
    protected $fillable = ['kode_golongan','nama_golongan', 'slug', 'nomorcabang_id'];

    public static function boot()
    {
        parent::boot();
        static::creating(function ($golongan) {
            $golongan->slug = Str::slug($golongan->nama_golongan);
        });
        static::updating(function ($golongan) {
            $golongan->slug = Str::slug($golongan->nama_golongan);
        });
    }

    public function cabang()
    {
        return $this->belongsTo(Nomorcabang::class);
    }

    public function ketentuanusia()
    {
        return $this->hasMany(Ketentuanusia::class);
    }

}
