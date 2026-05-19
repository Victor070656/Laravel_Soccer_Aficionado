<?php

namespace Database\Seeders;

use App\Models\Club;
use App\Models\Player;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ClubSeeder extends Seeder
{
    public function run(): void
    {
        $clubs = [
            // English Premier League
            ['name' => 'Manchester United', 'short_name' => 'MUN', 'country' => 'England', 'city' => 'Manchester', 'stadium' => 'Old Trafford', 'founded_year' => 1878, 'description' => 'One of the most successful clubs in English football history.'],
            ['name' => 'Liverpool', 'short_name' => 'LIV', 'country' => 'England', 'city' => 'Liverpool', 'stadium' => 'Anfield', 'founded_year' => 1892, 'description' => 'Historic club known for passionate supporters and European success.'],
            ['name' => 'Arsenal', 'short_name' => 'ARS', 'country' => 'England', 'city' => 'London', 'stadium' => 'Emirates Stadium', 'founded_year' => 1886, 'description' => 'One of the most successful clubs in English football.'],
            ['name' => 'Chelsea', 'short_name' => 'CHE', 'country' => 'England', 'city' => 'London', 'stadium' => 'Stamford Bridge', 'founded_year' => 1905, 'description' => 'London club with multiple Premier League and Champions League titles.'],
            ['name' => 'Manchester City', 'short_name' => 'MCI', 'country' => 'England', 'city' => 'Manchester', 'stadium' => 'Etihad Stadium', 'founded_year' => 1880, 'description' => 'Dominant force in modern English football.'],
            ['name' => 'Tottenham Hotspur', 'short_name' => 'TOT', 'country' => 'England', 'city' => 'London', 'stadium' => 'Tottenham Hotspur Stadium', 'founded_year' => 1882, 'description' => 'North London club with a rich history.'],

            // La Liga
            ['name' => 'Real Madrid', 'short_name' => 'RMA', 'country' => 'Spain', 'city' => 'Madrid', 'stadium' => 'Santiago Bernabéu', 'founded_year' => 1902, 'description' => 'The most successful club in European competition history.'],
            ['name' => 'FC Barcelona', 'short_name' => 'BAR', 'country' => 'Spain', 'city' => 'Barcelona', 'stadium' => 'Spotify Camp Nou', 'founded_year' => 1899, 'description' => 'Mes que un club - More than a club.'],
            ['name' => 'Atletico Madrid', 'short_name' => 'ATM', 'country' => 'Spain', 'city' => 'Madrid', 'stadium' => 'Cívitas Metropolitano', 'founded_year' => 1903, 'description' => 'Madrid club known for defensive solidity.'],

            // Serie A
            ['name' => 'AC Milan', 'short_name' => 'ACM', 'country' => 'Italy', 'city' => 'Milan', 'stadium' => 'San Siro', 'founded_year' => 1899, 'description' => 'One of the most successful clubs in Italian and European football.'],
            ['name' => 'Inter Milan', 'short_name' => 'INT', 'country' => 'Italy', 'city' => 'Milan', 'stadium' => 'San Siro', 'founded_year' => 1908, 'description' => 'Historic Milanese club and treble winners.'],
            ['name' => 'Juventus', 'short_name' => 'JUV', 'country' => 'Italy', 'city' => 'Turin', 'stadium' => 'Allianz Stadium', 'founded_year' => 1897, 'description' => 'The most successful club in Italian football.'],

            // Bundesliga
            ['name' => 'Bayern Munich', 'short_name' => 'BAY', 'country' => 'Germany', 'city' => 'Munich', 'stadium' => 'Allianz Arena', 'founded_year' => 1900, 'description' => 'German football giants with record Bundesliga titles.'],
            ['name' => 'Borussia Dortmund', 'short_name' => 'BVB', 'country' => 'Germany', 'city' => 'Dortmund', 'stadium' => 'Signal Iduna Park', 'founded_year' => 1909, 'description' => 'Known for the Yellow Wall and passionate supporters.'],

            // Ligue 1
            ['name' => 'Paris Saint-Germain', 'short_name' => 'PSG', 'country' => 'France', 'city' => 'Paris', 'stadium' => 'Parc des Princes', 'founded_year' => 1970, 'description' => 'French giants dominating Ligue 1.'],
            ['name' => 'Marseille', 'short_name' => 'OM', 'country' => 'France', 'city' => 'Marseille', 'stadium' => 'Orange Vélodrome', 'founded_year' => 1899, 'description' => 'Only French club to win the Champions League.'],

            // Other European
            ['name' => 'Ajax', 'short_name' => 'AJA', 'country' => 'Netherlands', 'city' => 'Amsterdam', 'stadium' => 'Johan Cruyff Arena', 'founded_year' => 1900, 'description' => 'Dutch club famous for total football and youth development.'],
            ['name' => 'PSV Eindhoven', 'short_name' => 'PSV', 'country' => 'Netherlands', 'city' => 'Eindhoven', 'stadium' => 'Philips Stadion', 'founded_year' => 1913, 'description' => 'Successful Dutch club with European history.'],
            ['name' => 'Feyenoord', 'short_name' => 'FEY', 'country' => 'Netherlands', 'city' => 'Rotterdam', 'stadium' => 'De Kuip', 'founded_year' => 1908, 'description' => 'Rotterdam-based club with a rich history.'],
            ['name' => 'Benfica', 'short_name' => 'BEN', 'country' => 'Portugal', 'city' => 'Lisbon', 'stadium' => 'Estádio da Luz', 'founded_year' => 1904, 'description' => 'One of the big three Portuguese clubs.'],
            ['name' => 'Porto', 'short_name' => 'POR', 'country' => 'Portugal', 'city' => 'Porto', 'stadium' => 'Estádio do Dragão', 'founded_year' => 1893, 'description' => 'Champions League winners from Portugal.'],
            ['name' => 'Sporting CP', 'short_name' => 'SCP', 'country' => 'Portugal', 'city' => 'Lisbon', 'stadium' => 'Estádio José Alvalade', 'founded_year' => 1906, 'description' => 'Renowned for its youth academy.'],
            ['name' => 'AS Roma', 'short_name' => 'ROM', 'country' => 'Italy', 'city' => 'Rome', 'stadium' => 'Stadio Olimpico', 'founded_year' => 1927, 'description' => 'Historic club from the Italian capital.'],

            // MLS
            ['name' => 'Inter Miami', 'short_name' => 'MIA', 'country' => 'USA', 'city' => 'Miami', 'stadium' => 'Chase Stadium', 'founded_year' => 2018, 'description' => 'Global sensation with legendary players.'],
            ['name' => 'LA Galaxy', 'short_name' => 'LAG', 'country' => 'USA', 'city' => 'Los Angeles', 'stadium' => 'Dignity Health Sports Park', 'founded_year' => 1994, 'description' => 'One of the most decorated clubs in MLS.'],
        ];

        foreach ($clubs as $clubData) {
            $clubData['slug'] = Str::slug($clubData['name']);
            $club = Club::firstOrCreate(['name' => $clubData['name']], $clubData);

            // Create sample players for each club
            $positions = ['Goalkeeper', 'Defender', 'Defender', 'Defender', 'Defender', 'Midfielder', 'Midfielder', 'Midfielder', 'Forward', 'Forward', 'Forward'];
            $nationalities = ['Brazil', 'Argentina', 'France', 'England', 'Spain', 'Germany', 'Portugal', 'Netherlands', 'Belgium', 'Italy', 'Colombia'];

            foreach ($positions as $i => $position) {
                $playerName = fake()->name('male');
                Player::firstOrCreate([
                    'club_id' => $club->id,
                    'position' => $position,
                    'jersey_number' => $i + 1,
                ], [
                    'name' => $playerName,
                    'slug' => Str::slug($playerName).'-'.($club->id * 100 + $i),
                    'nationality' => $nationalities[array_rand($nationalities)],
                    'date_of_birth' => fake()->dateTimeBetween('-35 years', '-18 years'),
                    'height_cm' => rand(170, 195),
                    'weight_kg' => rand(65, 90),
                ]);
            }
        }
    }
}
