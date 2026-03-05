<?php

namespace Database\Seeders;

use App\Models\Club;
use App\Models\Community;
use App\Models\Comment;
use App\Models\Like;
use App\Models\Poll;
use App\Models\PollOption;
use App\Models\Post;
use App\Models\Role;
use App\Models\User;
use App\Models\Vote;
use App\Models\FootballMatch;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::where('slug', 'admin')->first();
        $userRole = Role::where('slug', 'user')->first();

        // Create admin user
        $admin = User::firstOrCreate(['email' => 'admin@soccer-aficionado.com'], [
            'name' => 'Admin',
            'username' => 'admin',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'country' => 'England',
            'bio' => 'Platform administrator',
            'points' => 500,
        ]);
        if ($adminRole)
            $admin->roles()->syncWithoutDetaching([$adminRole->id]);

        // Create demo user
        $demo = User::firstOrCreate(['email' => 'fan@soccer-aficionado.com'], [
            'name' => 'Soccer Fan',
            'username' => 'soccerfan',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'country' => 'Brazil',
            'bio' => 'Passionate about football! ⚽',
            'points' => 150,
        ]);
        if ($userRole)
            $demo->roles()->syncWithoutDetaching([$userRole->id]);

        // Create additional users
        $users = collect([$admin, $demo]);
        $names = ['Carlos Mendes', 'Sophie Laurent', 'Marco Rossi', 'Emma Wilson', 'Kenji Tanaka', 'Fatima Al-Hassan', 'Diego Rivera', 'Lena Schmidt', 'Oluwaseun Adeyemi', 'Priya Sharma'];
        $countries = ['Brazil', 'France', 'Italy', 'England', 'Japan', 'Egypt', 'Argentina', 'Germany', 'Nigeria', 'India'];

        foreach ($names as $i => $name) {
            $user = User::firstOrCreate(['email' => strtolower(str_replace(' ', '.', $name)) . '@example.com'], [
                'name' => $name,
                'username' => strtolower(str_replace(' ', '', $name)),
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'country' => $countries[$i],
                'bio' => 'Football fan from ' . $countries[$i],
                'points' => rand(10, 300),
            ]);
            if ($userRole)
                $user->roles()->syncWithoutDetaching([$userRole->id]);
            $users->push($user);
        }

        // Favorite clubs
        $clubs = Club::all();
        foreach ($users as $user) {
            $user->favoriteClubs()->syncWithoutDetaching($clubs->random(rand(1, 3))->pluck('id'));
        }

        // Follows
        foreach ($users as $user) {
            $toFollow = $users->where('id', '!=', $user->id)->random(rand(2, 5));
            foreach ($toFollow as $target) {
                DB::table('follows')->insertOrIgnore([
                    'follower_id' => $user->id,
                    'following_id' => $target->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // Communities
        $communityData = [
            ['name' => 'Red Devils United', 'description' => 'Manchester United fans community', 'club' => 'Manchester United'],
            ['name' => 'The Kop End', 'description' => 'Liverpool FC supporters', 'club' => 'Liverpool'],
            ['name' => 'Gunners Talk', 'description' => 'Arsenal discussion hub', 'club' => 'Arsenal'],
            ['name' => 'Madridistas', 'description' => 'Real Madrid fans worldwide', 'club' => 'Real Madrid'],
            ['name' => 'Blaugrana World', 'description' => 'FC Barcelona supporters community', 'club' => 'FC Barcelona'],
            ['name' => 'Football Tactics', 'description' => 'Discuss tactics, formations, and strategies', 'club' => null],
            ['name' => 'Transfer Rumors', 'description' => 'Latest transfer news and speculation', 'club' => null],
        ];

        $communities = collect();
        foreach ($communityData as $data) {
            $creator = $users->random();
            $club = $data['club'] ? Club::where('name', $data['club'])->first() : null;

            $community = Community::firstOrCreate(['name' => $data['name']], [
                'slug' => Str::slug($data['name']),
                'description' => $data['description'],
                'club_id' => $club?->id,
                'created_by' => $creator->id,
                'is_active' => true,
                'members_count' => 0,
            ]);

            // Add members
            $members = $users->random(rand(4, 8));
            foreach ($members as $member) {
                $community->members()->syncWithoutDetaching([
                    $member->id => ['role' => $member->id === $creator->id ? 'moderator' : 'member'],
                ]);
            }
            $community->update(['members_count' => $community->members()->count()]);
            $communities->push($community);
        }

        // Posts
        $postBodies = [
            'What an incredible match last night! The atmosphere was electric ⚡',
            'Thoughts on the new signing? I think they could be a game changer for the team.',
            'Match day! Let\'s go! 🔥⚽',
            'Just watched the highlights - that goal in the 89th minute was unbelievable!',
            'Who do you think will win the league this season? Share your predictions below 👇',
            'The defensive display today was world class. Clean sheet well deserved!',
            'Transfer window closing soon - who are you hoping your club signs?',
            'That referee decision was absolutely shocking. VAR needs to be better.',
            'Youth academy is producing some incredible talent. The future looks bright!',
            'Tactics analysis: Why the 4-3-3 formation worked so well today.',
            'Can we talk about how underrated this player is? Consistently performing every week.',
            'Pre-season is looking promising. New signings integrating well with the squad.',
            'Throwback to one of the greatest finals I\'ve ever watched. What a night that was!',
            'The rivalry match this weekend is going to be intense. Who\'s getting the bragging rights?',
            'Manager masterclass today. Perfect tactical adjustments at half time.',
        ];

        $posts = collect();
        foreach ($postBodies as $body) {
            $user = $users->random();
            $community = rand(0, 1) ? $communities->random() : null;

            $post = Post::create([
                'user_id' => $user->id,
                'community_id' => $community?->id,
                'body' => $body,
                'is_approved' => true,
                'likes_count' => 0,
                'comments_count' => 0,
                'shares_count' => 0,
            ]);
            $posts->push($post);
        }

        // Likes
        foreach ($posts as $post) {
            $likers = $users->random(rand(1, 6));
            foreach ($likers as $liker) {
                Like::firstOrCreate([
                    'user_id' => $liker->id,
                    'likeable_type' => Post::class,
                    'likeable_id' => $post->id,
                ]);
            }
            $post->update(['likes_count' => $post->likes()->count()]);
        }

        // Comments
        $commentBodies = [
            'Totally agree! 💯',
            'Great point!',
            'I think differently, but respect your opinion.',
            'This is spot on!',
            'Couldn\'t have said it better myself.',
            'Interesting take!',
            'Yes! Finally someone said it.',
            'I was just thinking about this!',
            '100% agree!',
        ];

        foreach ($posts as $post) {
            $numComments = rand(0, 4);
            for ($c = 0; $c < $numComments; $c++) {
                Comment::create([
                    'user_id' => $users->random()->id,
                    'post_id' => $post->id,
                    'body' => $commentBodies[array_rand($commentBodies)],
                ]);
            }
            $post->update(['comments_count' => $post->comments()->count()]);
        }

        // Polls
        $upcomingMatches = FootballMatch::where('status', 'scheduled')->with('homeClub', 'awayClub')->limit(3)->get();
        foreach ($upcomingMatches as $match) {
            $poll = Poll::create([
                'user_id' => $users->random()->id,
                'match_id' => $match->id,
                'title' => 'Who will win: ' . $match->homeClub->name . ' vs ' . $match->awayClub->name . '?',
                'closes_at' => $match->kick_off,
            ]);

            $opt1 = PollOption::create(['poll_id' => $poll->id, 'label' => $match->homeClub->name . ' Win', 'votes_count' => 0]);
            $opt2 = PollOption::create(['poll_id' => $poll->id, 'label' => 'Draw', 'votes_count' => 0]);
            $opt3 = PollOption::create(['poll_id' => $poll->id, 'label' => $match->awayClub->name . ' Win', 'votes_count' => 0]);

            $options = collect([$opt1, $opt2, $opt3]);
            $voters = $users->random(rand(3, 8));
            foreach ($voters as $voter) {
                $option = $options->random();
                Vote::firstOrCreate([
                    'user_id' => $voter->id,
                    'poll_id' => $poll->id,
                ], [
                    'poll_option_id' => $option->id,
                ]);
                $option->increment('votes_count');
            }
            $poll->update(['total_votes' => $poll->options()->sum('votes_count')]);
        }

        // A general poll
        $poll = Poll::create([
            'user_id' => $users->random()->id,
            'title' => 'Who is the greatest footballer of all time?',
            'closes_at' => now()->addDays(30),
        ]);
        $goatOptions = ['Lionel Messi', 'Cristiano Ronaldo', 'Diego Maradona', 'Pelé', 'Zinedine Zidane'];
        $pollOptions = collect();
        foreach ($goatOptions as $text) {
            $pollOptions->push(PollOption::create(['poll_id' => $poll->id, 'label' => $text, 'votes_count' => 0]));
        }
        foreach ($users as $voter) {
            $option = $pollOptions->random();
            Vote::firstOrCreate([
                'user_id' => $voter->id,
                'poll_id' => $poll->id,
            ], [
                'poll_option_id' => $option->id,
            ]);
            $option->increment('votes_count');
        }
        $poll->update(['total_votes' => $poll->options()->sum('votes_count')]);
    }
}
