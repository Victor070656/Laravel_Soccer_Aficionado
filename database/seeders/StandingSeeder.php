<?php

namespace Database\Seeders;

use App\Models\Club;
use App\Models\Competition;
use App\Models\Standing;
use Illuminate\Database\Seeder;

class StandingSeeder extends Seeder
{
    public function run(): void
    {
        $leagues = Competition::where('type', 'league')->with('clubs')->get();

        foreach ($leagues as $league) {
            $position = 1;
            foreach ($league->clubs->shuffle() as $club) {
                $played = rand(20, 30);
                $won = rand(5, $played - 5);
                $drawn = rand(0, $played - $won);
                $lost = $played - $won - $drawn;
                $goalsFor = $won * rand(1, 3) + $drawn * rand(0, 1);
                $goalsAgainst = $lost * rand(1, 3) + $drawn * rand(0, 1);

                Standing::updateOrCreate(
                    [
                        'competition_id' => $league->id,
                        'club_id' => $club->id,
                        'season' => $league->season ?? '2024-25',
                    ],
                    [
                        'position' => $position,
                        'played' => $played,
                        'won' => $won,
                        'drawn' => $drawn,
                        'lost' => $lost,
                        'goals_for' => $goalsFor,
                        'goals_against' => $goalsAgainst,
                        'goal_difference' => $goalsFor - $goalsAgainst,
                        'points' => ($won * 3) + $drawn,
                        'form' => collect(array_map(fn() => collect(['W', 'D', 'L'])->random(), range(1, 5)))->implode(''),
                    ]
                );
                $position++;
            }
        }
    }
}
