<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Service to fetch live football data from TheSportsDB (thesportsdb.com).
 *
 * Free tier: 30 requests / minute, current season data.
 * Docs: https://www.thesportsdb.com/documentation
 *
 * All responses are cached to minimise API usage.
 * When the API key is missing or a request fails, the service returns
 * graceful empty arrays so the controllers / views never break.
 *
 * The public interface and normalised formats are kept compatible with
 * the rest of the application – controllers and views do not need changes.
 */
class FootballApiService
{
    protected string $baseUrl;
    protected string $apiKey;
    protected array $cacheTtl;
    protected array $leagues;
    protected string $season;

    /**
     * TheSportsDB league IDs mapped from our config league IDs.
     * Config uses API-Football IDs for backward compat; we map them here.
     */
    protected const LEAGUE_MAP = [
        // API-Football ID => TheSportsDB ID
        39 => 4328, // English Premier League
        140 => 4335, // Spanish La Liga
        135 => 4332, // Italian Serie A
        78 => 4331, // German Bundesliga
        61 => 4334, // French Ligue 1
    ];

    /**
     * League metadata keyed by TheSportsDB ID.
     * Avoids calling lookupleague.php just to get the name for search_all_teams.
     */
    protected const LEAGUE_META = [
        4328 => ['name' => 'English Premier League', 'country' => 'England'],
        4335 => ['name' => 'Spanish La Liga', 'country' => 'Spain'],
        4332 => ['name' => 'Italian Serie A', 'country' => 'Italy'],
        4331 => ['name' => 'German Bundesliga', 'country' => 'Germany'],
        4334 => ['name' => 'French Ligue 1', 'country' => 'France'],
    ];

    public function __construct()
    {
        $config = config('services.football_api');

        $this->apiKey = $config['key'] ?? '123'; // TheSportsDB free key
        $this->baseUrl = rtrim($config['base_url'] ?? 'https://www.thesportsdb.com/api/v1/json', '/');
        $this->cacheTtl = $config['cache_ttl'] ?? [];
        $this->leagues = $config['leagues'] ?? [39, 140, 135, 78, 61];
        $this->season = $config['season'] ?? '2025-2026';
    }

    // ── Core HTTP ──────────────────────────────────────────

    /**
     * Whether the service is configured and ready.
     */
    public function isConfigured(): bool
    {
        return true; // TheSportsDB free key '123' always works
    }

    /**
     * Get the configured season string (e.g. "2025-2026").
     */
    public function getSeason(): string
    {
        return $this->season;
    }

    /**
     * Display-friendly season string (e.g. "2025/26").
     */
    public function seasonDisplay(): string
    {
        $parts = explode('-', $this->season);
        if (count($parts) === 2) {
            return $parts[0] . '/' . substr($parts[1], -2);
        }
        return $this->season;
    }

    /**
     * Make a GET request to TheSportsDB API.
     */
    protected function get(string $endpoint, array $params = []): ?array
    {
        $url = "{$this->baseUrl}/{$this->apiKey}/{$endpoint}";

        try {
            $response = Http::timeout(10)->get($url, $params);

            if ($response->successful()) {
                return $response->json();
            }

            Log::warning('TheSportsDB API HTTP error', [
                'endpoint' => $endpoint,
                'params' => $params,
                'status' => $response->status(),
            ]);

            return null;
        } catch (\Throwable $e) {
            Log::error('TheSportsDB API exception', [
                'endpoint' => $endpoint,
                'message' => $e->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * Cache-remember helper that does NOT store empty/null results.
     *
     * Prevents caching of rate-limited (429) or failed API responses
     * so subsequent requests can retry instead of serving stale empties.
     */
    protected function cacheRemember(string $key, int $ttl, callable $callback): mixed
    {
        $cached = Cache::get($key);
        if ($cached !== null) {
            return $cached;
        }

        $result = $callback();

        // Only cache meaningful results (non-empty arrays, non-null values).
        // This prevents caching rate-limited (429) or failed API responses.
        if ($result !== null && $result !== []) {
            Cache::put($key, $result, $ttl);
        }

        return $result;
    }

    /**
     * Resolve TheSportsDB league ID from our config league ID.
     */
    protected function resolveLeagueId(int $configId): int
    {
        return self::LEAGUE_MAP[$configId] ?? $configId;
    }

    // ── Round Helpers ──────────────────────────────────────

    /**
     * Detect the current round context for a league.
     *
     * Uses eventspastleague + eventsnextleague (1 event each on free tier)
     * to determine which rounds are completed vs upcoming.
     *
     * @return array{last_completed: int, next_upcoming: int}
     */
    protected function getCurrentRounds(int $tsdbId): array
    {
        $cacheKey = "tsdb:rounds:{$tsdbId}";
        $ttl = 14400; // 4 hours – round numbers only change once per matchday

        return $this->cacheRemember($cacheKey, $ttl, function () use ($tsdbId) {
            $pastData = $this->get('eventspastleague.php', ['id' => $tsdbId]);
            $nextData = $this->get('eventsnextleague.php', ['id' => $tsdbId]);

            // If both API calls failed, return null so cacheRemember won't cache
            if ($pastData === null && $nextData === null) {
                return null;
            }

            $lastCompleted = 1;
            $pastEvents = $pastData['events'] ?? [];
            if (!empty($pastEvents)) {
                $lastCompleted = (int) ($pastEvents[0]['intRound'] ?? 1);
            }

            $nextEvents = $nextData['events'] ?? [];
            $nextUpcoming = !empty($nextEvents)
                ? (int) ($nextEvents[0]['intRound'] ?? $lastCompleted + 1)
                : $lastCompleted + 1;

            return [
                'last_completed' => $lastCompleted,
                'next_upcoming' => $nextUpcoming,
            ];
        }) ?? ['last_completed' => 1, 'next_upcoming' => 2];
    }

    /**
     * Fetch all events for a specific league round.
     *
     * Returns 9-10 events per round (full matchday) on the free tier.
     */
    protected function getEventsForRound(int $tsdbId, int $round): array
    {
        if ($round < 1) {
            return [];
        }

        $cacheKey = "tsdb:round:{$tsdbId}:{$round}:{$this->season}";
        $ttl = $this->cacheTtl['fixtures'] ?? 900;

        return $this->cacheRemember($cacheKey, $ttl, function () use ($tsdbId, $round) {
            $data = $this->get('eventsround.php', [
                'id' => $tsdbId,
                'r' => $round,
                's' => $this->season,
            ]);

            if ($data === null) {
                return null; // API failed – don't cache
            }

            return $data['events'] ?? [];
        }) ?? [];
    }

    // ── Fixtures / Events ──────────────────────────────────

    /**
     * Get fixtures for a specific date (or today).
     */
    public function getFixturesByDate(?string $date = null, ?int $leagueId = null, ?string $status = null): array
    {
        $date = $date ?? now()->format('Y-m-d');
        $cacheKey = "tsdb:fixtures:date:{$date}:" . ($leagueId ?? 'all') . ':' . ($status ?? 'all');
        $ttl = $this->cacheTtl['fixtures'] ?? 900;

        return $this->cacheRemember($cacheKey, $ttl, function () use ($date, $leagueId, $status) {
            $data = $this->get('eventsday.php', ['d' => $date, 's' => 'Soccer']);

            if ($data === null) {
                return null; // API failed – don't cache
            }

            $events = $data['events'] ?? [];

            if (!$events) {
                return [];
            }

            // Filter to configured leagues
            $tsdbLeagues = array_map(fn($id) => $this->resolveLeagueId($id), $this->leagues);

            if ($leagueId) {
                $tsdbId = $this->resolveLeagueId($leagueId);
                $events = array_filter($events, fn($e) => ($e['idLeague'] ?? '') == $tsdbId);
            } else {
                $events = array_filter($events, fn($e) => in_array((int) ($e['idLeague'] ?? 0), $tsdbLeagues));
            }

            $events = array_values($events);

            // Convert to our standard fixture format
            $fixtures = array_map(fn($e) => self::tsdbEventToFixture($e), $events);

            // Filter by status
            if ($status) {
                $fixtures = $this->filterByStatus($fixtures, $status);
            }

            return $fixtures;
        }) ?? [];
    }

    /**
     * Get upcoming fixtures across configured leagues.
     *
     * Strategy: detect the current round via eventspastleague / eventsnextleague
     * (each returns only 1 event on free tier), then fetch full rounds via
     * eventsround.php which gives 9-10 matches per round.
     */
    public function getUpcomingFixtures(int $limit = 20, ?int $leagueId = null): array
    {
        $targetLeagues = $leagueId ? [$leagueId] : $this->leagues;
        $allFixtures = [];

        // Limit look-ahead to stay within 30 req/min rate limit.
        // Single-league view: 3 rounds for richer data.
        // All-leagues view: 1 round per league (5 leagues × 1 = 5 round calls).
        $maxRoundsAhead = $leagueId ? 3 : 1;

        foreach ($targetLeagues as $league) {
            $tsdbId = $this->resolveLeagueId($league);
            $rounds = $this->getCurrentRounds($tsdbId);
            $nextRound = $rounds['next_upcoming'];

            $endRound = $nextRound + $maxRoundsAhead - 1;

            for ($r = $nextRound; $r <= $endRound; $r++) {
                $events = $this->getEventsForRound($tsdbId, $r);

                foreach ($events as $e) {
                    $status = strtolower(trim($e['strStatus'] ?? 'Not Started'));
                    if (in_array($status, ['not started', 'ns', ''])) {
                        $allFixtures[] = $e;
                    }
                }
            }
        }

        // Sort by date ascending
        usort(
            $allFixtures,
            fn($a, $b) =>
            strtotime($a['strTimestamp'] ?? $a['dateEvent'] ?? '0')
            - strtotime($b['strTimestamp'] ?? $b['dateEvent'] ?? '0')
        );

        $allFixtures = array_slice($allFixtures, 0, $limit);

        return array_map(fn($e) => self::tsdbEventToFixture($e), $allFixtures);
    }

    /**
     * Get recently finished fixtures across configured leagues.
     *
     * Strategy: detect the last completed round, then fetch that round
     * plus the 2 preceding rounds via eventsround.php (9-10 matches each).
     */
    public function getFinishedFixtures(int $limit = 20, ?int $leagueId = null): array
    {
        $targetLeagues = $leagueId ? [$leagueId] : $this->leagues;
        $allFixtures = [];

        // Same rate-limit strategy as upcoming.
        $maxRoundsBack = $leagueId ? 3 : 1;

        foreach ($targetLeagues as $league) {
            $tsdbId = $this->resolveLeagueId($league);
            $rounds = $this->getCurrentRounds($tsdbId);
            $lastRound = $rounds['last_completed'];

            $startRound = max(1, $lastRound - $maxRoundsBack + 1);

            for ($r = $lastRound; $r >= $startRound; $r--) {
                $events = $this->getEventsForRound($tsdbId, $r);

                foreach ($events as $e) {
                    $status = strtolower(trim($e['strStatus'] ?? ''));
                    $isFinished = in_array($status, [
                        'match finished',
                        'finished',
                        'ft',
                        'aet',
                        'after extra time',
                        'after pen',
                        'penalties',
                    ]);
                    if ($isFinished) {
                        $allFixtures[] = $e;
                    }
                }
            }
        }

        // Sort by date descending (most recent first)
        usort(
            $allFixtures,
            fn($a, $b) =>
            strtotime($b['strTimestamp'] ?? $b['dateEvent'] ?? '0')
            - strtotime($a['strTimestamp'] ?? $a['dateEvent'] ?? '0')
        );

        $allFixtures = array_slice($allFixtures, 0, $limit);

        return array_map(fn($e) => self::tsdbEventToFixture($e), $allFixtures);
    }

    /**
     * Get all currently live fixtures.
     * (Free tier doesn't have livescores; we check today's events for in-progress.)
     */
    public function getLiveFixtures(): array
    {
        $cacheKey = 'tsdb:live';
        $ttl = $this->cacheTtl['live'] ?? 60;

        return $this->cacheRemember($cacheKey, $ttl, function () {
            $today = now()->format('Y-m-d');
            $data = $this->get('eventsday.php', ['d' => $today, 's' => 'Soccer']);

            if ($data === null) {
                return null; // API failed – don't cache
            }

            $events = $data['events'] ?? [];

            if (!$events) {
                return [];
            }

            $tsdbLeagues = array_map(fn($id) => $this->resolveLeagueId($id), $this->leagues);

            // Filter to our leagues and currently live status
            $events = array_filter($events, function ($e) use ($tsdbLeagues) {
                $inLeague = in_array((int) ($e['idLeague'] ?? 0), $tsdbLeagues);
                $status = strtolower($e['strStatus'] ?? '');
                $isLive = in_array($status, ['1h', '2h', 'ht', 'et', 'live', 'in progress']);
                return $inLeague && $isLive;
            });

            return array_map(fn($e) => self::tsdbEventToFixture($e), array_values($events));
        }) ?? [];
    }

    /**
     * Fetch and cache the raw TheSportsDB event object for a fixture.
     *
     * lookupevent.php returns the richest payload: goal scorers, cards,
     * lineup strings, formations, shots, spectators, description, etc.
     * All detail methods (getFixture, getFixtureEvents, getFixtureLineups,
     * getFixtureStatistics) share this cached raw event.
     */
    protected function getRawEvent(int $fixtureId): ?array
    {
        $cacheKey = "tsdb:raw_event:{$fixtureId}";
        $ttl = $this->cacheTtl['fixture'] ?? 300;

        return $this->cacheRemember($cacheKey, $ttl, function () use ($fixtureId) {
            $data = $this->get('lookupevent.php', ['id' => $fixtureId]);
            if ($data === null) {
                return null;
            }
            $events = $data['events'] ?? [];
            return !empty($events) ? $events[0] : null;
        });
    }

    /**
     * Get a single fixture by its TheSportsDB event ID.
     */
    public function getFixture(int $fixtureId): ?array
    {
        $raw = $this->getRawEvent($fixtureId);
        return $raw ? self::tsdbEventToFixture($raw) : null;
    }

    /**
     * Get events (goals, cards) for a fixture.
     *
     * Parsed from the goal-detail & card-detail strings that
     * lookupevent.php returns (e.g. "45':Salah;90':Firmino;").
     */
    public function getFixtureEvents(int $fixtureId): array
    {
        $raw = $this->getRawEvent($fixtureId);
        if (!$raw) {
            return [];
        }
        return self::parseMatchEvents($raw);
    }

    /**
     * Get lineups for a fixture.
     *
     * Parsed from lineup strings that lookupevent.php returns
     * (e.g. strHomeLineupGoalkeeper, strHomeLineupDefense, etc.).
     */
    public function getFixtureLineups(int $fixtureId): array
    {
        $raw = $this->getRawEvent($fixtureId);
        if (!$raw) {
            return [];
        }
        return self::parseMatchLineups($raw);
    }

    /**
     * Get match statistics for a fixture.
     *
     * Extracts shots, cards counts, and any other stats available
     * from the lookupevent.php payload.
     */
    public function getFixtureStatistics(int $fixtureId): array
    {
        $raw = $this->getRawEvent($fixtureId);
        if (!$raw) {
            return [];
        }
        return self::parseMatchStatistics($raw);
    }

    /**
     * Get all fixtures with filtering and pagination.
     */
    public function getAllFixtures(array $filters = []): array
    {
        $status = $filters['status'] ?? null;
        $leagueId = isset($filters['league']) ? (int) $filters['league'] : null;
        $date = $filters['date'] ?? null;
        $page = max(1, (int) ($filters['page'] ?? 1));
        $perPage = 20;

        if ($date) {
            $fixtures = $this->getFixturesByDate($date, $leagueId, $status);
            return $this->paginateArray($fixtures, $page, $perPage);
        }

        $allFixtures = [];

        // Live
        if (!$status || $status === 'live') {
            $live = $this->getLiveFixtures();
            if ($leagueId) {
                $tsdbId = $this->resolveLeagueId($leagueId);
                $live = array_filter($live, fn($f) => ($f['league']['id'] ?? 0) == $tsdbId);
                $live = array_values($live);
            }
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

        return $this->paginateArray($allFixtures, $page, $perPage);
    }

    /**
     * Get the configured league names for filters (compact list).
     */
    public function getLeagues(): array
    {
        $cacheKey = 'tsdb:leagues:' . $this->season;
        $ttl = $this->cacheTtl['leagues'] ?? 86400;

        return $this->cacheRemember($cacheKey, $ttl, function () {
            $leagues = [];

            foreach ($this->leagues as $configId) {
                $tsdbId = $this->resolveLeagueId($configId);
                $meta = self::LEAGUE_META[$tsdbId] ?? null;

                if ($meta) {
                    $leagues[] = [
                        'id' => $tsdbId,
                        'name' => $meta['name'],
                        'country' => $meta['country'],
                        'logo' => null, // badge not stored in constant
                    ];
                }
            }

            return $leagues;
        });
    }

    // ── Leagues / Competitions ─────────────────────────────

    /**
     * Get full details for all configured leagues (competitions listing).
     *
     * Uses LEAGUE_META for instant display; enriches from API when available.
     * Avoids 5 lookupleague.php calls on every competitions page.
     */
    public function getLeaguesFull(): array
    {
        $cacheKey = 'tsdb:leagues_full:' . $this->season;
        $ttl = $this->cacheTtl['leagues'] ?? 86400;

        return $this->cacheRemember($cacheKey, $ttl, function () {
            $leagues = [];

            foreach ($this->leagues as $configId) {
                $tsdbId = $this->resolveLeagueId($configId);
                $meta = self::LEAGUE_META[$tsdbId] ?? null;

                if (!$meta) {
                    continue;
                }

                // Try the API for rich data (badge, banner, description)
                $data = $this->get('lookupleague.php', ['id' => $tsdbId]);
                $league = ($data['leagues'] ?? [])[0] ?? null;

                if ($league) {
                    $currentSeason = $league['strCurrentSeason'] ?? $this->season;

                    $leagues[] = [
                        'id' => (int) $league['idLeague'],
                        'name' => $league['strLeague'] ?? $meta['name'],
                        'type' => strtolower($league['strLeagueAlternate'] ?? '') ?: 'league',
                        'logo' => $league['strBadge'] ?? null,
                        'logo_wide' => $league['strLogo'] ?? null,
                        'country' => $league['strCountry'] ?? $meta['country'],
                        'country_code' => null,
                        'country_flag' => ($league['strCountry'] ?? $meta['country'])
                            ? 'https://flagsapi.com/' . self::countryToIso($league['strCountry'] ?? $meta['country']) . '/flat/32.png'
                            : null,
                        'season' => $currentSeason,
                        'season_start' => null,
                        'season_end' => null,
                        'banner' => $league['strBanner'] ?? null,
                        'fanart' => $league['strFanart1'] ?? null,
                        'trophy' => $league['strTrophy'] ?? null,
                        'poster' => $league['strPoster'] ?? null,
                        'description' => $league['strDescriptionEN'] ?? null,
                    ];
                } else {
                    // API failed (rate-limited) — use LEAGUE_META fallback
                    $leagues[] = [
                        'id' => $tsdbId,
                        'name' => $meta['name'],
                        'type' => 'league',
                        'logo' => null,
                        'logo_wide' => null,
                        'country' => $meta['country'],
                        'country_code' => null,
                        'country_flag' => 'https://flagsapi.com/' . self::countryToIso($meta['country']) . '/flat/32.png',
                        'season' => $this->season,
                        'season_start' => null,
                        'season_end' => null,
                        'banner' => null,
                        'fanart' => null,
                        'trophy' => null,
                        'poster' => null,
                        'description' => null,
                    ];
                }
            }

            return $leagues;
        });
    }

    /**
     * Get a single league/competition by its TheSportsDB ID.
     */
    public function getLeague(int $leagueId): ?array
    {
        $cacheKey = "tsdb:league:{$leagueId}:{$this->season}";
        $ttl = $this->cacheTtl['leagues'] ?? 86400;

        return $this->cacheRemember($cacheKey, $ttl, function () use ($leagueId) {
            $data = $this->get('lookupleague.php', ['id' => $leagueId]);
            $league = ($data['leagues'] ?? [])[0] ?? null;

            if ($league) {
                $currentSeason = $league['strCurrentSeason'] ?? $this->season;

                return [
                    'id' => (int) $league['idLeague'],
                    'name' => $league['strLeague'] ?? 'Unknown',
                    'type' => strtolower($league['strLeagueAlternate'] ?? '') ?: 'league',
                    'logo' => $league['strBadge'] ?? null,
                    'logo_wide' => $league['strLogo'] ?? null,
                    'country' => $league['strCountry'] ?? null,
                    'country_code' => null,
                    'country_flag' => $league['strCountry']
                        ? 'https://flagsapi.com/' . self::countryToIso($league['strCountry']) . '/flat/32.png'
                        : null,
                    'season' => $currentSeason,
                    'season_start' => null,
                    'season_end' => null,
                    'description' => $league['strDescriptionEN'] ?? null,
                    'banner' => $league['strBanner'] ?? null,
                    'fanart' => $league['strFanart1'] ?? null,
                    'trophy' => $league['strTrophy'] ?? null,
                    'poster' => $league['strPoster'] ?? null,
                ];
            }

            // API failed or league not found — fall back to LEAGUE_META
            $meta = self::LEAGUE_META[$leagueId] ?? null;
            if (!$meta) {
                return null;
            }

            return [
                'id' => $leagueId,
                'name' => $meta['name'],
                'type' => 'league',
                'logo' => null,
                'logo_wide' => null,
                'country' => $meta['country'],
                'country_code' => null,
                'country_flag' => 'https://flagsapi.com/' . self::countryToIso($meta['country']) . '/flat/32.png',
                'season' => $this->season,
                'season_start' => null,
                'season_end' => null,
                'description' => null,
                'banner' => null,
                'fanart' => null,
                'trophy' => null,
                'poster' => null,
            ];
        });
    }

    /**
     * Get standings for a league/season.
     */
    public function getStandings(int $leagueId, ?string $season = null): array
    {
        $season = $season ?? $this->season;
        $cacheKey = "tsdb:standings:{$leagueId}:{$season}";
        $ttl = $this->cacheTtl['standings'] ?? 3600;

        return $this->cacheRemember($cacheKey, $ttl, function () use ($leagueId, $season) {
            $data = $this->get('lookuptable.php', ['l' => $leagueId, 's' => $season]);

            if ($data === null) {
                return null; // API failed – don't cache
            }

            $table = $data['table'] ?? [];

            if (empty($table)) {
                return [];
            }

            // Return as array of one group (matching old API-Football format)
            return [$table];
        }) ?? [];
    }

    // ── Teams / Clubs ──────────────────────────────────────

    /**
     * Get all teams for a specific league.
     *
     * Strategy: extract all unique teams from season events (eventsseason.php
     * returns ~15 events → covers all 18-20 teams from home/away data).
     * Falls back to search_all_teams (limited to 10) when season data is empty.
     */
    public function getTeamsByLeague(int $leagueId, ?string $season = null): array
    {
        $cacheKey = "tsdb:teams:league:{$leagueId}:{$this->season}";
        $ttl = $this->cacheTtl['teams'] ?? 86400;

        // Don't serve a cached empty array – it was likely a rate-limit failure.
        $cached = Cache::get($cacheKey);
        if (is_array($cached) && !empty($cached)) {
            return $cached;
        }

        // Use constants to avoid a lookupleague API call.
        $meta = self::LEAGUE_META[$leagueId] ?? null;
        $leagueName = $meta['name'] ?? null;
        $country = $meta['country'] ?? null;

        // 1) search_all_teams: gives full team data (venue, founded, etc.)
        //    but limited to 10 results on the free tier.
        $searchTeams = [];
        if ($leagueName) {
            $searchData = $this->get('search_all_teams.php', ['l' => $leagueName]);
            foreach ($searchData['teams'] ?? [] as $t) {
                $raw = self::tsdbTeamToRaw($t);
                $searchTeams[(int) ($t['idTeam'] ?? 0)] = $raw;
            }
        }

        // 2) Season events: discover ALL 18-20 team IDs from home/away data.
        //    Teams not already in search results get a minimal entry.
        $data = $this->get('eventsseason.php', ['id' => $leagueId, 's' => $this->season]);
        $events = $data['events'] ?? [];

        $allTeams = $searchTeams;

        foreach ($events as $e) {
            $homeId = (int) ($e['idHomeTeam'] ?? 0);
            $awayId = (int) ($e['idAwayTeam'] ?? 0);

            if ($homeId && !isset($allTeams[$homeId])) {
                $cached = Cache::get("tsdb:team:{$homeId}");
                $allTeams[$homeId] = $cached ?: [
                    'team' => [
                        'id' => $homeId,
                        'name' => $e['strHomeTeam'] ?? 'Unknown',
                        'code' => null,
                        'country' => $country,
                        'founded' => null,
                        'national' => false,
                        'logo' => $e['strHomeTeamBadge'] ?? null,
                    ],
                    'venue' => [
                        'id' => null,
                        'name' => null,
                        'city' => null,
                        'capacity' => null,
                        'surface' => null,
                        'image' => null,
                    ],
                ];
            }

            if ($awayId && !isset($allTeams[$awayId])) {
                $cached = Cache::get("tsdb:team:{$awayId}");
                $allTeams[$awayId] = $cached ?: [
                    'team' => [
                        'id' => $awayId,
                        'name' => $e['strAwayTeam'] ?? 'Unknown',
                        'code' => null,
                        'country' => $country,
                        'founded' => null,
                        'national' => false,
                        'logo' => $e['strAwayTeamBadge'] ?? null,
                    ],
                    'venue' => [
                        'id' => null,
                        'name' => null,
                        'city' => null,
                        'capacity' => null,
                        'surface' => null,
                        'image' => null,
                    ],
                ];
            }
        }

        // Only cache if we actually got results.
        if (!empty($allTeams)) {
            $teams = array_values($allTeams);
            usort($teams, fn($a, $b) => strcasecmp($a['team']['name'] ?? '', $b['team']['name'] ?? ''));
            Cache::put($cacheKey, $teams, $ttl);

            // Warm individual team caches ONLY for teams with full data
            // (from search_all_teams). Minimal stubs from events should NOT
            // pollute the cache — getTeam() needs to call lookupteam.php
            // for full details (description, social links, stadium, etc.).
            foreach ($searchTeams as $teamId => $teamData) {
                $teamCacheKey = "tsdb:team:{$teamId}";
                if (!Cache::has($teamCacheKey)) {
                    Cache::put($teamCacheKey, $teamData, $ttl);
                }
            }

            return $teams;
        }

        return [];
    }

    /**
     * Get all teams across all configured leagues.
     */
    public function getAllTeams(?string $search = null, ?string $country = null): array
    {
        $allTeams = [];

        foreach ($this->leagues as $configId) {
            $tsdbId = $this->resolveLeagueId($configId);
            $teams = $this->getTeamsByLeague($tsdbId);
            foreach ($teams as $team) {
                $allTeams[$team['team']['id']] = $team; // deduplicate by id
            }
        }

        $allTeams = array_values($allTeams);

        // Apply search filter
        if ($search) {
            $search = strtolower($search);
            $allTeams = array_filter($allTeams, function ($t) use ($search) {
                return str_contains(strtolower($t['team']['name'] ?? ''), $search)
                    || str_contains(strtolower($t['team']['code'] ?? ''), $search);
            });
            $allTeams = array_values($allTeams);
        }

        // Apply country filter
        if ($country) {
            $country = strtolower($country);
            $allTeams = array_filter($allTeams, function ($t) use ($country) {
                return str_contains(strtolower($t['team']['country'] ?? ''), $country);
            });
            $allTeams = array_values($allTeams);
        }

        // Sort by name
        usort($allTeams, fn($a, $b) => strcasecmp($a['team']['name'] ?? '', $b['team']['name'] ?? ''));

        return $allTeams;
    }

    /**
     * Get a single team by its TheSportsDB team ID.
     */
    public function getTeam(int $teamId): ?array
    {
        $cacheKey = "tsdb:team:{$teamId}";
        $ttl = $this->cacheTtl['teams'] ?? 86400;

        return $this->cacheRemember($cacheKey, $ttl, function () use ($teamId) {
            $data = $this->get('lookupteam.php', ['id' => $teamId]);

            if ($data === null) {
                return null; // API failed – don't cache
            }

            $teams = $data['teams'] ?? [];

            if (empty($teams)) {
                return null;
            }

            return self::tsdbTeamToRaw($teams[0]);
        });
    }

    /**
     * Get the full squad (players) for a team.
     */
    public function getTeamSquad(int $teamId): array
    {
        $cacheKey = "tsdb:squad:{$teamId}";
        $ttl = $this->cacheTtl['teams'] ?? 86400;

        return $this->cacheRemember($cacheKey, $ttl, function () use ($teamId) {
            $data = $this->get('lookup_all_players.php', ['id' => $teamId]);

            if ($data === null) {
                return null; // API failed – don't cache
            }

            $players = $data['player'] ?? [];

            if (empty($players)) {
                return [];
            }

            return array_values(array_filter(
                array_map(fn($p) => self::tsdbPlayerToRaw($p), $players)
            ));
        }) ?? [];
    }

    /**
     * Get fixtures for a specific team.
     *
     * Strategy: look up the team to find its league, then fetch
     * surrounding rounds via eventsround and filter to this team.
     * Gives ~5 matches each for recent/upcoming instead of 1.
     */
    public function getTeamFixtures(int $teamId, ?string $status = null, int $limit = 10): array
    {
        $cacheKey = "tsdb:team_fixtures:{$teamId}:{$status}:{$limit}";
        $ttl = $this->cacheTtl['fixtures'] ?? 900;

        return $this->cacheRemember($cacheKey, $ttl, function () use ($teamId, $status, $limit) {
            // Find the team's league
            $teamData = $this->get('lookupteam.php', ['id' => $teamId]);
            $team = ($teamData['teams'] ?? [])[0] ?? null;
            $leagueId = (int) ($team['idLeague'] ?? 0);

            if (!$leagueId) {
                return [];
            }

            $rounds = $this->getCurrentRounds($leagueId);
            $lastRound = $rounds['last_completed'];
            $nextRound = $rounds['next_upcoming'];

            $allEvents = [];

            if ($status === 'finished') {
                // Each team plays 1 match per round; scan $limit+2 rounds backward
                $scanFrom = $lastRound;
                $scanTo = max(1, $lastRound - $limit - 1);

                for ($r = $scanFrom; $r >= $scanTo; $r--) {
                    $events = $this->getEventsForRound($leagueId, $r);

                    foreach ($events as $e) {
                        $isTeam = ((int) ($e['idHomeTeam'] ?? 0) === $teamId)
                            || ((int) ($e['idAwayTeam'] ?? 0) === $teamId);
                        $st = strtolower(trim($e['strStatus'] ?? ''));
                        $isFinished = in_array($st, [
                            'match finished',
                            'finished',
                            'ft',
                            'aet',
                            'after extra time',
                            'after pen',
                            'penalties',
                        ]);

                        if ($isTeam && $isFinished) {
                            $allEvents[] = $e;
                        }
                    }

                    if (count($allEvents) >= $limit) {
                        break;
                    }
                }

                // Most recent first
                usort(
                    $allEvents,
                    fn($a, $b) =>
                    strtotime($b['strTimestamp'] ?? $b['dateEvent'] ?? '0')
                    - strtotime($a['strTimestamp'] ?? $a['dateEvent'] ?? '0')
                );
            } elseif ($status === 'upcoming') {
                // Each team plays 1 match per round; scan $limit+2 rounds forward
                $startRound = min($lastRound + 1, $nextRound);
                $endRound = $startRound + $limit + 1;

                for ($r = $startRound; $r <= $endRound; $r++) {
                    $events = $this->getEventsForRound($leagueId, $r);

                    // Empty round means we've gone past the season
                    if (empty($events)) {
                        break;
                    }

                    foreach ($events as $e) {
                        $isTeam = ((int) ($e['idHomeTeam'] ?? 0) === $teamId)
                            || ((int) ($e['idAwayTeam'] ?? 0) === $teamId);
                        $st = strtolower(trim($e['strStatus'] ?? 'Not Started'));
                        $isUpcoming = in_array($st, ['not started', 'ns', '']);

                        if ($isTeam && $isUpcoming) {
                            $allEvents[] = $e;
                        }
                    }

                    if (count($allEvents) >= $limit) {
                        break;
                    }
                }

                // Soonest first
                usort(
                    $allEvents,
                    fn($a, $b) =>
                    strtotime($a['strTimestamp'] ?? $a['dateEvent'] ?? '0')
                    - strtotime($b['strTimestamp'] ?? $b['dateEvent'] ?? '0')
                );
            } else {
                return [];
            }

            $allEvents = array_slice($allEvents, 0, $limit);

            return array_map(fn($e) => self::tsdbEventToFixture($e), $allEvents);
        }) ?? [];
    }

    /**
     * Get team statistics for a given league/season.
     * TheSportsDB free tier doesn't have team stats – return null.
     */
    public function getTeamStatistics(int $teamId, int $leagueId, ?string $season = null): ?array
    {
        return null; // Not available on free tier
    }

    // ── Data Normalisation ────────────────────────────────

    /**
     * Convert a TheSportsDB event into the standard fixture format
     * that our controllers and views expect.
     */
    public static function tsdbEventToFixture(array $e): array
    {
        $statusRaw = strtolower(trim($e['strStatus'] ?? 'Not Started'));
        $statusShort = self::tsdbStatusToShort($statusRaw);
        $status = self::mapStatus($statusShort);

        $homeScore = $e['intHomeScore'] ?? null;
        $awayScore = $e['intAwayScore'] ?? null;

        // If scores are empty strings, treat as null
        if ($homeScore === '' || $homeScore === null) {
            $homeScore = null;
        } else {
            $homeScore = (int) $homeScore;
        }
        if ($awayScore === '' || $awayScore === null) {
            $awayScore = null;
        } else {
            $awayScore = (int) $awayScore;
        }

        $isPlayed = $homeScore !== null;
        $scoreDisplay = $isPlayed ? "{$homeScore} - {$awayScore}" : 'vs';

        $dateStr = ($e['strTimestamp'] ?? null)
            ?: (($e['dateEvent'] ?? '') . 'T' . ($e['strTime'] ?? '00:00:00'));

        return [
            'id' => (int) ($e['idEvent'] ?? 0),
            'referee' => null,
            'date' => $dateStr,
            'timestamp' => strtotime($dateStr) ?: null,
            'venue' => $e['strVenue'] ?? null,
            'status' => $status,
            'status_short' => $statusShort,
            'status_long' => $e['strStatus'] ?? 'Not Started',
            'elapsed' => null,
            'league' => [
                'id' => (int) ($e['idLeague'] ?? 0),
                'name' => $e['strLeague'] ?? 'Unknown',
                'country' => $e['strCountry'] ?? null,
                'logo' => $e['strLeagueBadge'] ?? null,
                'round' => isset($e['intRound']) ? "Round {$e['intRound']}" : null,
            ],
            'home_team' => [
                'id' => (int) ($e['idHomeTeam'] ?? 0),
                'name' => $e['strHomeTeam'] ?? 'TBD',
                'logo' => $e['strHomeTeamBadge'] ?? null,
                'winner' => $isPlayed
                    ? ($homeScore > $awayScore ? true : ($homeScore < $awayScore ? false : null))
                    : null,
            ],
            'away_team' => [
                'id' => (int) ($e['idAwayTeam'] ?? 0),
                'name' => $e['strAwayTeam'] ?? 'TBD',
                'logo' => $e['strAwayTeamBadge'] ?? null,
                'winner' => $isPlayed
                    ? ($awayScore > $homeScore ? true : ($awayScore < $homeScore ? false : null))
                    : null,
            ],
            'home_score' => $homeScore,
            'away_score' => $awayScore,
            'ht_score' => ['home' => null, 'away' => null],
            'ft_score' => ['home' => $homeScore, 'away' => $awayScore],
            'et_score' => ['home' => null, 'away' => null],
            'penalty_score' => ['home' => null, 'away' => null],
            'score_display' => $scoreDisplay,
            'result' => !empty($e['strResult']) ? $e['strResult'] : null,
            'description' => !empty($e['strDescriptionEN']) ? $e['strDescriptionEN'] : null,
            'season' => $e['strSeason'] ?? null,
            'spectators' => isset($e['intSpectators']) && $e['intSpectators'] !== '' ? (int) $e['intSpectators'] : null,
            'home_formation' => !empty($e['strHomeFormation']) ? $e['strHomeFormation'] : null,
            'away_formation' => !empty($e['strAwayFormation']) ? $e['strAwayFormation'] : null,
            'home_goal_details' => !empty($e['strHomeGoalDetails']) ? $e['strHomeGoalDetails'] : null,
            'away_goal_details' => !empty($e['strAwayGoalDetails']) ? $e['strAwayGoalDetails'] : null,
            'poster' => $e['strPoster'] ?? null,
            'thumb' => $e['strThumb'] ?? null,
            'banner' => $e['strBanner'] ?? null,
            'square' => $e['strSquare'] ?? null,
            'fanart' => $e['strFanart'] ?? null,
            'video' => $e['strVideo'] ?? null,
        ];
    }

    /**
     * Normalise a fixture (compatibility alias used by controllers).
     */
    public static function normaliseFixture(array $raw): array
    {
        // If already converted (has home_team key), pass through
        if (isset($raw['home_team']) && isset($raw['away_team'])) {
            return $raw;
        }
        return self::tsdbEventToFixture($raw);
    }

    /**
     * Normalise an event from fixture events endpoint.
     */
    public static function normaliseEvent(array $raw): array
    {
        return $raw; // Not available on free tier – pass-through
    }

    /**
     * Normalise a lineup entry.
     */
    public static function normaliseLineup(array $raw): array
    {
        return $raw; // Not available on free tier – pass-through
    }

    /**
     * Normalise a team from the teams data.
     * Accepts raw TheSportsDB team wrapper format.
     */
    public static function normaliseTeam(array $raw): array
    {
        $team = $raw['team'] ?? [];
        $venue = $raw['venue'] ?? [];

        return [
            'id' => $team['id'] ?? 0,
            'name' => $team['name'] ?? 'Unknown',
            'code' => $team['code'] ?? null,
            'country' => $team['country'] ?? null,
            'founded' => $team['founded'] ?? null,
            'national' => $team['national'] ?? false,
            'logo' => $team['logo'] ?? null,
            'banner' => $team['banner'] ?? null,
            'fanart' => $team['fanart'] ?? null,
            'jersey' => $team['jersey'] ?? null,
            'description' => $team['description'] ?? null,
            'gender' => $team['gender'] ?? null,
            'league' => $team['league'] ?? null,
            'league_id' => $team['league_id'] ?? null,
            'colours' => [
                'primary' => $team['colour1'] ?? null,
                'secondary' => $team['colour2'] ?? null,
                'tertiary' => $team['colour3'] ?? null,
            ],
            'social' => [
                'website' => $team['website'] ?? null,
                'facebook' => $team['facebook'] ?? null,
                'twitter' => $team['twitter'] ?? null,
                'instagram' => $team['instagram'] ?? null,
                'youtube' => $team['youtube'] ?? null,
            ],
            'venue' => [
                'id' => $venue['id'] ?? null,
                'name' => $venue['name'] ?? null,
                'city' => $venue['city'] ?? null,
                'capacity' => $venue['capacity'] ?? null,
                'surface' => $venue['surface'] ?? null,
                'image' => $venue['image'] ?? null,
                'description' => $venue['description'] ?? null,
            ],
        ];
    }

    /**
     * Normalise a standing row from the standings data.
     * Accepts TheSportsDB table row format.
     */
    public static function normaliseStandingRow(array $raw): array
    {
        return [
            'rank' => (int) ($raw['intRank'] ?? 0),
            'team' => [
                'id' => (int) ($raw['idTeam'] ?? 0),
                'name' => $raw['strTeam'] ?? 'Unknown',
                'logo' => $raw['strBadge'] ?? null,
            ],
            'points' => (int) ($raw['intPoints'] ?? 0),
            'goals_diff' => (int) ($raw['intGoalDifference'] ?? 0),
            'played' => (int) ($raw['intPlayed'] ?? 0),
            'won' => (int) ($raw['intWin'] ?? 0),
            'drawn' => (int) ($raw['intDraw'] ?? 0),
            'lost' => (int) ($raw['intLoss'] ?? 0),
            'goals_for' => (int) ($raw['intGoalsFor'] ?? 0),
            'goals_against' => (int) ($raw['intGoalsAgainst'] ?? 0),
            'form' => $raw['strForm'] ?? '',
            'description' => $raw['strDescription'] ?? null,
            'status' => null,
        ];
    }

    /**
     * Normalise a squad player from the players data.
     */
    public static function normaliseSquadPlayer(array $raw): array
    {
        return $raw; // Already in our format from tsdbPlayerToRaw
    }

    // ── Raw format converters ──────────────────────────────

    /**
     * Convert a TheSportsDB team object into the raw wrapper format
     * that our normaliseTeam() expects.
     */
    protected static function tsdbTeamToRaw(array $t): array
    {
        return [
            'team' => [
                'id' => (int) ($t['idTeam'] ?? 0),
                'name' => $t['strTeam'] ?? 'Unknown',
                'code' => $t['strTeamShort'] ?? null,
                'country' => $t['strCountry'] ?? null,
                'founded' => $t['intFormedYear'] ?? null,
                'national' => false,
                'logo' => $t['strBadge'] ?? null,
                'banner' => $t['strBanner'] ?? $t['strTeamBanner'] ?? null,
                'fanart' => $t['strFanart1'] ?? null,
                'jersey' => $t['strTeamJersey'] ?? $t['strEquipment'] ?? null,
                'description' => $t['strDescriptionEN'] ?? null,
                'gender' => $t['strGender'] ?? null,
                'league' => $t['strLeague'] ?? null,
                'league_id' => isset($t['idLeague']) ? (int) $t['idLeague'] : null,
                'colour1' => $t['strColour1'] ?? null,
                'colour2' => $t['strColour2'] ?? null,
                'colour3' => $t['strColour3'] ?? null,
                'website' => $t['strWebsite'] ?? null,
                'facebook' => $t['strFacebook'] ?? null,
                'twitter' => $t['strTwitter'] ?? null,
                'instagram' => $t['strInstagram'] ?? null,
                'youtube' => $t['strYoutube'] ?? null,
            ],
            'venue' => [
                'id' => null,
                'name' => $t['strStadium'] ?? null,
                'city' => $t['strStadiumLocation'] ?? null,
                'capacity' => isset($t['intStadiumCapacity']) ? (int) $t['intStadiumCapacity'] : null,
                'surface' => null,
                'image' => $t['strStadiumThumb'] ?? null,
                'description' => $t['strStadiumDescription'] ?? null,
            ],
        ];
    }

    /**
     * Convert a TheSportsDB player object into our normalised format.
     */
    protected static function tsdbPlayerToRaw(array $p): ?array
    {
        $position = self::mapPlayerPosition($p['strPosition'] ?? '');

        // Filter out coaches, managers, and other non-player staff
        if ($position === null) {
            return null;
        }

        return [
            'id' => (int) ($p['idPlayer'] ?? 0),
            'name' => $p['strPlayer'] ?? 'Unknown',
            'age' => isset($p['dateBorn']) && $p['dateBorn']
                ? (int) now()->diffInYears(\Carbon\Carbon::parse($p['dateBorn']))
                : null,
            'number' => $p['strNumber'] ?? null,
            'position' => $position,
            'photo' => $p['strCutout'] ?? $p['strThumb'] ?? $p['strRender'] ?? null,
        ];
    }

    /**
     * Map TheSportsDB detailed position strings to the 4 view categories.
     *
     * Returns null for non-player roles (coach, manager, etc.).
     */
    protected static function mapPlayerPosition(string $raw): ?string
    {
        $pos = strtolower(trim($raw));

        if ($pos === '' || $pos === 'null') {
            return null;
        }

        // Coaches / staff → exclude
        if (
            str_contains($pos, 'coach') || str_contains($pos, 'manager')
            || str_contains($pos, 'director') || str_contains($pos, 'physio')
            || str_contains($pos, 'scout') || str_contains($pos, 'analyst')
        ) {
            return null;
        }

        // Goalkeeper
        if (str_contains($pos, 'goalkeeper') || str_contains($pos, 'keeper')) {
            return 'Goalkeeper';
        }

        // Defenders
        if (
            str_contains($pos, 'back') || str_contains($pos, 'centre-back')
            || str_contains($pos, 'center-back') || str_contains($pos, 'defender')
            || str_contains($pos, 'defence') || str_contains($pos, 'defense')
        ) {
            return 'Defender';
        }

        // Midfielders
        if (
            str_contains($pos, 'midfield') || str_contains($pos, 'midfielder')
            || str_contains($pos, 'pivot')
        ) {
            return 'Midfielder';
        }

        // Attackers / Forwards / Wingers
        if (
            str_contains($pos, 'forward') || str_contains($pos, 'striker')
            || str_contains($pos, 'winger') || str_contains($pos, 'wing')
            || str_contains($pos, 'attacker') || str_contains($pos, 'attack')
        ) {
            return 'Attacker';
        }

        // Fallback: treat unknown playing positions as Midfielder
        return 'Midfielder';
    }

    // ── Match Detail Parsers ───────────────────────────────

    /**
     * Parse a TheSportsDB detail string into structured entries.
     *
     * TheSportsDB encodes goals, cards, etc. as semicolon-delimited strings:
     *   "45':Salah;90'+2':Firmino (Penalty);"
     *
     * @return array<int, array{time: int, player: string, extra: string}>
     */
    protected static function parseDetailString(string $details): array
    {
        $details = trim($details);
        if (empty($details)) {
            return [];
        }

        $result = [];
        $entries = array_filter(array_map('trim', explode(';', $details)));

        foreach ($entries as $entry) {
            if (empty($entry)) {
                continue;
            }

            // Match patterns like "45':Player" or "90'+2':Player (Penalty)"
            if (preg_match("/^(\d+)'(?:\+(\d+))?:(.*)$/", $entry, $matches)) {
                $time = (int) $matches[1];
                $playerAndExtra = trim($matches[3]);

                // Extract extra info in parentheses
                $extra = '';
                $player = $playerAndExtra;
                if (preg_match('/^(.*?)\s*\(([^)]+)\)\s*$/', $playerAndExtra, $pMatches)) {
                    $player = trim($pMatches[1]);
                    $extra = trim($pMatches[2]);
                }

                $result[] = [
                    'time' => $time,
                    'player' => $player,
                    'extra' => $extra,
                ];
            }
        }

        return $result;
    }

    /**
     * Parse goal and card events from a raw TheSportsDB event.
     *
     * @return array<int, array{time: int, team_id: int, team_name: string, type: string, detail: string, player: string, assist: ?string, icon: string}>
     */
    protected static function parseMatchEvents(array $e): array
    {
        $events = [];
        $homeId = (int) ($e['idHomeTeam'] ?? 0);
        $awayId = (int) ($e['idAwayTeam'] ?? 0);
        $homeName = $e['strHomeTeam'] ?? 'Home';
        $awayName = $e['strAwayTeam'] ?? 'Away';

        // ── Goals ──
        foreach (self::parseDetailString($e['strHomeGoalDetails'] ?? '') as $d) {
            $events[] = self::buildEventEntry($d, $homeId, $homeName, 'Goal');
        }
        foreach (self::parseDetailString($e['strAwayGoalDetails'] ?? '') as $d) {
            $events[] = self::buildEventEntry($d, $awayId, $awayName, 'Goal');
        }

        // ── Red Cards ──
        foreach (self::parseDetailString($e['strHomeRedCards'] ?? '') as $d) {
            $events[] = [
                'time' => $d['time'],
                'team_id' => $homeId,
                'team_name' => $homeName,
                'type' => 'Card',
                'detail' => 'Red Card',
                'player' => $d['player'],
                'assist' => null,
                'icon' => '🟥',
            ];
        }
        foreach (self::parseDetailString($e['strAwayRedCards'] ?? '') as $d) {
            $events[] = [
                'time' => $d['time'],
                'team_id' => $awayId,
                'team_name' => $awayName,
                'type' => 'Card',
                'detail' => 'Red Card',
                'player' => $d['player'],
                'assist' => null,
                'icon' => '🟥',
            ];
        }

        // ── Yellow Cards ──
        foreach (self::parseDetailString($e['strHomeYellowCards'] ?? '') as $d) {
            $events[] = [
                'time' => $d['time'],
                'team_id' => $homeId,
                'team_name' => $homeName,
                'type' => 'Card',
                'detail' => 'Yellow Card',
                'player' => $d['player'],
                'assist' => null,
                'icon' => '🟨',
            ];
        }
        foreach (self::parseDetailString($e['strAwayYellowCards'] ?? '') as $d) {
            $events[] = [
                'time' => $d['time'],
                'team_id' => $awayId,
                'team_name' => $awayName,
                'type' => 'Card',
                'detail' => 'Yellow Card',
                'player' => $d['player'],
                'assist' => null,
                'icon' => '🟨',
            ];
        }

        // Sort chronologically
        usort($events, fn($a, $b) => ($a['time'] ?? 0) - ($b['time'] ?? 0));

        return $events;
    }

    /**
     * Build a single goal event entry from a parsed detail.
     */
    protected static function buildEventEntry(array $d, int $teamId, string $teamName, string $type): array
    {
        $extra = strtolower($d['extra'] ?? '');
        $detail = 'Normal Goal';
        $icon = '⚽';

        if (str_contains($extra, 'own')) {
            $detail = 'Own Goal';
            $icon = '🔴';
        } elseif (str_contains($extra, 'pen')) {
            $detail = 'Penalty';
            $icon = '⚽(P)';
        }

        return [
            'time' => $d['time'],
            'team_id' => $teamId,
            'team_name' => $teamName,
            'type' => $type,
            'detail' => $detail,
            'player' => $d['player'],
            'assist' => null,
            'icon' => $icon,
        ];
    }

    /**
     * Parse lineups from a raw TheSportsDB event.
     *
     * Returns one entry per team (home first, away second).
     */
    protected static function parseMatchLineups(array $e): array
    {
        $lineups = [];

        $homeLineup = self::parseTeamLineup($e, 'Home');
        $awayLineup = self::parseTeamLineup($e, 'Away');

        if ($homeLineup) {
            $homeLineup['team'] = [
                'id' => (int) ($e['idHomeTeam'] ?? 0),
                'name' => $e['strHomeTeam'] ?? 'Home',
                'logo' => $e['strHomeTeamBadge'] ?? null,
            ];
            $lineups[] = $homeLineup;
        }

        if ($awayLineup) {
            $awayLineup['team'] = [
                'id' => (int) ($e['idAwayTeam'] ?? 0),
                'name' => $e['strAwayTeam'] ?? 'Away',
                'logo' => $e['strAwayTeamBadge'] ?? null,
            ];
            $lineups[] = $awayLineup;
        }

        return $lineups;
    }

    /**
     * Parse a single team's lineup from the event payload.
     *
     * @param  string $side  'Home' or 'Away'
     */
    protected static function parseTeamLineup(array $e, string $side): ?array
    {
        $gk = trim($e["str{$side}LineupGoalkeeper"] ?? '');
        $def = trim($e["str{$side}LineupDefense"] ?? '');
        $mid = trim($e["str{$side}LineupMidfield"] ?? '');
        $fwd = trim($e["str{$side}LineupForward"] ?? '');
        $subs = trim($e["str{$side}LineupSubstitutes"] ?? '');
        $formation = trim($e["str{$side}Formation"] ?? '');

        // No lineup data at all → return null
        if (empty($gk) && empty($def) && empty($mid) && empty($fwd)) {
            return null;
        }

        $startXI = [];
        foreach (self::parseLineupPlayers($gk) as $player) {
            $startXI[] = ['player' => ['name' => $player, 'pos' => 'G', 'grid' => null]];
        }
        foreach (self::parseLineupPlayers($def) as $player) {
            $startXI[] = ['player' => ['name' => $player, 'pos' => 'D', 'grid' => null]];
        }
        foreach (self::parseLineupPlayers($mid) as $player) {
            $startXI[] = ['player' => ['name' => $player, 'pos' => 'M', 'grid' => null]];
        }
        foreach (self::parseLineupPlayers($fwd) as $player) {
            $startXI[] = ['player' => ['name' => $player, 'pos' => 'F', 'grid' => null]];
        }

        $substitutes = [];
        foreach (self::parseLineupPlayers($subs) as $player) {
            $substitutes[] = ['player' => ['name' => $player, 'pos' => null, 'grid' => null]];
        }

        return [
            'formation' => $formation ?: null,
            'startXI' => $startXI,
            'substitutes' => $substitutes,
        ];
    }

    /**
     * Split a semicolon/comma-delimited lineup string into player names.
     */
    protected static function parseLineupPlayers(string $lineup): array
    {
        if (empty(trim($lineup))) {
            return [];
        }

        return array_values(array_filter(
            array_map('trim', preg_split('/[;,]/', $lineup)),
            fn($name) => !empty($name)
        ));
    }

    /**
     * Parse match statistics from a raw TheSportsDB event.
     *
     * Extracts shots, card counts, and any other available numeric stats.
     */
    protected static function parseMatchStatistics(array $e): array
    {
        $stats = [];

        // Direct stat fields from TheSportsDB
        $statFields = [
            ['intHomeShots', 'intAwayShots', 'Total Shots'],
            ['intHomeShotsOnTarget', 'intAwayShotsOnTarget', 'Shots on Target'],
        ];

        foreach ($statFields as [$homeKey, $awayKey, $label]) {
            $home = $e[$homeKey] ?? null;
            $away = $e[$awayKey] ?? null;

            if ($home !== null && $home !== '' && $away !== null && $away !== '') {
                $stats[] = [
                    'type' => $label,
                    'home' => (int) $home,
                    'away' => (int) $away,
                ];
            }
        }

        // Derive card counts from event detail strings
        $homeYellows = count(self::parseDetailString($e['strHomeYellowCards'] ?? ''));
        $awayYellows = count(self::parseDetailString($e['strAwayYellowCards'] ?? ''));
        $homeReds = count(self::parseDetailString($e['strHomeRedCards'] ?? ''));
        $awayReds = count(self::parseDetailString($e['strAwayRedCards'] ?? ''));

        if ($homeYellows > 0 || $awayYellows > 0) {
            $stats[] = [
                'type' => 'Yellow Cards',
                'home' => $homeYellows,
                'away' => $awayYellows,
            ];
        }

        if ($homeReds > 0 || $awayReds > 0) {
            $stats[] = [
                'type' => 'Red Cards',
                'home' => $homeReds,
                'away' => $awayReds,
            ];
        }

        // Spectators
        if (isset($e['intSpectators']) && $e['intSpectators'] !== '' && $e['intSpectators'] !== null) {
            $stats[] = [
                'type' => 'Spectators',
                'home' => (int) $e['intSpectators'],
                'away' => null,
            ];
        }

        return $stats;
    }

    // ── Helpers ────────────────────────────────────────────

    /**
     * Map TheSportsDB status string to a short code.
     */
    protected static function tsdbStatusToShort(string $status): string
    {
        return match (true) {
            str_contains($status, 'not started'), $status === 'ns', $status === '' => 'NS',
            str_contains($status, '1st half'), $status === '1h' => '1H',
            str_contains($status, '2nd half'), $status === '2h' => '2H',
            str_contains($status, 'half time'), $status === 'ht' => 'HT',
            str_contains($status, 'extra time'), $status === 'et' => 'ET',
            str_contains($status, 'finished'), str_contains($status, 'match finished'), $status === 'ft' => 'FT',
            str_contains($status, 'after extra'), $status === 'aet' => 'AET',
            str_contains($status, 'penalties'), str_contains($status, 'after pen'), $status === 'pen' => 'PEN',
            str_contains($status, 'postponed') => 'PST',
            str_contains($status, 'cancelled'), str_contains($status, 'canceled') => 'CANC',
            str_contains($status, 'suspended') => 'SUSP',
            str_contains($status, 'abandoned') => 'ABD',
            str_contains($status, 'live'), str_contains($status, 'in progress') => 'LIVE',
            default => 'NS',
        };
    }

    /**
     * Map status short codes to our internal status names.
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
            $fixtureStatus = $fixture['status'] ?? 'unknown';

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

    /**
     * Map common country names to ISO 3166-1 alpha-2 codes for flag URLs.
     */
    protected static function countryToIso(string $country): string
    {
        $map = [
            'England' => 'GB',
            'Spain' => 'ES',
            'Italy' => 'IT',
            'Germany' => 'DE',
            'France' => 'FR',
            'Portugal' => 'PT',
            'Netherlands' => 'NL',
            'Belgium' => 'BE',
            'Scotland' => 'GB',
            'Turkey' => 'TR',
            'Brazil' => 'BR',
            'Argentina' => 'AR',
        ];

        return $map[$country] ?? 'UN';
    }
}
