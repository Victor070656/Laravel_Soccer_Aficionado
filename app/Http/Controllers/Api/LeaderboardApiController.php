<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Services\GamificationService;
use Illuminate\Http\Request;

class LeaderboardApiController extends BaseApiController
{
    public function __construct(
        protected GamificationService $gamification,
    ) {
    }

    public function __invoke(Request $request)
    {
        $leaders = $this->gamification->getLeaderboard(50);

        $data = [
            'leaders' => $leaders,
        ];

        // Include current user rank if authenticated
        if ($request->user()) {
            $currentUser = $request->user();
            $data['current_user'] = [
                'id' => $currentUser->id,
                'name' => $currentUser->name,
                'username' => $currentUser->username,
                'points' => $currentUser->points,
                'rank' => User::where('points', '>', $currentUser->points)->count() + 1,
            ];
        }

        return $this->success($data);
    }
}
