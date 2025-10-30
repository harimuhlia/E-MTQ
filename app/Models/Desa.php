<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Desa extends Model
{
    use HasFactory;

    // Menggunakan kolom 'nama' pada tabel desas
    protected $fillable = ['nama'];
}
