<?php

namespace Database\Seeders;

use App\Models\Club;
use App\Models\Competition;
use App\Models\FootballMatch;
use App\Models\MatchEvent;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class MatchSeeder extends Seeder
{
    public function run(): void
    {
        $competitions = Competition::with('clubs')->get();

        foreach ($competitions as $competition) {
            $clubs = $competition->clubs;
            if ($clubs->count() < 2)
                continue;

            // Create some past matches
            for ($i = 0; $i < min(8, $clubs->count()); $i++) {
                $home = $clubs->random();
                $away = $clubs->where('id', '!=', $home->id)->random();

                $homeScore = rand(0, 4);
                $awayScore = rand(0, 3);

                $match = FootballMatch::create([
                    'home_club_id' => $home->id,
                    'away_club_id' => $away->id,
                    'competition_id' => $competition->id,
                    'kick_off' => Carbon::now()->subDays(rand(1, 30))->setHour(rand(15, 21))->setMinute(0),
                    'status' => 'finished',
                    'home_score' => $homeScore,
                    'away_score' => $awayScore,
                    'venue' => $home->stadium,
                    'matchday' => $i + 1,
                ]);

                // Add goal events
                for ($g = 0; $g < $homeScore; $g++) {
                    $player = $home->players()->inRandomOrder()->first();
                    if ($player) {
                        MatchEvent::create([
                            'match_id' => $match->id,
                            'type' => 'goal',
                            'minute' => rand(1, 90),
                            'player_id' => $player->id,
                            'club_id' => $home->id,
                        ]);
                    }
                }
                for ($g = 0; $g < $awayScore; $g++) {
                    $player = $away->players()->inRandomOrder()->first();
                    if ($player) {
                        MatchEvent::create([
                            'match_id' => $match->id,
                            'type' => 'goal',
                            'minute' => rand(1, 90),
                            'player_id' => $player->id,
                            'club_id' => $away->id,
                        ]);
                    }
                }
            }

            // Create upcoming matches
            for ($i = 0; $i < min(5, $clubs->count()); $i++) {
                $home = $clubs->random();
                $away = $clubs->where('id', '!=', $home->id)->random();

                FootballMatch::create([
                    'home_club_id' => $home->id,
                    'away_club_id' => $away->id,
                    'competition_id' => $competition->id,
                    'kick_off' => Carbon::now()->addDays(rand(1, 21))->setHour(rand(15, 21))->setMinute(0),
                    'status' => 'scheduled',
                    'home_score' => 0,
                    'away_score' => 0,
                    'venue' => $home->stadium,
                    'matchday' => $i + 9,
                ]);
            }

            // One live match per competition (only first)
            if ($competition->id === $competitions->first()->id) {
                $home = $clubs->random();
                $away = $clubs->where('id', '!=', $home->id)->random();

                FootballMatch::create([
                    'home_club_id' => $home->id,
                    'away_club_id' => $away->id,
                    'competition_id' => $competition->id,
                    'kick_off' => Carbon::now()->subMinutes(rand(15, 60)),
                    'status' => 'live',
                    'home_score' => rand(0, 2),
                    'away_score' => rand(0, 2),
                    'venue' => $home->stadium,
                    'matchday' => 99,
                ]);
            }
        }
    }
}
