<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Announcement;
use App\Models\DetailEvent;
use App\Models\User;

class AnnouncementSeeder extends Seeder
{
    public function run(): void
    {
        $event = DetailEvent::first();
        $admin = User::where('role', 'administrator')->first();

        Announcement::create([
            'title' => 'Pengumuman Resmi',
            'content' => 'Ini adalah pengumuman untuk event ' . $event->name,
            'user_id' => $admin->id,
            'detail_event_id' => $event->id,
        ]);
    }
}
