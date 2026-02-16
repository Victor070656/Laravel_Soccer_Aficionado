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
            RoleSeeder::class,
            BadgeSeeder::class,
            ClubSeeder::class,
            CompetitionSeeder::class,
            MatchSeeder::class,
            StandingSeeder::class,
            UserSeeder::class,
        ]);
    }
}
