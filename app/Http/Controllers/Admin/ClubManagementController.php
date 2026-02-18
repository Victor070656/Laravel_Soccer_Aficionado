<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Club;
use App\Services\FootballApiService;
use Illuminate\Http\Request;

/**
 * Admin management for clubs sourced from TheSportsDB API.
 *
 * Clubs are NOT created manually – they are synced from the API.
 * Admins can: browse API teams, sync them into local records, and view/manage local state.
 */
class ClubManagementController extends Controller
{
    public function __construct(
        protected FootballApiService $api,
    ) {
    }

    /**
     * Browse all clubs from the API, showing sync status.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $country = $request->input('country');

        $apiTeams = collect($this->api->getAllTeams(search: $search, country: $country))
            ->map(fn(array $raw) => (object) FootballApiService::normaliseTeam($raw));

        // Get local club records to show sync status
        $localClubApiIds = Club::pluck('api_team_id')->filter()->toArray();

        $leagues = collect($this->api->getLeagues())
            ->map(fn(array $l) => (object) $l);

        return view('admin.clubs.index', compact('apiTeams', 'localClubApiIds', 'leagues'));
    }

    /**
     * Show a single club detail from the API.
     */
    public function show(int $id)
    {
        $raw = $this->api->getTeam($id);

        if (!$raw) {
            abort(404, 'Club not found.');
        }

        $club = (object) FootballApiService::normaliseTeam($raw);

        // Squad from API
        $squad = collect($this->api->getTeamSquad($id))
            ->map(fn(array $p) => (object) FootballApiService::normaliseSquadPlayer($p));

        // Check local sync status
        $localClub = Club::where('api_team_id', $id)->first();

        return view('admin.clubs.show', compact('club', 'squad', 'localClub'));
    }

    /**
     * Sync a single club from the API into the local database.
     */
    public function syncClub(Request $request, int $id)
    {
        $raw = $this->api->getTeam($id);

        if (!$raw) {
            return back()->with('error', 'Could not fetch club from API.');
        }

        $team = FootballApiService::normaliseTeam($raw);
        Club::fromApiTeam($team);

        return back()->with('success', "{$team['name']} synced to local database.");
    }

    /**
     * Sync all clubs from a specific league.
     */
    public function syncLeague(Request $request)
    {
        $leagueId = $request->input('league_id');

        if (!$leagueId) {
            return back()->with('error', 'Please select a league.');
        }

        $teams = $this->api->getTeamsByLeague((int) $leagueId);
        $synced = 0;

        foreach ($teams as $raw) {
            $team = FootballApiService::normaliseTeam($raw);
            Club::fromApiTeam($team);
            $synced++;
        }

        return back()->with('success', "Synced {$synced} clubs from the API.");
    }

    /**
     * Toggle active status for a local club record.
     */
    public function toggleActive(Club $club)
    {
        $club->update(['is_active' => !$club->is_active]);

        return back()->with('success', "{$club->name} " . ($club->is_active ? 'activated' : 'deactivated') . '.');
    }

    /**
     * Remove a local club record (does not affect API data).
     */
    public function destroy(Club $club)
    {
        $name = $club->name;
        $club->delete();

        return redirect()->route('admin.clubs.index')->with('success', "{$name} removed from local database.");
    }
}
