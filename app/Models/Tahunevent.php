<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tahunevent extends Model
{
    use HasFactory;

    protected $fillable = ['tahun_event', 'slug'];

    public static function generateSlug($tahun_event)
    {
        return strtolower(str_replace(' ', '-', $tahun_event));
    }
}
