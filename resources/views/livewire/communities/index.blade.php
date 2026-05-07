<div wire:poll.60s class="min-h-screen bg-surface py-6">
    <!-- Stadium Glow Background -->
    <div class="fixed inset-0 pointer-events-none overflow-hidden">
        <div class="absolute top-0 left-1/4 h-96 w-96 rounded-full bg-primary-container/5 blur-3xl"></div>
        <div class="absolute bottom-0 right-1/4 h-96 w-96 rounded-full bg-secondary/5 blur-3xl"></div>
    </div>

    <div class="relative z-10 mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="glass-card rounded-xl p-6 mb-6 relative overflow-hidden">
            <div class="absolute -top-20 -right-20 h-40 w-40 rounded-full bg-primary-container/10 blur-3xl"></div>
            <div class="relative z-10">
                <h1 class="text-headline-lg text-on-surface flex items-center gap-3">
                    <span class="w-12 h-12 rounded-xl bg-primary-container/20 flex items-center justify-center text-2xl">👥</span>
                    Club Communities
                </h1>
                <p class="mt-2 text-body-md text-on-surface-variant">Join fan communities organized by club, country, and region.</p>
            </div>
        </div>

        <!-- Location Filter Tabs -->
        <div class="glass-card rounded-xl p-4 mb-6">
            <div class="flex flex-wrap gap-2">
                <button wire:click="setLocationFilter('all')"
                        class="px-4 py-2 rounded-lg text-sm font-medium transition-all
                            {{ $locationFilter === 'all'
                                ? 'bg-primary-container text-on-primary-container scale-105'
                                : 'bg-surface-container-high text-on-surface-variant hover:bg-surface-container' }}">
                    🌍 All Communities
                </button>
                <button wire:click="setLocationFilter('global')"
                        class="px-4 py-2 rounded-lg text-sm font-medium transition-all
                            {{ $locationFilter === 'global'
                                ? 'bg-primary-container text-on-primary-container scale-105'
                                : 'bg-surface-container-high text-on-surface-variant hover:bg-surface-container' }}">
                    🌐 Global
                </button>
                <button wire:click="setLocationFilter('country')"
                        class="px-4 py-2 rounded-lg text-sm font-medium transition-all
                            {{ $locationFilter === 'country'
                                ? 'bg-primary-container text-on-primary-container scale-105'
                                : 'bg-surface-container-high text-on-surface-variant hover:bg-surface-container' }}">
                    🏳️ By Country
                </button>
                <button wire:click="setLocationFilter('state')"
                        class="px-4 py-2 rounded-lg text-sm font-medium transition-all
                            {{ $locationFilter === 'state'
                                ? 'bg-primary-container text-on-primary-container scale-105'
                                : 'bg-surface-container-high text-on-surface-variant hover:bg-surface-container' }}">
                    📍 By State/Region
                </button>
            </div>

            <!-- Search -->
            <div class="mt-4">
                <input wire:model.live="search"
                       type="text"
                       placeholder="Search communities..."
                       class="w-full rounded-lg bg-surface-container-high border border-outline-variant/40 text-on-surface placeholder-on-surface-variant/50 p-3 text-body-md focus:border-primary-container focus:ring-1 focus:ring-primary-container/20">
            </div>
        </div>

        <!-- Communities Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($communities as $community)
                <a href="{{ route('communities.show', $community) }}"
                   class="glass-card rounded-xl p-5 hover:bg-surface-container/50 transition-all hover:scale-[1.02] group">
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0">
                            @if($community->avatar_url)
                                <img src="{{ $community->avatar_url }}"
                                     alt="{{ $community->name }}"
                                     class="h-12 w-12 rounded-lg object-cover">
                            @else
                                <div class="h-12 w-12 rounded-lg bg-surface-container-high flex items-center justify-center text-xs font-bold text-on-surface">
                                    {{ substr($community->name, 0, 2) }}
                                </div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="text-label-bold text-on-surface group-hover:text-primary-container transition-colors">
                                {{ $community->name }}
                            </h3>
                            @if($community->club)
                                <p class="text-label-sm text-primary-container mt-1">{{ $community->club->name }}</p>
                            @endif
                            @if($community->country)
                                <p class="text-label-sm text-on-surface-variant mt-1">
                                    📍 {{ $community->country }}@if($community->state), {{ $community->state }}@endif
                                </p>
                            @endif
                        </div>
                    </div>
                    <div class="mt-4 flex items-center gap-4 text-label-sm text-on-surface-variant">
                        <span class="flex items-center gap-1">👥 {{ $community->members_count }} members</span>
                        <span class="flex items-center gap-1">📰 {{ $community->posts_count }} posts</span>
                    </div>
                </a>
            @empty
                <div class="col-span-full glass-card rounded-xl p-8 text-center">
                    <div class="text-4xl mb-4">👥</div>
                    <h3 class="text-headline-md text-on-surface mb-2">No communities found</h3>
                    <p class="text-body-md text-on-surface-variant">Try adjusting your filters or create a new community!</p>
                </div>
            @endforelse
        </div>

        <!-- Club List Sidebar (for creating communities) -->
        @if(auth()->check())
            <div class="mt-6 glass-card rounded-xl p-5">
                <h3 class="text-label-bold text-on-surface mb-3">Popular Clubs</h3>
                <div class="flex flex-wrap gap-2">
                    @foreach($clubs as $club)
                        <a href="{{ route('clubs.show', $club->api_team_id ?? $club->id) }}"
                           class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-surface-container/30 hover:bg-surface-container/50 transition-colors">
                            @if($club->logo_url)
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
