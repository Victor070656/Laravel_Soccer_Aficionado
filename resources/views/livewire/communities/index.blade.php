<div wire:poll.60s class="min-h-screen bg-surface py-6">
    <div class="fixed inset-0 pointer-events-none overflow-hidden">
        <div class="absolute top-0 left-1/4 h-96 w-96 rounded-full bg-primary-container/5 blur-3xl"></div>
        <div class="absolute bottom-0 right-1/4 h-96 w-96 rounded-full bg-secondary/5 blur-3xl"></div>
    </div>

    <div class="relative z-10 mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 space-y-6">
        <div class="glass-card rounded-xl p-6 relative overflow-hidden">
            <div class="absolute -top-20 -right-20 h-40 w-40 rounded-full bg-primary-container/10 blur-3xl"></div>
            <div class="relative z-10 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-headline-lg text-on-surface flex items-center gap-3">
                        <span class="w-12 h-12 rounded-xl bg-primary-container/20 flex items-center justify-center text-2xl">👥</span>
                        Club Communities
                    </h1>
                    <p class="mt-2 text-body-md text-on-surface-variant">Join fan communities organized by club, country, and region.</p>
                </div>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-[1.3fr_0.7fr]">
            <div class="glass-card rounded-xl p-6 space-y-5">
                <div class="flex items-center gap-2 border-b border-outline-variant/20 pb-2">
                    <flux:icon icon="plus-circle" variant="mini" class="text-primary-container" />
                    <flux:heading size="sm" class="font-black uppercase tracking-widest text-on-surface-variant">
                        {{ __('Create Community') }}
                    </flux:heading>
                </div>

                <form wire:submit="save" class="grid gap-5 sm:grid-cols-2">
                    <div class="sm:col-span-2">
                        <flux:input wire:model="name" :label="__('Name')" required />
                    </div>

                    <div class="sm:col-span-2">
                        <flux:textarea wire:model="description" :label="__('Description')" rows="4" required />
                    </div>

                    <div>
                        <flux:textarea wire:model="rules" :label="__('Rules')" rows="4" placeholder="Community guidelines..." />
                    </div>

                    <div class="space-y-4">
                        <flux:select wire:model="clubId" :label="__('Link to a Club (optional)')">
                            <option value="">{{ __('No club') }}</option>
                            @foreach ($clubs as $club)
                                <option value="{{ $club->id }}">{{ $club->name }}</option>
                            @endforeach
                        </flux:select>

                        <div>
                            <flux:label>{{ __('Banner Image') }}</flux:label>
                            <input type="file" wire:model="banner" accept="image/*"
                                class="mt-2 block w-full text-sm text-on-surface-variant file:mr-3 file:rounded-lg file:border-0 file:bg-primary/10 file:px-4 file:py-2 file:text-sm file:font-medium file:text-on-primary hover:file:bg-primary/20">
                            @if ($banner)
                                <img src="{{ $banner->temporaryUrl() }}" alt="" class="mt-3 h-28 w-full rounded-xl object-cover">
                            @endif
                        </div>
                    </div>

                    <div class="sm:col-span-2 flex justify-end">
                        <flux:button type="submit" variant="primary">{{ __('Create Community') }}</flux:button>
                    </div>
                </form>
            </div>

            <div class="space-y-6">
                <div class="glass-card rounded-xl p-4">
                    <div class="flex flex-wrap gap-2">
                        <button wire:click="setLocationFilter('all')" class="px-4 py-2 rounded-lg text-sm font-medium transition-all {{ $locationFilter === 'all' ? 'bg-primary-container text-on-primary-container scale-105' : 'bg-surface-container-high text-on-surface-variant hover:bg-surface-container' }}">
                            🌍 All Communities
                        </button>
                        <button wire:click="setLocationFilter('global')" class="px-4 py-2 rounded-lg text-sm font-medium transition-all {{ $locationFilter === 'global' ? 'bg-primary-container text-on-primary-container scale-105' : 'bg-surface-container-high text-on-surface-variant hover:bg-surface-container' }}">
                            🌐 Global
                        </button>
                        <button wire:click="setLocationFilter('country')" class="px-4 py-2 rounded-lg text-sm font-medium transition-all {{ $locationFilter === 'country' ? 'bg-primary-container text-on-primary-container scale-105' : 'bg-surface-container-high text-on-surface-variant hover:bg-surface-container' }}">
                            🏳️ By Country
                        </button>
                        <button wire:click="setLocationFilter('state')" class="px-4 py-2 rounded-lg text-sm font-medium transition-all {{ $locationFilter === 'state' ? 'bg-primary-container text-on-primary-container scale-105' : 'bg-surface-container-high text-on-surface-variant hover:bg-surface-container' }}">
                            📍 By State/Region
                        </button>
                    </div>

                    <div class="mt-4">
                        <input wire:model.live="search" type="text" placeholder="Search communities..."
                            class="w-full rounded-lg bg-surface-container-high border border-outline-variant/40 text-on-surface placeholder-on-surface-variant/50 p-3 text-body-md focus:border-primary-container focus:ring-1 focus:ring-primary-container/20">
                    </div>
                </div>

                @if ($communities->isEmpty())
                    <div class="glass-card rounded-xl p-8 text-center">
                        <div class="text-4xl mb-4">👥</div>
                        <h3 class="text-headline-md text-on-surface mb-2">No communities found</h3>
                        <p class="text-body-md text-on-surface-variant">Try adjusting your filters or create a new community!</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach ($communities as $community)
                            <a href="{{ route('communities.show', $community) }}" wire:navigate
                                class="glass-card rounded-xl p-5 hover:bg-surface-container/50 transition-all hover:scale-[1.02] group">
                                <div class="flex items-start gap-3">
                                    <div class="flex-shrink-0">
                                        @if ($community->avatar_url)
                                            <img src="{{ $community->avatar_url }}" alt="{{ $community->name }}" class="h-12 w-12 rounded-lg object-cover">
                                        @else
                                            <div class="h-12 w-12 rounded-lg bg-surface-container-high flex items-center justify-center text-xs font-bold text-on-surface">
                                                {{ substr($community->name, 0, 2) }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2">
                                            <h3 class="text-label-bold text-on-surface group-hover:text-primary-container transition-colors truncate">
                                                {{ $community->name }}
                                            </h3>
                                            @if ($community->club)
                                                <span class="inline-flex items-center rounded-full bg-primary-container/10 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider text-primary-container">
                                                    {{ $community->club->name }}
                                                </span>
                                            @endif
                                        </div>
                                        <p class="mt-1 text-label-sm text-on-surface-variant line-clamp-2">
                                            {{ $community->description }}
                                        </p>
                                    </div>
                                </div>
                                <div class="mt-4 flex items-center gap-4 text-label-sm text-on-surface-variant">
                                    <span class="flex items-center gap-1">👥 {{ $community->members_count }} members</span>
                                    <span class="flex items-center gap-1">📰 {{ $community->posts_count }} posts</span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        @if(auth()->check())
            <div class="glass-card rounded-xl p-5">
                <h3 class="text-label-bold text-on-surface mb-3">Popular Clubs</h3>
                <div class="flex flex-wrap gap-2">
                    @foreach ($clubs as $club)
                        <a href="{{ route('clubs.show', $club->api_team_id ?? $club->id) }}"
                            class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-surface-container/30 hover:bg-surface-container/50 transition-colors">
                            @if ($club->logo_url)
                                <img src="{{ $club->logo_url }}" alt="" class="h-6 w-6 object-contain">
                            @endif
                            <span class="text-label-sm text-on-surface">{{ $club->name }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
