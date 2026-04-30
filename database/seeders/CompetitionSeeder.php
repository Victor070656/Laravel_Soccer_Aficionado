<?php

namespace Database\Seeders;

use App\Models\Club;
use App\Models\Competition;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CompetitionSeeder extends Seeder
{
    public function run(): void
    {
        $competitions = [
            [
                'name' => 'Premier League',
                'type' => 'league',
                'country' => 'England',
                'season' => '2024-25',
                'description' => 'The top tier of English football.',
                'clubs' => ['Manchester United', 'Liverpool', 'Arsenal', 'Chelsea', 'Manchester City', 'Tottenham Hotspur'],
            ],
            [
                'name' => 'La Liga',
                'type' => 'league',
                'country' => 'Spain',
                'season' => '2024-25',
                'description' => 'The top tier of Spanish football.',
                'clubs' => ['Real Madrid', 'FC Barcelona', 'Atletico Madrid'],
            ],
            [
                'name' => 'Serie A',
                'type' => 'league',
                'country' => 'Italy',
                'season' => '2024-25',
                'description' => 'The top tier of Italian football.',
                'clubs' => ['AC Milan', 'Inter Milan', 'Juventus'],
            ],
            [
                'name' => 'Bundesliga',
                'type' => 'league',
                'country' => 'Germany',
                'season' => '2024-25',
                'description' => 'The top tier of German football.',
                'clubs' => ['Bayern Munich', 'Borussia Dortmund'],
            ],
            [
                'name' => 'Ligue 1',
                'type' => 'league',
                'country' => 'France',
                'season' => '2024-25',
                'description' => 'The top tier of French football.',
                'clubs' => ['Paris Saint-Germain', 'Marseille'],
            ],
            [
                'name' => 'Primeira Liga',
                'type' => 'league',
                'country' => 'Portugal',
                'season' => '2024-25',
                'description' => 'The top tier of Portuguese football.',
                'clubs' => ['Benfica', 'Porto', 'Sporting CP'],
            ],
            [
                'name' => 'Eredivisie',
                'type' => 'league',
                'country' => 'Netherlands',
                'season' => '2024-25',
                'description' => 'The top tier of Dutch football.',
                'clubs' => ['Ajax', 'PSV Eindhoven', 'Feyenoord'],
            ],
            [
                'name' => 'Major League Soccer',
                'type' => 'league',
                'country' => 'USA',
                'season' => '2025',
                'description' => 'The top tier of American football.',
                'clubs' => ['Inter Miami', 'LA Galaxy'],
            ],
            [
                'name' => 'Champions League',
                'type' => 'cup',
                'country' => null,
                'season' => '2024-25',
                'description' => 'The premier European club competition.',
                'clubs' => ['Real Madrid', 'FC Barcelona', 'Bayern Munich', 'Manchester City', 'Liverpool', 'Inter Milan', 'Paris Saint-Germain', 'Borussia Dortmund', 'Arsenal', 'AC Milan', 'Benfica', 'Porto', 'Ajax', 'Atletico Madrid', 'Chelsea', 'Juventus'],
            ],
            [
                'name' => 'Europa League',
                'type' => 'cup',
                'country' => null,
                'season' => '2024-25',
                'description' => 'Secondary European club competition.',
                'clubs' => ['Manchester United', 'Tottenham Hotspur', 'AS Roma'],
            ],
        ];

        foreach ($competitions as $data) {
            $clubNames = $data['clubs'];
            unset($data['clubs']);

            $competition = Competition::firstOrCreate(['name' => $data['name'], 'season' => $data['season']], array_merge($data, ['slug' => Str::slug($data['name'] . '-' . $data['season'])]));

            $clubs = Club::whereIn('name', $clubNames)->pluck('id');
            $competition->clubs()->syncWithoutDetaching($clubs);
        }
    }
}
