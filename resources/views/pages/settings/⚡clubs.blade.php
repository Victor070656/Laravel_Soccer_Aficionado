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

    <x-pages::settings.layout :heading="__('Favorite Clubs')" :subheading="__('Build your loyalty. Select your favorite clubs to personalize your experience.')">
        <div class="my-6 w-full space-y-8">
            {{-- Selected Clubs Section --}}
            <div class="space-y-6">
                <div class="flex items-center gap-2 border-b border-zinc-100 dark:border-zinc-800 pb-2">
                    <flux:icon icon="shield-check" variant="mini" class="text-green-600" />
                    <flux:heading size="sm" class="font-black uppercase tracking-widest text-zinc-500">{{ __('Your Allegiance') }}</flux:heading>
                </div>

                @if (count($selectedTeams) > 0)
                    <div class="grid grid-cols-1 gap-3">
                        @foreach ($selectedTeams as $apiTeamId => $team)
                            <div class="flex items-center justify-between rounded-2xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-4 shadow-sm group hover:border-green-500/30 transition-all">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-xl bg-zinc-50 dark:bg-zinc-900 flex items-center justify-center p-2 border border-zinc-100 dark:border-zinc-800 shadow-inner group-hover:scale-105 transition-transform">
                                        @if ($team['logo'])
                                            <img src="{{ $team['logo'] }}" alt="{{ $team['name'] }}" class="w-full h-full object-contain">
                                        @else
                                            <span class="text-xs font-black text-zinc-400 uppercase">{{ substr($team['name'], 0, 2) }}</span>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <span class="font-black text-zinc-900 dark:text-zinc-100">{{ $team['name'] }}</span>
                                            @if ($apiTeamId === $primaryApiTeamId)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-green-500 text-white shadow-sm shadow-green-500/20">Primary</span>
                                            @endif
                                        </div>
                                        @if ($team['country'])
                                            <span class="text-xs font-bold text-zinc-500 uppercase tracking-tighter">{{ $team['country'] }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                    @if ($apiTeamId !== $primaryApiTeamId)
                                        <button type="button" wire:click="setPrimary({{ $apiTeamId }})" class="text-[10px] font-black uppercase tracking-widest text-blue-500 hover:text-blue-700 transition-colors">{{ __('Set Primary') }}</button>
                                    @endif
                                    <button type="button" wire:click="toggleTeam({{ $apiTeamId }}, '{{ addslashes($team['name']) }}', '{{ $team['logo'] }}', '{{ $team['country'] }}')" class="p-2 rounded-lg text-zinc-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition-all">
                                        <flux:icon icon="trash" variant="mini" />
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="rounded-2xl border-2 border-dashed border-zinc-200 dark:border-zinc-800 p-8 text-center">
                        <div class="text-4xl mb-3 opacity-20">⚽</div>
                        <p class="text-sm font-bold text-zinc-500">{{ __('No clubs selected yet.') }}</p>
                        <p class="text-xs text-zinc-400 mt-1">{{ __('Search below to add your favorite teams.') }}</p>
                    </div>
                @endif
            </div>

            {{-- Search Section --}}
            <div class="space-y-6">
                <div class="flex items-center gap-2 border-b border-zinc-100 dark:border-zinc-800 pb-2">
                    <flux:icon icon="magnifying-glass" variant="mini" class="text-green-600" />
                    <flux:heading size="sm" class="font-black uppercase tracking-widest text-zinc-500">{{ __('Discover Clubs') }}</flux:heading>
                </div>

                <div class="relative">
                    <flux:input wire:model.live.debounce.500ms="search" placeholder="{{ __('Search by club name...') }}" class="pl-10" />
                    <div class="absolute left-3 top-1/2 -translate-y-1/2">
                        <flux:icon icon="magnifying-glass" variant="mini" class="text-zinc-400" />
                    </div>
                </div>

                <div wire:loading wire:target="search" class="w-full flex justify-center py-6">
                    <flux:spacer />
                    <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-green-600"></div>
                    <flux:spacer />
                </div>

                @if (strlen($search) >= 2)
                    <div wire:loading.remove wire:target="search" class="max-h-80 overflow-y-auto rounded-2xl border border-zinc-100 dark:border-zinc-800 divide-y divide-zinc-50 dark:divide-zinc-800/50 shadow-inner bg-zinc-50/30 dark:bg-black/10">
                        @forelse ($this->availableTeams as $team)
                            <button type="button"
                                wire:click="toggleTeam({{ $team['id'] }}, '{{ addslashes($team['name']) }}', '{{ $team['logo'] }}', '{{ $team['country'] }}')"
                                class="w-full flex items-center gap-4 p-4 text-left hover:bg-white dark:hover:bg-zinc-800 transition-all group {{ isset($selectedTeams[$team['id']]) ? 'bg-green-50/50 dark:bg-green-900/10' : '' }}">
                                <div class="w-10 h-10 rounded-lg bg-white dark:bg-zinc-900 flex items-center justify-center p-1.5 border border-zinc-200 dark:border-zinc-700 shadow-sm group-hover:scale-110 transition-transform">
                                    @if ($team['logo'])
                                        <img src="{{ $team['logo'] }}" alt="{{ $team['name'] }}" class="w-full h-full object-contain">
                                    @else
                                        <span class="text-[10px] font-black text-zinc-400 uppercase">{{ substr($team['name'], 0, 2) }}</span>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <div class="font-bold text-zinc-900 dark:text-zinc-100">{{ $team['name'] }}</div>
                                    @if ($team['country'])
                                        <div class="text-[10px] font-black text-zinc-500 uppercase tracking-tighter">{{ $team['country'] }}</div>
                                    @endif
                                </div>
                                @if (isset($selectedTeams[$team['id']]))
                                    <div class="w-6 h-6 rounded-full bg-green-500 flex items-center justify-center shadow-lg shadow-green-500/30">
                                        <flux:icon icon="check" variant="mini" class="text-white h-4 w-4" />
                                    </div>
                                @else
                                    <div class="w-6 h-6 rounded-full border-2 border-zinc-200 dark:border-zinc-700 group-hover:border-green-500/50 transition-colors"></div>
                                @endif
                            </button>
                        @empty
                            <div class="p-8 text-center text-sm font-bold text-zinc-500">
                                {{ __('No clubs found matching your search.') }}
                            </div>
                        @endforelse
                    </div>
                @elseif (strlen($search) > 0)
                    <p class="text-xs font-bold text-zinc-400 uppercase tracking-widest text-center">{{ __('Type at least 2 characters to search.') }}</p>
                @endif
            </div>

            {{-- Save Button --}}
            <div class="flex items-center justify-between pt-6 border-t border-zinc-100 dark:border-zinc-800">
                <div class="flex items-center gap-4">
                    <flux:button variant="primary" wire:click="save" class="!bg-gradient-to-r !from-green-600 !to-emerald-600 hover:!from-green-500 hover:!to-emerald-500 !shadow-lg !shadow-green-500/25 px-8">
                        {{ __('Save Allegiances') }}
                    </flux:button>

                    <x-action-message on="clubs-updated">
                        <span class="text-green-600 font-bold text-sm flex items-center gap-1">
                            <flux:icon icon="check-circle" variant="mini" />
                            {{ __('Saved successfully.') }}
                        </span>
                    </x-action-message>
                </div>
            </div>
        </div>
    </x-pages::settings.layout>
</section>
