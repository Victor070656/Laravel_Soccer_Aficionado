<?php

namespace App\Livewire\Communities;

use App\Models\Club;
use App\Models\Community;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class Index extends Component
{
    use AuthorizesRequests;
    use WithFileUploads;

    public string $locationFilter = 'all'; // all, global, continent, country, state

    public string $selectedCountry = '';

    public string $selectedState = '';

    public string $search = '';

    public string $name = '';

    public string $description = '';

    public string $rules = '';

    public ?int $clubId = null;

    public $banner = null;

    public function render()
    {
        $communities = Community::with(['club', 'members', 'posts'])
            ->where('is_active', true)
            ->when($this->search, fn ($q) => $q->where('name', 'LIKE', "%{$this->search}%"))
            ->when($this->locationFilter === 'global', fn ($q) => $q->whereNull('country'))
            ->when($this->locationFilter === 'country', fn ($q) => $q->whereNotNull('country')->whereNull('state'))
            ->when($this->locationFilter === 'state', fn ($q) => $q->whereNotNull('state'))
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

    public function save(): void
    {
        $this->authorize('create', Community::class);

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:2000'],
            'rules' => ['nullable', 'string', 'max:5000'],
            'clubId' => ['nullable', 'exists:clubs,id'],
            'banner' => ['nullable', 'image', 'max:4096'],
        ]);

        $community = Community::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'description' => $validated['description'],
            'rules' => $validated['rules'] ?? null,
            'club_id' => $validated['clubId'] ?? null,
            'created_by' => auth()->id(),
            'banner' => $this->banner ? $this->banner->store('communities/banners', 'public') : null,
        ]);

        $community->members()->attach(auth()->id(), ['role' => 'moderator']);
        $community->increment('members_count');

        $this->reset(['name', 'description', 'rules', 'clubId', 'banner']);

        $this->redirectRoute('communities.show', $community);
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
