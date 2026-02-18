<x-layouts::app :title="($match->home_team['name'] ?? 'Home') . ' vs ' . ($match->away_team['name'] ?? 'Away')">
    <div class="max-w-5xl mx-auto space-y-6">
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-green-600 via-emerald-600 to-teal-600 p-8 text-white">
            <div class="absolute top-0 right-0 w-48 h-48 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/4"></div>
            <div class="relative">
                <p class="text-green-100 text-sm mb-2">{{ $match->league['name'] ?? '' }} · {{ $match->league['round'] ?? '' }}</p>
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold">Match Details</h1>
                        <p class="text-green-100 mt-1">
                            @if($match->date){{ \Carbon\Carbon::parse($match->date)->format('M d, Y H:i') }} · @endif
                            {{ $match->venue ?? '' }}
                        </p>
                    </div>
                    <a href="{{ route('admin.matches.index') }}" class="rounded-xl bg-white/20 backdrop-blur-sm px-5 py-2.5 text-sm font-semibold hover:bg-white/30 transition border border-white/20">← Back</a>
                </div>
            </div>
        </div>

        {{-- Score Card --}}
        <div class="rounded-2xl border border-zinc-200/80 dark:border-zinc-700/80 bg-white dark:bg-zinc-800 p-8 shadow-sm">
            <div class="flex items-center justify-center gap-8 sm:gap-12">
                <div class="text-center">
                    @if($match->home_team['logo'] ?? null)
                    <img src="{{ $match->home_team['logo'] }}" class="w-16 h-16 sm:w-20 sm:h-20 mx-auto object-contain mb-3" alt="">
                    @else
                    <div class="w-16 h-16 mx-auto rounded-xl bg-green-100 dark:bg-green-900/30 flex items-center justify-center text-2xl mb-3">⚽</div>
                    @endif
                    <h3 class="font-bold text-zinc-900 dark:text-white text-sm sm:text-base">{{ $match->home_team['name'] ?? 'Home' }}</h3>
                </div>
                <div class="text-center">
                    <div class="text-4xl sm:text-5xl font-black text-zinc-900 dark:text-white tabular-nums">
                        {{ $match->score_display ?? 'vs' }}
                    </div>
                    @php
                        $statusColors = [
                            'scheduled' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/50 dark:text-blue-300',
                            'live' => 'bg-red-100 text-red-700 dark:bg-red-900/50 dark:text-red-300',
                            'half_time' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/50 dark:text-amber-300',
                            'finished' => 'bg-green-100 text-green-700 dark:bg-green-900/50 dark:text-green-300',
                            'postponed' => 'bg-orange-100 text-orange-700 dark:bg-orange-900/50 dark:text-orange-300',
                        ];
                    @endphp
                    <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold mt-3 {{ $statusColors[$match->status] ?? 'bg-zinc-100 text-zinc-600' }}">
                        {{ $match->status_long ?? ucfirst(str_replace('_', ' ', $match->status)) }}
                    </span>
                </div>
                <div class="text-center">
                    @if($match->away_team['logo'] ?? null)
                    <img src="{{ $match->away_team['logo'] }}" class="w-16 h-16 sm:w-20 sm:h-20 mx-auto object-contain mb-3" alt="">
                    @else
                    <div class="w-16 h-16 mx-auto rounded-xl bg-green-100 dark:bg-green-900/30 flex items-center justify-center text-2xl mb-3">⚽</div>
                    @endif
                    <h3 class="font-bold text-zinc-900 dark:text-white text-sm sm:text-base">{{ $match->away_team['name'] ?? 'Away' }}</h3>
                </div>
            </div>
        </div>

        {{-- Match Info --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="rounded-2xl border border-zinc-200/80 dark:border-zinc-700/80 bg-white dark:bg-zinc-800 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-zinc-100 dark:border-zinc-700/60 bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/10 dark:to-emerald-900/10">
                    <h3 class="font-bold text-zinc-900 dark:text-white text-sm">📋 Match Information</h3>
                </div>
                <div class="p-5 space-y-3">
                    @foreach([
                        'League' => $match->league['name'] ?? '—',
                        'Round' => $match->league['round'] ?? '—',
                        'Venue' => $match->venue ?? '—',
                        'Date' => $match->date ? \Carbon\Carbon::parse($match->date)->format('F j, Y g:i A') : '—',
                        'Status' => ucfirst(str_replace('_', ' ', $match->status)),
                        'API ID' => $match->id,
                    ] as $label => $value)
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-zinc-500">{{ $label }}</span>
                        <span class="font-medium text-zinc-900 dark:text-white">{{ $value }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="rounded-2xl border border-zinc-200/80 dark:border-zinc-700/80 bg-white dark:bg-zinc-800 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-zinc-100 dark:border-zinc-700/60 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/10 dark:to-indigo-900/10">
                    <h3 class="font-bold text-zinc-900 dark:text-white text-sm">🔗 Quick Actions</h3>
                </div>
                <div class="p-5 space-y-3">
                    <a href="{{ route('matches.show', $match->id) }}" class="flex items-center justify-between p-3 rounded-xl bg-zinc-50 dark:bg-zinc-700/30 hover:bg-zinc-100 dark:hover:bg-zinc-700/50 transition">
                        <span class="text-sm font-medium text-zinc-700 dark:text-zinc-300">View Public Page</span>
                        <span class="text-zinc-400">→</span>
                    </a>
                    @if($match->home_team['id'] ?? null)
                    <a href="{{ route('clubs.show', $match->home_team['id']) }}" class="flex items-center justify-between p-3 rounded-xl bg-zinc-50 dark:bg-zinc-700/30 hover:bg-zinc-100 dark:hover:bg-zinc-700/50 transition">
                        <span class="text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ $match->home_team['name'] }} Profile</span>
                        <span class="text-zinc-400">→</span>
                    </a>
                    @endif
                    @if($match->away_team['id'] ?? null)
                    <a href="{{ route('clubs.show', $match->away_team['id']) }}" class="flex items-center justify-between p-3 rounded-xl bg-zinc-50 dark:bg-zinc-700/30 hover:bg-zinc-100 dark:hover:bg-zinc-700/50 transition">
                        <span class="text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ $match->away_team['name'] }} Profile</span>
                        <span class="text-zinc-400">→</span>
                    </a>
                    @endif
                    @if($match->video ?? null)
                    <a href="{{ $match->video }}" target="_blank" class="flex items-center justify-between p-3 rounded-xl bg-zinc-50 dark:bg-zinc-700/30 hover:bg-zinc-100 dark:hover:bg-zinc-700/50 transition">
                        <span class="text-sm font-medium text-zinc-700 dark:text-zinc-300">🎬 Match Highlights</span>
                        <span class="text-zinc-400">→</span>
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-layouts::app>
