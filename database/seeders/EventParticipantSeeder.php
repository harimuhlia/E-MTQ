<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EventParticipant;
use App\Models\User;
use App\Models\DetailEvent;
use App\Models\Cabang;
use App\Models\Golongan;

class EventParticipantSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('role', 'peserta')->first();
        $event = DetailEvent::first();
        $cabang = Cabang::first();
        $golongan = Golongan::first();

        EventParticipant::create([
            'user_id' => $user->id,
            'detail_event_id' => $event->id,
            'cabang_id' => $cabang->id,
            'golongan_id' => $golongan->id,
            'status_verifikasi' => 'belum_verifikasi',
        ]);
    }
}
