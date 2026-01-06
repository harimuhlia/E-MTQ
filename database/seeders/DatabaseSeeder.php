<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            DesaSeeder::class,
            UserSeeder::class,
            DetailEventSeeder::class,
            CabangSeeder::class,
            GolonganSeeder::class,
            EventParticipantSeeder::class,
            AnnouncementSeeder::class,
        ]);
    }
}
