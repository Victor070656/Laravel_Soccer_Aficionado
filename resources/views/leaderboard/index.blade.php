<x-layouts::app :title="__('Leaderboard')">
    <div class="min-h-screen bg-surface py-6">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="glass-card rounded-xl p-6 relative overflow-hidden">
                <div class="absolute -top-20 -right-20 h-40 w-40 rounded-full bg-tertiary/10 blur-3xl"></div>
                <div class="relative z-10 text-center">
                    <h1 class="text-headline-lg text-on-surface flex items-center justify-center gap-3">
                        <span class="w-12 h-12 rounded-xl bg-tertiary/20 flex items-center justify-center text-2xl">🏆</span>
                        Leaderboard
                    </h1>
                    <p class="mt-2 text-body-md text-on-surface-variant">Top fans ranked by points. Earn points by engaging with the community.</p>
                </div>
            </div>

            @if($leaders->count() >= 3)
                <div class="grid grid-cols-3 gap-4">
                    @foreach([1,0,2] as $position)
                        @php $leader = $leaders[$position]; @endphp
                        <div class="glass-card rounded-xl p-4 text-center {{ $position === 0 ? 'order-first' : '' }}">
                            <div class="mx-auto mb-3 h-14 w-14 rounded-full overflow-hidden bg-surface-container-high flex items-center justify-center">
                                @if($leader->avatar)
                                    <img src="{{ $leader->avatar_url }}" alt="{{ $leader->name }}" class="h-full w-full object-cover">
                                @else
                                    <span class="font-black text-on-surface">{{ strtoupper(substr($leader->name, 0, 1)) }}</span>
                                @endif
                            </div>
                            <div class="text-2xl">{{ $position === 0 ? '🥇' : ($position === 1 ? '🥈' : '🥉') }}</div>
                            <a href="{{ route('profiles.show', $leader) }}" class="mt-2 block font-bold text-on-surface hover:text-primary-container">{{ $leader->name }}</a>
                            <div class="text-sm font-bold text-primary-container">{{ number_format($leader->points) }} pts</div>
                        </div>
                    @endforeach
                </div>
            @endif

            @if($currentRank)
                <div class="glass-card rounded-xl p-5">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-sm font-semibold text-on-surface">Your Ranking</div>
                            <div class="text-xs text-on-surface-variant">Keep engaging to climb higher.</div>
                        </div>
                        <div class="text-xl font-black text-primary-container tabular-nums">#{{ $currentRank }}</div>
                    </div>
                </div>
            @endif

            <div class="glass-card rounded-xl overflow-hidden">
                <div class="border-b border-outline-variant/20 px-5 py-4">
                    <h3 class="font-bold text-on-surface">Full Rankings</h3>
                </div>
                <table class="w-full">
                    <thead class="bg-surface-container-high/60 text-xs uppercase tracking-wider text-on-surface-variant">
                        <tr>
                            <th class="px-5 py-3 text-left w-16">Rank</th>
                            <th class="px-5 py-3 text-left">User</th>
                            <th class="px-5 py-3 text-right">Points</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant/20">
                        @foreach($leaders as $index => $leader)
                            <tr class="{{ auth()->id() === $leader->id ? 'bg-primary-container/10' : '' }} hover:bg-surface-container-high/40 transition-colors">
                                <td class="px-5 py-4 font-bold text-on-surface-variant">
                                    {{ $index < 3 ? ['🥇','🥈','🥉'][$index] : '#'.($index + 1) }}
                                </td>
                                <td class="px-5 py-4">
                                    <a href="{{ route('profiles.show', $leader) }}" class="flex items-center gap-3">
                                        <div class="h-10 w-10 rounded-full overflow-hidden bg-surface-container-high flex items-center justify-center">
                                            @if($leader->avatar)
                                                <img src="{{ $leader->avatar_url }}" alt="{{ $leader->name }}" class="h-full w-full object-cover">
                                            @else
                                                <span class="font-black text-on-surface">{{ strtoupper(substr($leader->name, 0, 1)) }}</span>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="font-semibold text-on-surface">{{ $leader->name }}</div>
                                            <div class="text-xs text-on-surface-variant">{{ '@'.$leader->username }}</div>
                                        </div>
                                    </a>
                                </td>
                                <td class="px-5 py-4 text-right font-black text-on-surface tabular-nums">{{ number_format($leader->points) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layouts::app>
