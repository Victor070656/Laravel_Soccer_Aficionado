<?php

namespace App\Livewire\Communities;

use App\Models\Club;
use App\Models\Community;
use Livewire\Component;

class Index extends Component
{
    public string $locationFilter = 'all'; // all, global, continent, country, state

    public string $selectedCountry = '';

    public string $selectedState = '';

    public string $search = '';

    public function render()
    {
        $communities = Community::with(['club', 'members', 'posts'])
            ->where('is_active', true)
            ->when($this->search, fn ($q) => $q->where('name', 'LIKE', "%{$this->search}%"))
            ->when($this->selectedCountry, fn ($q) => $q->where('country', $this->selectedCountry))
            ->when($this->selectedState, fn ($q) => $q->where('state', $this->selectedState))
            ->withCount(['members', 'posts'])
            ->orderByDesc('members_count')
            ->get();

        // Group by location hierarchy
        $grouped = $communities->groupBy(function ($community) {
            if ($community->country && $community->state) {
                return "state_{$community->country}_{$community->state}";
            }
            if ($community->country) {
                return "country_{$community->country}";
            }

            return 'global';
        });

        $clubs = Club::withCount('communities')->orderByDesc('communities_count')->take(20)->get();

        return view('livewire.communities.index', [
            'communities' => $communities,
            'groupedCommunities' => $grouped,
            'clubs' => $clubs,
        ]);
    }

    public function setLocationFilter(string $filter): void
    {
        $this->locationFilter = $filter;
        $this->reset(['selectedCountry', 'selectedState']);
    }

    public function getListeners(): array
    {
        return [
            'echo:communities,CommunityUpdated' => '$refresh',
        ];
    }
}
