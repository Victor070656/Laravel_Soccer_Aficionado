<?php

namespace Tests\Feature;

use App\Models\Notification;
use App\Models\Poll;
use App\Models\User;
use App\Services\FootballApiService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class SidebarPageRedesignTest extends TestCase
{
    use RefreshDatabase;

    public function test_polls_page_uses_the_new_design(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $poll = Poll::create([
            'user_id' => $user->id,
            'title' => 'Who wins?',
            'description' => 'Test poll',
            'type' => 'prediction',
            'is_active' => true,
            'total_votes' => 0,
        ]);

        $poll->options()->createMany([
            ['label' => 'Home'],
            ['label' => 'Away'],
        ]);

        $response = $this->get(route('polls.index'));

        $response->assertOk();
        $response->assertSee('Create Poll');
        $response->assertSee('Who wins?');
    }

    public function test_leaderboard_page_uses_the_new_design(): void
    {
        $leader = User::factory()->create(['points' => 100]);
        User::factory()->create(['points' => 80]);
        User::factory()->create(['points' => 60]);
        $currentUser = User::factory()->create(['points' => 50]);

        $this->actingAs($currentUser);

        $response = $this->get(route('leaderboard'));

        $response->assertOk();
        $response->assertSee('Leaderboard');
        $response->assertSee($leader->name);
    }

    public function test_notifications_page_uses_the_new_design(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Notification::create([
            'id' => (string) Str::uuid(),
            'user_id' => $user->id,
            'type' => 'comment',
            'title' => 'New comment',
            'message' => 'Someone commented on your post.',
        ]);

        $response = $this->get(route('notifications.index'));

        $response->assertOk();
        $response->assertSee('Notifications');
        $response->assertSee('Someone commented on your post.');
    }

    public function test_leagues_pages_use_the_new_design(): void
    {
        app()->instance(FootballApiService::class, new class extends FootballApiService
        {
            public function __construct() {}

            public function isConfigured(): bool
            {
                return true;
            }

            public function seasonDisplay(): string
            {
                return '2025/26';
            }

            public function getSeason(): string
            {
                return '2025-2026';
            }

            public function getLeaguesFull(): array
            {
                return [
                    [
                        'id' => 39,
                        'name' => 'English Premier League',
                        'type' => 'league',
                        'country' => 'England',
                        'season' => '2025-2026',
                        'season_start' => '2025-08-01',
                        'season_end' => '2026-05-31',
                    ],
                ];
            }

            public function getLiveFixtures(): array
            {
                return [];
            }

            public function getLeague(int $leagueId): ?array
            {
                return [
                    'id' => $leagueId,
                    'name' => 'English Premier League',
                    'type' => 'league',
                    'country' => 'England',
                    'logo' => null,
                ];
            }

            public function getLeagueSeasons(int $leagueId): array
            {
                return [['value' => '2025-2026', 'label' => '2025/26']];
            }

            public function getStandings(int $leagueId, ?string $season = null): array
            {
                return [[
                    [
                        'rank' => 1,
                        'team' => ['id' => 1, 'name' => 'Arsenal', 'logo' => null],
                        'played' => 10,
                        'won' => 8,
                        'drawn' => 1,
                        'lost' => 1,
                        'goals_for' => 20,
                        'goals_against' => 8,
                        'goals_diff' => 12,
                        'points' => 25,
                        'form' => 'WWWDW',
                    ],
                ]];
            }

            public function getTeamsByLeague(int $leagueId, ?string $season = null): array
            {
                return [
                    ['id' => 1, 'name' => 'Arsenal', 'logo' => null],
                ];
            }

            public function getUpcomingFixtures(int $limit = 20, ?int $leagueId = null): array
            {
                return [[
                    'id' => 10,
                    'date' => '2026-05-20 12:00:00',
                    'home_team' => ['name' => 'Arsenal', 'logo' => null],
                    'away_team' => ['name' => 'Chelsea', 'logo' => null],
                    'league' => ['round' => 'Round 10'],
                ]];
            }

            public function getFinishedFixtures(int $limit = 20, ?int $leagueId = null): array
            {
                return [[
                    'id' => 11,
                    'date' => '2026-05-19 12:00:00',
                    'home_team' => ['name' => 'Liverpool', 'logo' => null],
                    'away_team' => ['name' => 'City', 'logo' => null],
                    'score_display' => '2 - 1',
                ]];
            }
        });

        $index = $this->get(route('competitions.index'));
        $index->assertOk();
        $index->assertSee('Leagues');

        $show = $this->get(route('competitions.show', 39));
        $show->assertOk();
        $show->assertSee('League: English Premier League');
    }
}
