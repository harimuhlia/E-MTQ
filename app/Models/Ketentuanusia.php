<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Ketentuanusia extends Model
{
    use HasFactory;
    protected $fillable = ['usia_minimal', 'usia_maksimal', 'cabang_id', 'golongan_id', 'slug'];

    public static function generateUniqueSlug($title, $id = null)
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        
        $count = 1;
        while (static::where('slug', $slug)->where('id', '!=', $id)->exists()) {
            $slug = "$originalSlug-$count";
            $count++;
        }

        return $slug;
    }

    public function cabang()
    {
        return $this->belongsTo(Nomorcabang::class);
    }

    public function golongan()
    {
        return $this->belongsTo(Golongan::class);
    }

}
