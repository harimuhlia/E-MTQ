<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ketentuanusia extends Model
{
    use HasFactory;
    protected $fillable = [
        'usia_minimal',
        'usia_maksimal', 'nama_cabang', 'nama_golongan', 'nomorcabang_id'
    ];
}
