<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Desa;

class DesaSeeder extends Seeder
{
    public function run(): void
    {
        Desa::create(['nama' => 'Desa Sukamaju']);
        Desa::create(['nama' => 'Desa Mekarjaya']);
    }
}
