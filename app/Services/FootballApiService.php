<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Service to fetch live football data from API-Football (api-sports.io).
 *
 * Free tier: 100 requests / day.
 * Docs: https://www.api-football.com/documentation-v3
 *
 * All responses are cached to minimise API usage.
 * When the API key is missing or a request fails, the service returns
 * graceful empty arrays so the controllers / views never break.
 */
class FootballApiService
{
    protected string $baseUrl;
    protected ?string $apiKey;
    protected array $cacheTtl;
    protected array $leagues;
    protected int $season;

    public function __construct()
    {
        $config = config('services.football_api');

        $this->baseUrl = rtrim($config['base_url'] ?? 'https://v3.football.api-sports.io', '/');
        $this->apiKey = $config['key'] ?? null;
        $this->cacheTtl = $config['cache_ttl'] ?? [];
        $this->leagues = $config['leagues'] ?? [39, 140, 135, 78, 61];
        $this->season = $config['season'] ?? (int) date('Y');
    }

    // ── Core HTTP ──────────────────────────────────────────

    /**
     * Whether the service is configured (has an API key).
     */
    public function isConfigured(): bool
    {
        return !empty($this->apiKey);
    }

    /**
     * Make an authenticated GET request to the API.
     */
    protected function get(string $endpoint, array $params = []): ?array
    {
        if (!$this->isConfigured()) {
            return null;
        }

        try {
            $response = Http::withHeaders([
                'x-apisports-key' => $this->apiKey,
            ])
                ->timeout(10)
                ->get("{$this->baseUrl}/{$endpoint}", $params);

            if ($response->successful()) {
                $body = $response->json();

                if (isset($body['errors']) && !empty($body['errors'])) {
                    Log::warning('Football API error', [
                        'endpoint' => $endpoint,
                        'params' => $params,
                        'errors' => $body['errors'],
                    ]);
                    return null;
                }

                return $body;
            }

            Log::warning('Football API HTTP error', [
                'endpoint' => $endpoint,
                'status' => $response->status(),
            ]);

            return null;
        } catch (\Throwable $e) {
            Log::error('Football API exception', [
                'endpoint' => $endpoint,
                'message' => $e->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * Cached wrapper around get().
     */
    protected function cached(string $cacheKey, int $ttl, string $endpoint, array $params = []): array
    {
        return Cache::remember($cacheKey, $ttl, function () use ($endpoint, $params) {
            $response = $this->get($endpoint, $params);

            return $response['response'] ?? [];
        });
    }

    // ── Public Methods ─────────────────────────────────────

    /**
     * Get fixtures for a specific date (or today).
     * Returns all league fixtures for the configured leagues.
     */
    public function getFixturesByDate(?string $date = null, ?int $leagueId = null, ?string $status = null): array
    {
        $date = $date ?? now()->format('Y-m-d');

        $params = [
            'date' => $date,
            'season' => $this->season,
            'timezone' => config('app.timezone', 'UTC'),
        ];

        if ($leagueId) {
            $params['league'] = $leagueId;
        }

        $cacheKey = 'football_api:fixtures:date:' . md5(json_encode($params));
        $ttl = $this->cacheTtl['fixtures'] ?? 900;

        $fixtures = $this->cached($cacheKey, $ttl, 'fixtures', $params);

        // If no specific league, filter to configured leagues only
        if (!$leagueId) {
            $fixtures = array_filter($fixtures, function ($fixture) {
                return in_array($fixture['league']['id'] ?? 0, $this->leagues);
            });
            $fixtures = array_values($fixtures);
        }

        // Filter by status if given
        if ($status) {
            $fixtures = $this->filterByStatus($fixtures, $status);
        }

        return $fixtures;
    }

    /**
     * Get upcoming fixtures across configured leagues.
     * Uses the "next" parameter for efficient querying.
     */
    public function getUpcomingFixtures(int $limit = 20, ?int $leagueId = null): array
    {
        $params = [
            'season' => $this->season,
            'timezone' => config('app.timezone', 'UTC'),
            'status' => 'NS-TBD',  // Not Started & TBD
        ];

        $allFixtures = [];

        $targetLeagues = $leagueId ? [$leagueId] : $this->leagues;

        foreach ($targetLeagues as $league) {
            $params['league'] = $league;
            $params['next'] = $leagueId ? $limit : ceil($limit / count($targetLeagues));

            $cacheKey = 'football_api:upcoming:' . md5(json_encode($params));
            $ttl = $this->cacheTtl['fixtures'] ?? 900;

            $fixtures = $this->cached($cacheKey, $ttl, 'fixtures', $params);
            $allFixtures = array_merge($allFixtures, $fixtures);
        }

        // Sort by kick-off ascending
        usort($allFixtures, fn($a, $b) => strtotime($a['fixture']['date'] ?? '0') - strtotime($b['fixture']['date'] ?? '0'));

        return array_slice($allFixtures, 0, $limit);
    }

    /**
     * Get recently finished fixtures across configured leagues.
     */
    public function getFinishedFixtures(int $limit = 20, ?int $leagueId = null): array
    {
        $params = [
            'season' => $this->season,
            'timezone' => config('app.timezone', 'UTC'),
            'status' => 'FT-AET-PEN',  // Full Time, After Extra Time, Penalties
        ];

        $allFixtures = [];

        $targetLeagues = $leagueId ? [$leagueId] : $this->leagues;

        foreach ($targetLeagues as $league) {
            $params['league'] = $league;
            $params['last'] = $leagueId ? $limit : ceil($limit / count($targetLeagues));

            $cacheKey = 'football_api:finished:' . md5(json_encode($params));
            $ttl = $this->cacheTtl['fixtures'] ?? 900;

            $fixtures = $this->cached($cacheKey, $ttl, 'fixtures', $params);
            $allFixtures = array_merge($allFixtures, $fixtures);
        }

        // Sort by kick-off descending (most recent first)
        usort($allFixtures, fn($a, $b) => strtotime($b['fixture']['date'] ?? '0') - strtotime($a['fixture']['date'] ?? '0'));

        return array_slice($allFixtures, 0, $limit);
    }

    /**
     * Get all currently live fixtures.
     */
    public function getLiveFixtures(): array
    {
        if (!$this->isConfigured()) {
            return [];
        }

        $cacheKey = 'football_api:live';
        $ttl = $this->cacheTtl['live'] ?? 60;

        $fixtures = $this->cached($cacheKey, $ttl, 'fixtures', ['live' => 'all']);

        // Filter to configured leagues only
        $fixtures = array_filter($fixtures, function ($fixture) {
            return in_array($fixture['league']['id'] ?? 0, $this->leagues);
        });

        return array_values($fixtures);
    }

    /**
     * Get a single fixture by its API-Football fixture ID.
     */
    public function getFixture(int $fixtureId): ?array
    {
        $cacheKey = "football_api:fixture:{$fixtureId}";
        $ttl = $this->cacheTtl['fixture'] ?? 300;

        $fixtures = $this->cached($cacheKey, $ttl, 'fixtures', ['id' => $fixtureId]);

        return $fixtures[0] ?? null;
    }

    /**
     * Get events (goals, cards, subs) for a fixture.
     */
    public function getFixtureEvents(int $fixtureId): array
    {
        $cacheKey = "football_api:events:{$fixtureId}";
        $ttl = $this->cacheTtl['events'] ?? 120;

        return $this->cached($cacheKey, $ttl, 'fixtures/events', ['fixture' => $fixtureId]);
    }

    /**
     * Get lineups for a fixture.
     */
    public function getFixtureLineups(int $fixtureId): array
    {
        $cacheKey = "football_api:lineups:{$fixtureId}";
        $ttl = $this->cacheTtl['lineups'] ?? 600;

        return $this->cached($cacheKey, $ttl, 'fixtures/lineups', ['fixture' => $fixtureId]);
    }

    /**
     * Get match statistics for a fixture.
     */
    public function getFixtureStatistics(int $fixtureId): array
    {
        $cacheKey = "football_api:statistics:{$fixtureId}";
        $ttl = $this->cacheTtl['statistics'] ?? 300;

        return $this->cached($cacheKey, $ttl, 'fixtures/statistics', ['fixture' => $fixtureId]);
    }

    /**
     * Get all fixtures for all configured leagues (paginated-style).
     * Accepts filters: status, league, date, page.
     */
    public function getAllFixtures(array $filters = []): array
    {
        $status = $filters['status'] ?? null;
        $leagueId = isset($filters['league']) ? (int) $filters['league'] : null;
        $date = $filters['date'] ?? null;
        $page = max(1, (int) ($filters['page'] ?? 1));
        $perPage = 20;

        // If a specific date is provided, use date endpoint
        if ($date) {
            $fixtures = $this->getFixturesByDate($date, $leagueId, $status);

            return $this->paginateArray($fixtures, $page, $perPage);
        }

        // Otherwise, compose from live + upcoming + finished
        $allFixtures = [];

        // Always include live at the top
        $live = $this->getLiveFixtures();
        if ($leagueId) {
            $live = array_filter($live, fn($f) => ($f['league']['id'] ?? 0) === $leagueId);
            $live = array_values($live);
        }

        if (!$status || $status === 'live') {
            $allFixtures = array_merge($allFixtures, $live);
        }

        if (!$status || $status === 'scheduled') {
            $upcoming = $this->getUpcomingFixtures(30, $leagueId);
            $allFixtures = array_merge($allFixtures, $upcoming);
        }

        if (!$status || $status === 'finished') {
            $finished = $this->getFinishedFixtures(30, $leagueId);
            $allFixtures = array_merge($allFixtures, $finished);
        }

        if ($status && !in_array($status, ['live', 'scheduled', 'finished'])) {
            // For other statuses, try date-based with today
            $todayFixtures = $this->getFixturesByDate(now()->format('Y-m-d'), $leagueId, $status);
            $allFixtures = array_merge($allFixtures, $todayFixtures);
        }

        return $this->paginateArray($allFixtures, $page, $perPage);
    }

    /**
     * Get the configured league IDs and names for filters.
     */
    public function getLeagues(): array
    {
        $cacheKey = 'football_api:leagues';
        $ttl = 86400; // 24 hours

        return Cache::remember($cacheKey, $ttl, function () {
            $leagues = [];

            foreach ($this->leagues as $leagueId) {
                $response = $this->get('leagues', ['id' => $leagueId]);
                if ($response && !empty($response['response'])) {
                    $league = $response['response'][0]['league'] ?? null;
                    if ($league) {
                        $leagues[] = [
                            'id' => $league['id'],
                            'name' => $league['name'],
                            'country' => $response['response'][0]['country']['name'] ?? null,
                            'logo' => $league['logo'] ?? null,
                        ];
                    }
                }
            }

            return $leagues;
        });
    }

    // ── Data Normalisation ────────────────────────────────

    /**
     * Normalise an API-Football fixture into a consistent format
     * that our Blade views can consume.
     */
    public static function normaliseFixture(array $raw): array
    {
        $fixture = $raw['fixture'] ?? [];
        $league = $raw['league'] ?? [];
        $teams = $raw['teams'] ?? [];
        $goals = $raw['goals'] ?? [];
        $score = $raw['score'] ?? [];

        $statusShort = $fixture['status']['short'] ?? 'NS';
        $status = self::mapStatus($statusShort);

        return [
            'id' => $fixture['id'] ?? 0,
            'referee' => $fixture['referee'] ?? null,
            'date' => $fixture['date'] ?? null,
            'timestamp' => $fixture['timestamp'] ?? null,
            'venue' => ($fixture['venue']['name'] ?? null)
                ? ($fixture['venue']['name'] . ($fixture['venue']['city'] ? ', ' . $fixture['venue']['city'] : ''))
                : null,
            'status' => $status,
            'status_short' => $statusShort,
            'status_long' => $fixture['status']['long'] ?? 'Not Started',
            'elapsed' => $fixture['status']['elapsed'] ?? null,
            'league' => [
                'id' => $league['id'] ?? 0,
                'name' => $league['name'] ?? 'Unknown',
                'country' => $league['country'] ?? null,
                'logo' => $league['logo'] ?? null,
                'round' => $league['round'] ?? null,
            ],
            'home_team' => [
                'id' => $teams['home']['id'] ?? 0,
                'name' => $teams['home']['name'] ?? 'TBD',
                'logo' => $teams['home']['logo'] ?? null,
                'winner' => $teams['home']['winner'] ?? null,
            ],
            'away_team' => [
                'id' => $teams['away']['id'] ?? 0,
                'name' => $teams['away']['name'] ?? 'TBD',
                'logo' => $teams['away']['logo'] ?? null,
                'winner' => $teams['away']['winner'] ?? null,
            ],
            'home_score' => $goals['home'] ?? null,
            'away_score' => $goals['away'] ?? null,
            'ht_score' => [
                'home' => $score['halftime']['home'] ?? null,
                'away' => $score['halftime']['away'] ?? null,
            ],
            'ft_score' => [
                'home' => $score['fulltime']['home'] ?? null,
                'away' => $score['fulltime']['away'] ?? null,
            ],
            'et_score' => [
                'home' => $score['extratime']['home'] ?? null,
                'away' => $score['extratime']['away'] ?? null,
            ],
            'penalty_score' => [
                'home' => $score['penalty']['home'] ?? null,
                'away' => $score['penalty']['away'] ?? null,
            ],
            'score_display' => self::buildScoreDisplay($statusShort, $goals),
        ];
    }

    /**
     * Normalise an event from the fixtures/events endpoint.
     */
    public static function normaliseEvent(array $raw): array
    {
        return [
            'time' => ($raw['time']['elapsed'] ?? 0) . ($raw['time']['extra'] ? '+' . $raw['time']['extra'] : '') . "'",
            'elapsed' => $raw['time']['elapsed'] ?? 0,
            'extra_time' => $raw['time']['extra'] ?? null,
            'team' => [
                'id' => $raw['team']['id'] ?? 0,
                'name' => $raw['team']['name'] ?? '',
                'logo' => $raw['team']['logo'] ?? null,
            ],
            'player' => [
                'id' => $raw['player']['id'] ?? null,
                'name' => $raw['player']['name'] ?? 'Unknown',
            ],
            'assist' => [
                'id' => $raw['assist']['id'] ?? null,
                'name' => $raw['assist']['name'] ?? null,
            ],
            'type' => strtolower($raw['type'] ?? 'goal'),
            'detail' => $raw['detail'] ?? '',
            'icon' => self::eventIcon($raw['type'] ?? '', $raw['detail'] ?? ''),
        ];
    }

    /**
     * Normalise a lineup entry.
     */
    public static function normaliseLineup(array $raw): array
    {
        return [
            'team' => [
                'id' => $raw['team']['id'] ?? 0,
                'name' => $raw['team']['name'] ?? '',
                'logo' => $raw['team']['logo'] ?? null,
            ],
            'formation' => $raw['formation'] ?? null,
            'start_xi' => array_map(fn($p) => [
                'id' => $p['player']['id'] ?? 0,
                'name' => $p['player']['name'] ?? '',
                'number' => $p['player']['number'] ?? null,
                'pos' => $p['player']['pos'] ?? null,
                'grid' => $p['player']['grid'] ?? null,
            ], $raw['startXI'] ?? []),
            'substitutes' => array_map(fn($p) => [
                'id' => $p['player']['id'] ?? 0,
                'name' => $p['player']['name'] ?? '',
                'number' => $p['player']['number'] ?? null,
                'pos' => $p['player']['pos'] ?? null,
            ], $raw['substitutes'] ?? []),
            'coach' => [
                'id' => $raw['coach']['id'] ?? null,
                'name' => $raw['coach']['name'] ?? null,
                'photo' => $raw['coach']['photo'] ?? null,
            ],
        ];
    }

    // ── Helpers ────────────────────────────────────────────

    /**
     * Map API-Football status codes to our internal status names.
     */
    protected static function mapStatus(string $short): string
    {
        return match ($short) {
            'TBD', 'NS' => 'scheduled',
            '1H', '2H', 'ET' => 'live',
            'HT' => 'half_time',
            'FT', 'AET', 'PEN' => 'finished',
            'BT' => 'break',
            'SUSP' => 'suspended',
            'INT' => 'interrupted',
            'PST' => 'postponed',
            'CANC' => 'cancelled',
            'ABD' => 'abandoned',
            'AWD' => 'awarded',
            'WO' => 'walkover',
            'LIVE' => 'live',
            default => 'unknown',
        };
    }

    protected static function buildScoreDisplay(string $statusShort, array $goals): string
    {
        $isPlayed = !in_array($statusShort, ['TBD', 'NS', 'PST', 'CANC']);
        if (!$isPlayed || $goals['home'] === null) {
            return 'vs';
        }

        return ($goals['home'] ?? 0) . ' - ' . ($goals['away'] ?? 0);
    }

    protected static function eventIcon(string $type, string $detail): string
    {
        return match (strtolower($type)) {
            'goal' => match (true) {
                    str_contains(strtolower($detail), 'own') => '🔴',
                    str_contains(strtolower($detail), 'penalty') => '⚽(P)',
                    default => '⚽',
                },
            'card' => match (true) {
                    str_contains(strtolower($detail), 'red') => '🟥',
                    str_contains(strtolower($detail), 'yellow') && str_contains(strtolower($detail), 'red') => '🟨🟥',
                    default => '🟨',
                },
            'subst' => '🔄',
            'var' => '📺',
            default => '📋',
        };
    }

    protected function filterByStatus(array $fixtures, string $status): array
    {
        $filtered = array_filter($fixtures, function ($fixture) use ($status) {
            $fixtureStatus = self::mapStatus($fixture['fixture']['status']['short'] ?? 'NS');

            return match ($status) {
                'live' => in_array($fixtureStatus, ['live', 'half_time']),
                'scheduled' => $fixtureStatus === 'scheduled',
                'finished' => $fixtureStatus === 'finished',
                'postponed' => $fixtureStatus === 'postponed',
                default => true,
            };
        });

        return array_values($filtered);
    }

    protected function paginateArray(array $items, int $page, int $perPage): array
    {
        $total = count($items);
        $offset = ($page - 1) * $perPage;
        $data = array_slice($items, $offset, $perPage);

        return [
            'data' => $data,
            'current_page' => $page,
            'per_page' => $perPage,
            'total' => $total,
            'last_page' => max(1, (int) ceil($total / $perPage)),
            'has_more' => ($offset + $perPage) < $total,
        ];
    }
}
