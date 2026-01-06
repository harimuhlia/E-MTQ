<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cabang;
use App\Models\DetailEvent;

class CabangSeeder extends Seeder
{
    public function run(): void
    {
        $event = DetailEvent::first();

        Cabang::create([
            'detail_event_id' => $event->id,
            'nama' => 'Hafalan',
        ]);
    }
}
