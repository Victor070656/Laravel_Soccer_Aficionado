<?php

namespace App\Http\Controllers\Api;

use App\Models\Ad;
use Illuminate\Http\Request;

class AdApiController extends BaseApiController
{
    /**
     * Return currently-running ads, optionally filtered by placement.
     *
     * GET /api/v1/ads?placement=feed&limit=3
     */
    public function index(Request $request)
    {
        $query = Ad::running();

        if ($request->filled('placement')) {
            $query->forPlacement($request->input('placement'));
        }

        $limit = min((int) $request->input('limit', 5), 10);

        $ads = $query->inRandomOrder()->limit($limit)->get();

        // Increment view count for each returned ad
        $ads->each(fn (Ad $ad) => $ad->increment('view_count'));

        return $this->success($ads->map(fn (Ad $ad) => [
            'id' => $ad->id,
            'title' => $ad->title,
            'image_url' => $ad->image_url,
            'link_url' => $ad->link_url,
            'placement' => $ad->placement,
        ]));
    }

    /**
     * Track a click on an ad.
     *
     * POST /api/v1/ads/{ad}/click
     */
    public function click(Ad $ad)
    {
        $ad->increment('click_count');

        return $this->success(null, 'Click recorded.');
    }
}
