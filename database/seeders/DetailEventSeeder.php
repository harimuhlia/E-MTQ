<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DetailEvent;

class DetailEventSeeder extends Seeder
{
    public function run(): void
    {
        DetailEvent::create([
            'name' => 'E-MTQ Ke-20',
            'slug' => 'emtq-ke-20',
            'tanggal_mulai' => now()->subDays(10),
            'tanggal_selesai' => now()->subDays(5),
        ]);

        DetailEvent::create([
            'name' => 'E-MTQ Ke-21',
            'slug' => 'emtq-ke-21',
            'tanggal_mulai' => now()->addDays(5),
            'tanggal_selesai' => now()->addDays(10),
        ]);
    }
}
