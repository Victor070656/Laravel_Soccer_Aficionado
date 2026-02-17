<?php

use App\Models\Club;
use App\Services\FootballApiService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component {
    public string $search = '';

    /** @var array<int, array{api_team_id: int, name: string, logo: ?string, country: ?string}> */
    public array $selectedTeams = [];
    public ?int $primaryApiTeamId = null;

    public function mount(): void
    {
        $user = Auth::user();
        $favorites = $user->favoriteClubs;

        foreach ($favorites as $club) {
            if ($club->api_team_id) {
                $this->selectedTeams[$club->api_team_id] = [
                    'api_team_id' => $club->api_team_id,
                    'name' => $club->name,
                    'logo' => $club->logo,
                    'country' => $club->country,
                ];
            }
        }

        $primary = $favorites->where('pivot.is_primary', true)->first();
        $this->primaryApiTeamId = $primary?->api_team_id;
    }

    #[Computed]
    public function availableTeams(): array
    {
        if (strlen($this->search) < 2) {
            return [];
        }

        $api = app(FootballApiService::class);
        $rawTeams = $api->getAllTeams(search: $this->search);

        return array_map(
            fn(array $raw) => FootballApiService::normaliseTeam($raw),
            array_slice($rawTeams, 0, 30)
        );
    }

    public function toggleTeam(int $apiTeamId, string $name, ?string $logo, ?string $country): void
    {
        if (isset($this->selectedTeams[$apiTeamId])) {
            unset($this->selectedTeams[$apiTeamId]);
            if ($this->primaryApiTeamId === $apiTeamId) {
                $this->primaryApiTeamId = array_key_first($this->selectedTeams);
            }
        } else {
            $this->selectedTeams[$apiTeamId] = [
                'api_team_id' => $apiTeamId,
                'name' => $name,
                'logo' => $logo,
                'country' => $country,
            ];
            if (count($this->selectedTeams) === 1) {
                $this->primaryApiTeamId = $apiTeamId;
            }
        }
    }

    public function setPrimary(int $apiTeamId): void
    {
        if (isset($this->selectedTeams[$apiTeamId])) {
            $this->primaryApiTeamId = $apiTeamId;
        }
    }

    public function save(): void
    {
        $user = Auth::user();

        // Upsert local Club records from API data and build sync array
        $syncData = [];
        foreach ($this->selectedTeams as $team) {
            $club = Club::fromApiTeam([
                'id' => $team['api_team_id'],
                'name' => $team['name'],
                'logo' => $team['logo'],
                'country' => $team['country'],
                'venue' => ['city' => null, 'name' => null],
                'founded' => null,
            ]);

            $syncData[$club->id] = [
                'is_primary' => $team['api_team_id'] === $this->primaryApiTeamId,
            ];
        }

        $user->favoriteClubs()->sync($syncData);

        $this->dispatch('clubs-updated');
    }
}; ?>

<section class="w-full">
    @include('partials.settings-heading')

    <flux:heading class="sr-only">{{ __('Favorite Clubs') }}</flux:heading>

    <x-pages::settings.layout :heading="__('Favorite Clubs')" :subheading="__('Search and select your favorite football clubs from real leagues')">
        <div class="my-6 w-full space-y-6">
            {{-- Selected Clubs --}}
            @if (count($selectedTeams) > 0)
                <div>
                    <flux:label class="mb-2">{{ __('Your Clubs') }}</flux:label>
                    <div class="space-y-2">
                        @foreach ($selectedTeams as $apiTeamId => $team)
                            <div class="flex items-center justify-between rounded-lg border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-3">
                                <div class="flex items-center gap-3">
                                    @if ($team['logo'])
                                        <img src="{{ $team['logo'] }}" alt="{{ $team['name'] }}" class="w-8 h-8 object-contain">
                                    @else
                                        <div class="w-8 h-8 rounded-full bg-green-500/20 flex items-center justify-center text-xs font-bold text-green-700 dark:text-green-400">
                                            {{ substr($team['name'], 0, 2) }}
                                        </div>
                                    @endif
                                    <div>
                                        <span class="text-sm font-medium text-zinc-900 dark:text-zinc-100">{{ $team['name'] }}</span>
                                        @if ($team['country'])
                                            <span class="text-xs text-zinc-500 ml-1">{{ $team['country'] }}</span>
                                        @endif
                                        @if ($apiTeamId === $primaryApiTeamId)
                                            <span class="ml-2 inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-400">Primary</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    @if ($apiTeamId !== $primaryApiTeamId)
                                        <button type="button" wire:click="setPrimary({{ $apiTeamId }})" class="text-xs text-blue-500 hover:text-blue-700">Set Primary</button>
                                    @endif
                                    <button type="button" wire:click="toggleTeam({{ $apiTeamId }}, '{{ addslashes($team['name']) }}', '{{ $team['logo'] }}', '{{ $team['country'] }}')" class="text-xs text-red-500 hover:text-red-700">Remove</button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Search and Add Clubs --}}
            <div>
                <flux:label class="mb-2">{{ __('Search Clubs') }}</flux:label>
                <input type="text" wire:model.live.debounce.500ms="search" placeholder="{{ __('Type at least 2 characters to search...') }}"
                    class="w-full rounded-lg border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 px-3 py-2 text-sm text-zinc-900 dark:text-zinc-100 focus:border-green-500 focus:ring-green-500 mb-3" />

                <div wire:loading wire:target="search" class="text-sm text-zinc-500 py-3 text-center">
                    Searching...
                </div>

                @if (strlen($search) >= 2)
                <div wire:loading.remove wire:target="search" class="max-h-64 overflow-y-auto rounded-lg border border-zinc-200 dark:border-zinc-700 divide-y divide-zinc-100 dark:divide-zinc-700/50">
                    @forelse ($this->availableTeams as $team)
                        <button type="button"
                            wire:click="toggleTeam({{ $team['id'] }}, '{{ addslashes($team['name']) }}', '{{ $team['logo'] }}', '{{ $team['country'] }}')"
                            class="w-full flex items-center gap-3 p-3 text-left hover:bg-zinc-50 dark:hover:bg-zinc-700/50 transition-colors {{ isset($selectedTeams[$team['id']]) ? 'bg-green-50 dark:bg-green-900/20' : '' }}">
                            @if ($team['logo'])
                                <img src="{{ $team['logo'] }}" alt="{{ $team['name'] }}" class="w-8 h-8 object-contain">
                            @else
                                <div class="w-8 h-8 rounded-full bg-green-500/20 flex items-center justify-center text-xs font-bold text-green-700 dark:text-green-400">
                                    {{ substr($team['name'], 0, 2) }}
                                </div>
                            @endif
                            <div class="flex-1">
                                <span class="text-sm font-medium text-zinc-900 dark:text-zinc-100">{{ $team['name'] }}</span>
                                @if ($team['country'])
                                    <span class="text-xs text-zinc-500 ml-1">{{ $team['country'] }}</span>
                                @endif
                            </div>
                            @if (isset($selectedTeams[$team['id']]))
                                <span class="text-green-500 text-lg">✓</span>
                            @endif
                        </button>
                    @empty
                        <div class="p-4 text-center text-sm text-zinc-500">
                            {{ __('No clubs found matching your search.') }}
                        </div>
                    @endforelse
                </div>
                @elseif (strlen($search) > 0)
                    <p class="text-xs text-zinc-400">{{ __('Type at least 2 characters to search.') }}</p>
                @endif
            </div>

            {{-- Save Button --}}
            <div class="flex items-center gap-4">
                <flux:button variant="primary" wire:click="save" class="!bg-gradient-to-r !from-green-600 !to-emerald-600 hover:!from-green-500 hover:!to-emerald-500 !shadow-lg !shadow-green-500/25">
                    {{ __('Save Favorites') }}
                </flux:button>

                <x-action-message class="me-3" on="clubs-updated">
                    {{ __('Saved.') }}
                </x-action-message>
            </div>
        </div>
    </x-pages::settings.layout>
</section>
