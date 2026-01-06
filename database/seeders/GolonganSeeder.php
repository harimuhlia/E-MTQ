<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Golongan;
use App\Models\Cabang;

class GolonganSeeder extends Seeder
{
    public function run(): void
    {
        $cabang = Cabang::first();

        Golongan::insert([
            ['cabang_id' => $cabang->id, 'name' => 'Al-Falaq'],
            ['cabang_id' => $cabang->id, 'name' => 'Al-Quraisy'],
            ['cabang_id' => $cabang->id, 'name' => 'At-Takatsur'],
        ]);
    }
}
