<?php

namespace App\Http\Controllers\Api;

use App\Services\FootballApiService;
use Illuminate\Http\Request;

class MatchApiController extends BaseApiController
{
    public function __construct(
        protected FootballApiService $api,
    ) {
    }

    public function index(Request $request)
    {
        $result = $this->api->getAllFixtures([
            'status' => $request->input('status'),
            'league' => $request->input('league'),
            'date' => $request->input('date'),
            'page' => $request->input('page', 1),
        ]);

        $result['data'] = array_map(
            fn(array $raw) => FootballApiService::normaliseFixture($raw),
            $result['data'] ?? [],
        );

        return $this->success($result);
    }

    public function show(int $id)
    {
        $raw = $this->api->getFixture($id);

        if (!$raw) {
            return $this->error('Match not found.', 404);
        }

        $match = FootballApiService::normaliseFixture($raw);
        $match['events'] = array_map(
            fn(array $e) => FootballApiService::normaliseEvent($e),
            $this->api->getFixtureEvents($id),
        );
        $match['lineups'] = array_map(
            fn(array $l) => FootballApiService::normaliseLineup($l),
            $this->api->getFixtureLineups($id),
        );
        $match['statistics'] = $this->api->getFixtureStatistics($id);

        return $this->success($match);
    }

    public function live()
    {
        $fixtures = $this->api->getLiveFixtures();

        $data = array_map(
            fn(array $raw) => FootballApiService::normaliseFixture($raw),
            $fixtures,
        );

        return $this->success($data);
    }
}
