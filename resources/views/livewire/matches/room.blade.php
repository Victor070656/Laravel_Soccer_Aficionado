<div wire:poll.3s class="min-h-screen bg-surface py-6">
    <!-- Stadium Glow Background -->
    <div class="fixed inset-0 pointer-events-none overflow-hidden">
        <div class="absolute top-0 left-1/4 h-96 w-96 rounded-full bg-primary-container/5 blur-3xl"></div>
        <div class="absolute bottom-0 right-1/4 h-96 w-96 rounded-full bg-primary-container/3 blur-3xl"></div>
    </div>

    <div class="relative z-10 mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <!-- Match Header with Heat Meter -->
        <div class="glass-card rounded-xl p-6 mb-6 relative overflow-hidden">
            <!-- Heat Meter Bar -->
            <div class="absolute top-0 left-0 right-0 h-1 flex">
                @php($heatColors = ['CALM' => 'bg-outline-variant', 'LOW' => 'bg-secondary', 'MEDIUM' => 'bg-tertiary-fixed-dim', 'HIGH' => 'bg-primary-container', 'EXTREME' => 'bg-error'])
                <div class="flex-1 {{ $heatColors[$heatLevel] ?? 'bg-outline-variant' }} transition-colors duration-500"></div>
            </div>

            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                <!-- Teams & Score -->
                <div class="flex items-center gap-6">
                    <!-- Home Team -->
                    <div class="text-center flex-1">
                        <div class="w-16 h-16 mx-auto mb-2 rounded-xl bg-surface-container-high p-2">
                            @if($match->home_team['logo'] ?? null)
                                <img loading="lazy" src="{{ $match->home_team['logo'] }}" alt="" class="h-full w-full object-contain">
                            @else
                                <span class="text-3xl">⚽</span>
                            @endif
                        </div>
                        <div class="text-sm font-bold text-on-surface">{{ $match->home_team['name'] }}</div>
                    </div>

                    <!-- Score & Time -->
                    <div class="text-center px-4">
                        <div class="text-4xl sm:text-5xl font-black text-primary-container tabular-nums">
                            {{ $match->score_display }}
                        </div>
                        <div class="mt-1 flex items-center justify-center gap-2">
                            @if($match->status === 'live')
                                <span class="relative flex h-3 w-3">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary-container opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-3 w-3 bg-primary-container"></span>
                                </span>
                            @endif
                            <span class="text-xs font-bold uppercase tracking-wider {{ $match->status === 'live' ? 'text-primary-container' : 'text-on-surface-variant' }}">
                                {{ $match->status === 'live' ? $match->elapsed ?? 'LIVE' : ucfirst(str_replace('_', ' ', $match->status)) }}
                            </span>
                        </div>
                    </div>

                    <!-- Away Team -->
                    <div class="text-center flex-1">
                        <div class="w-16 h-16 mx-auto mb-2 rounded-xl bg-surface-container-high p-2">
                            @if($match->away_team['logo'] ?? null)
                                <img loading="lazy" src="{{ $match->away_team['logo'] }}" alt="" class="h-full w-full object-contain">
                            @else
                                <span class="text-3xl">⚽</span>
                            @endif
                        </div>
                        <div class="text-sm font-bold text-on-surface">{{ $match->away_team['name'] }}</div>
                    </div>
                </div>

                <!-- Heat Level & Momentum -->
                <div class="flex flex-col gap-3 min-w-[200px]">
                    <!-- Heat Meter -->
                    <div>
                        <div class="flex items-center justify-between text-xs mb-1">
                            <span class="text-on-surface-variant">Heat Meter</span>
                            <span class="font-bold {{ $heatLevel === 'EXTREME' ? 'text-error' : ($heatLevel === 'HIGH' ? 'text-primary-container' : 'text-on-surface-variant') }}">
                                {{ $heatLevel }} ({{ $heatCount }})
                            </span>
                        </div>
                        <div class="h-2 bg-surface-container-high rounded-full overflow-hidden">
                            @php($heatPercent = min(100, $heatCount * 3.33))
                            <div class="h-full rounded-full transition-all duration-500
                                @if($heatLevel === 'EXTREME') bg-error
                                @elseif($heatLevel === 'HIGH') bg-primary-container
                                @elseif($heatLevel === 'MEDIUM') bg-tertiary-fixed-dim
                                @elseif($heatLevel === 'LOW') bg-secondary
                                @else bg-outline-variant @endif"
                                 style="width: {{ $heatPercent }}%">
                            </div>
                        </div>
                    </div>

                    <!-- Fan Momentum -->
                    <div>
                        <div class="text-xs text-on-surface-variant mb-1">Fan Momentum</div>
                        <div class="flex h-3 rounded-full overflow-hidden bg-surface-container-high">
                            @php($total = $homeMomentum + $awayMomentum ?: 1)
                            <div class="bg-primary-container/80 transition-all duration-500" style="width: {{ ($homeMomentum / $total) * 100 }}%"
                                 title="{{ $match->home_team['name'] }}: {{ $homeMomentum }}">
                            </div>
                            <div class="bg-secondary/80 transition-all duration-500" style="width: {{ ($awayMomentum / $total) * 100 }}%"
                                 title="{{ $match->away_team['name'] }}: {{ $awayMomentum }}">
                            </div>
                        </div>
                        <div class="flex justify-between text-[10px] text-on-surface-variant mt-1">
                            <span>{{ $homeMomentum }}</span>
                            <span>{{ $awayMomentum }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Emoji Storm Alert -->
        @if(count($recentEmojiStorm) > 0)
            <div class="mb-4 text-center">
                @foreach($recentEmojiStorm as $emoji => $count)
                    @if($count >= 3)
                        <div class="inline-block animate-bounce-subtle mx-1">
                            <span class="text-4xl">{{ $emoji }}</span>
                            <span class="text-xs text-on-surface-variant">x{{ $count }}</span>
                        </div>
                    @endif
                @endforeach
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left: Live Comments Feed -->
            <div class="lg:col-span-2 space-y-4">
                <!-- Comment Input -->
                @if(auth()->check())
                    <div class="glass-card rounded-xl p-4">
                        <form wire:submit="postComment">
                            <textarea wire:model="newComment"
                                      rows="2"
                                      placeholder="Share your reaction... Use emojis! ⚽🔥💚"
                                      class="w-full rounded-lg bg-surface-container-high border border-outline-variant/40 text-on-surface placeholder-on-surface-variant/50 p-3 text-sm focus:border-primary-container focus:ring-1 focus:ring-primary-container/20 resize-none"></textarea>
                            <div class="mt-3 flex items-center justify-between">
                                <span class="text-xs text-on-surface-variant">{{ strlen($newComment) }}/280</span>
                                <button type="submit"
                                        class="rounded-xl bg-primary-container px-5 py-2 text-sm font-semibold text-on-primary-container hover:bg-primary-container/90 transition-all disabled:opacity-50"
                                        disabled="{{ strlen($newComment) < 1 }}">
                                    Post →
                                </button>
                            </div>
                        </form>
                    </div>
                @endif

                <!-- Emoji Reaction Buttons -->
                <div class="glass-card rounded-xl p-4">
                    <div class="text-xs text-on-surface-variant mb-3 flex items-center gap-2">
                        <span>⚡ Quick Reactions</span>
                        <span class="badge-live !py-0 !px-2">LIVE</span>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        @foreach($availableEmojis as $emoji => $label)
                            <button wire:click="react('{{ $emoji }}')"
                                    class="group flex items-center gap-1.5 px-3 py-2 rounded-lg bg-surface-container-high hover:bg-surface-container transition-all hover:scale-110 active:scale-95"
                                    title="{{ $label }}">
                                <span class="text-xl group-hover:animate-bounce-subtle">{{ $emoji }}</span>
                                <span class="text-xs text-on-surface-variant">{{ $label }}</span>
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- Live Comments Feed -->
                <div class="space-y-3" id="comments-feed">
                    @forelse($comments as $comment)
                        <div class="glass-card rounded-xl p-4 hover:bg-surface-container/50 transition-colors">
                            <div class="flex items-start gap-3">
                                <div class="flex-shrink-0">
                                    @if($comment->user->avatar_url)
                                        <img src="{{ $comment->user->avatar_url }}"
                                             alt="{{ $comment->user->name }}"
                                             class="h-8 w-8 rounded-lg object-cover">
                                    @else
                                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-surface-container-high text-xs font-bold text-on-surface">
                                            {{ substr($comment->user->name, 0, 1) }}
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2">
                                        <span class="text-sm font-bold text-on-surface">{{ $comment->user->name }}</span>
                                        @if($comment->user->favoriteClubs->where('pivot.is_primary', true)->first() ?? $comment->user->favoriteClubs->first())
                                            @php($club = $comment->user->favoriteClubs->where('pivot.is_primary', true)->first() ?? $comment->user->favoriteClubs->first())
                                            <span class="text-xs px-1.5 py-0.5 rounded bg-primary-container/20 text-primary-container">
                                                {{ $club->short_name ?? substr($club->name, 0, 3) }}
                                            </span>
                                        @endif
                                        <span class="text-xs text-on-surface-variant">{{ $comment->created_at->diffForHumans() }}</span>
                                    </div>
                                    <p class="mt-1 text-sm text-on-surface leading-relaxed">{{ $comment->content }}</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="glass-card rounded-xl p-8 text-center">
                            <div class="text-4xl mb-3">💬</div>
                            <p class="text-on-surface-variant">No comments yet. Be the first to react!</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Right: Match Info Sidebar -->
            <div class="space-y-4">
                <!-- League Info -->
                <div class="glass-card rounded-xl p-4">
                    <h3 class="text-label-bold text-on-surface mb-3 flex items-center gap-2">
                        <span>🏆</span> {{ $match->league['name'] ?? 'League' }}
                    </h3>
                    @if($match->league['logo'] ?? null)
                        <img src="{{ $match->league['logo'] }}" alt="" class="h-8 object-contain">
                    @endif
                    <div class="mt-2 text-xs text-on-surface-variant">
                        {{ $match->venue ?? 'Venue TBA' }}
                    </div>
                </div>

                <!-- Live Events Ticker -->
                @if($events->count())
                    <div class="glass-card rounded-xl p-4">
                        <h3 class="text-label-bold text-on-surface mb-3 flex items-center gap-2">
                            <span>⚡</span> Match Events
                        </h3>
                        <div class="space-y-2 max-h-64 overflow-y-auto custom-scrollbar">
                            @foreach($events->sortByDesc('time') as $event)
                                <div class="flex items-center gap-2 p-2 rounded-lg bg-surface-container/30 text-sm">
                                    <span class="text-xs font-mono text-on-surface-variant w-10">{{ $event->time }}'</span>
                                    <span>{{ $event->icon }}</span>
                                    <span class="text-on-surface truncate">{{ $event->player['name'] ?? 'Unknown' }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Recent Reactions -->
                @if($reactions->count())
                    <div class="glass-card rounded-xl p-4">
                        <h3 class="text-label-bold text-on-surface mb-3 flex items-center gap-2">
                            <span>🔥</span> Live Reactions
                        </h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach($reactions->groupBy('emoji') as $emoji => $group)
                                <div class="flex items-center gap-1 px-2 py-1 rounded-lg bg-surface-container/50">
                                    <span class="text-lg">{{ $emoji }}</span>
                                    <span class="text-xs text-on-surface-variant">{{ $group->count() }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Quick Stats -->
                <div class="glass-card rounded-xl p-4">
                    <h3 class="text-label-bold text-on-surface mb-3">Quick Stats</h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-on-surface-variant">Total Comments</span>
                            <span class="text-on-surface font-bold">{{ $comments->count() }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-on-surface-variant">Total Reactions</span>
                            <span class="text-on-surface font-bold">{{ $reactions->count() }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-on-surface-variant">Heat Level</span>
                            <span class="font-bold
                                @if($heatLevel === 'EXTREME') text-error
                                @elseif($heatLevel === 'HIGH') text-primary-container
                                @else text-on-surface-variant @endif">
                                {{ $heatLevel }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Emoji Storm Animation Overlay -->
@if(count($recentEmojiStorm) > 0)
    <div class="fixed inset-0 pointer-events-none z-50 overflow-hidden">
        @foreach($recentEmojiStorm as $emoji => $count)
            @if($count >= 5)
                @for($i = 0; $i < min(10, $count); $i++)
                    <div class="absolute animate-float text-4xl opacity-50"
                         style="left: {{ rand(10, 90) }}%; top: {{ rand(10, 90) }}%; animation-delay: {{ $i * 0.2 }}s;">
                        {{ $emoji }}
                    </div>
                @endfor
            @endif
        @endforeach
    </div>
@endif>
