<?php

namespace Database\Seeders;

use App\Models\Badge;
use Illuminate\Database\Seeder;

class BadgeSeeder extends Seeder
{
    public function run(): void
    {
        $badges = [
            ['name' => 'First Post', 'slug' => 'first-post', 'description' => 'Published your first post', 'icon' => '📝', 'criteria' => 'posts_count >= 1'],
            ['name' => 'Prolific Writer', 'slug' => 'prolific-writer', 'description' => 'Published 50 posts', 'icon' => '✍️', 'criteria' => 'posts_count >= 50'],
            ['name' => 'Commentator', 'slug' => 'commentator', 'description' => 'Left 10 comments', 'icon' => '💬', 'criteria' => 'comments_count >= 10'],
            ['name' => 'Social Butterfly', 'slug' => 'social-butterfly', 'description' => 'Gained 10 followers', 'icon' => '🦋', 'criteria' => 'followers_count >= 10'],
            ['name' => 'Community Leader', 'slug' => 'community-leader', 'description' => 'Created a community', 'icon' => '👑', 'criteria' => 'communities_created >= 1'],
            ['name' => 'Poll Creator', 'slug' => 'poll-creator', 'description' => 'Created 5 polls', 'icon' => '📊', 'criteria' => 'polls_count >= 5'],
            ['name' => 'Fan Favorite', 'slug' => 'fan-favorite', 'description' => 'Received 100 likes', 'icon' => '❤️', 'criteria' => 'likes_received >= 100'],
            ['name' => 'Rising Star', 'slug' => 'rising-star', 'description' => 'Earned 100 points', 'icon' => '⭐', 'criteria' => 'points >= 100'],
            ['name' => 'Legend', 'slug' => 'legend', 'description' => 'Earned 1000 points', 'icon' => '🏆', 'criteria' => 'points >= 1000'],
            ['name' => 'Club Supporter', 'slug' => 'club-supporter', 'description' => 'Favorited 3 clubs', 'icon' => '💚', 'criteria' => 'favorite_clubs >= 3'],
        ];

        foreach ($badges as $badge) {
            Badge::firstOrCreate(['slug' => $badge['slug']], $badge);
        }
    }
}
