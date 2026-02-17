<x-layouts::app :title="__('Leaderboard')">
    <div class="max-w-4xl mx-auto space-y-8 p-2 sm:p-4">
        {{-- Header --}}
        <div class="relative rounded-2xl overflow-hidden bg-gradient-to-r from-amber-600 via-yellow-500 to-orange-500 p-6 sm:p-8 text-white shadow-xl">
            <div class="absolute inset-0 opacity-10" style="background-image: url('https://images.unsplash.com/photo-1567427017947-545c5f8d16ad?w=600&q=30'); background-size: cover; background-position: center;"></div>
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/4"></div>
            <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/5 rounded-full translate-y-1/3 -translate-x-1/4"></div>
            <div class="relative z-10 text-center">
                <h1 class="text-2xl sm:text-3xl font-bold mb-2 flex items-center justify-center gap-3">
                    <span class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur flex items-center justify-center text-xl">🏆</span>
                    Leaderboard
                </h1>
                <p class="text-amber-100 text-sm sm:text-base">Top fans ranked by points. Earn points by engaging with the community.</p>
            </div>
        </div>

        {{-- Top 3 Podium --}}
        @if($leaders->count() >= 3)
        <div class="grid grid-cols-3 gap-4 max-w-2xl mx-auto">
            {{-- 2nd Place --}}
            <div class="flex flex-col items-center pt-8">
                <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-full bg-gradient-to-br from-zinc-300 to-zinc-400 flex items-center justify-center text-white font-bold text-xl sm:text-2xl shadow-lg ring-4 ring-zinc-200 dark:ring-zinc-600">
                    {{ strtoupper(substr($leaders[1]->name, 0, 1)) }}
                </div>
                <span class="text-3xl mt-2">🥈</span>
                <a href="{{ route('profiles.show', $leaders[1]) }}" class="font-bold text-zinc-900 dark:text-white text-sm mt-1 text-center hover:text-green-600 transition">{{ $leaders[1]->name }}</a>
                <span class="text-sm font-bold text-zinc-500 dark:text-zinc-400 tabular-nums">{{ number_format($leaders[1]->points) }} pts</span>
                <div class="w-full h-24 bg-gradient-to-t from-zinc-200 to-zinc-100 dark:from-zinc-700 dark:to-zinc-600 rounded-t-2xl mt-2"></div>
            </div>
            {{-- 1st Place --}}
            <div class="flex flex-col items-center">
                <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-full bg-gradient-to-br from-amber-400 to-yellow-500 flex items-center justify-center text-white font-bold text-2xl sm:text-3xl shadow-xl ring-4 ring-amber-300 dark:ring-amber-600">
                    {{ strtoupper(substr($leaders[0]->name, 0, 1)) }}
                </div>
                <span class="text-4xl mt-2">🥇</span>
                <a href="{{ route('profiles.show', $leaders[0]) }}" class="font-bold text-zinc-900 dark:text-white text-sm mt-1 text-center hover:text-green-600 transition">{{ $leaders[0]->name }}</a>
                <span class="text-sm font-bold text-amber-600 dark:text-amber-400 tabular-nums">{{ number_format($leaders[0]->points) }} pts</span>
                <div class="w-full h-32 bg-gradient-to-t from-amber-200 to-amber-100 dark:from-amber-800/50 dark:to-amber-700/50 rounded-t-2xl mt-2"></div>
            </div>
            {{-- 3rd Place --}}
            <div class="flex flex-col items-center pt-12">
                <div class="w-14 h-14 sm:w-18 sm:h-18 rounded-full bg-gradient-to-br from-orange-400 to-amber-600 flex items-center justify-center text-white font-bold text-lg sm:text-xl shadow-lg ring-4 ring-orange-200 dark:ring-orange-700">
                    {{ strtoupper(substr($leaders[2]->name, 0, 1)) }}
                </div>
                <span class="text-3xl mt-2">🥉</span>
                <a href="{{ route('profiles.show', $leaders[2]) }}" class="font-bold text-zinc-900 dark:text-white text-sm mt-1 text-center hover:text-green-600 transition">{{ $leaders[2]->name }}</a>
                <span class="text-sm font-bold text-zinc-500 dark:text-zinc-400 tabular-nums">{{ number_format($leaders[2]->points) }} pts</span>
                <div class="w-full h-16 bg-gradient-to-t from-orange-200 to-orange-100 dark:from-orange-800/40 dark:to-orange-700/40 rounded-t-2xl mt-2"></div>
            </div>
        </div>
        @endif

        {{-- Current User Rank --}}
        @if($currentRank)
        <div class="rounded-2xl border-2 border-green-200 dark:border-green-800 bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <span class="w-10 h-10 rounded-xl bg-green-100 dark:bg-green-900/30 flex items-center justify-center text-green-700 dark:text-green-400 font-bold text-sm">
                        #{{ $currentRank }}
                    </span>
                    <div>
                        <span class="font-bold text-zinc-900 dark:text-white">Your Ranking</span>
                        <div class="text-xs text-zinc-500 dark:text-zinc-400">Keep engaging to climb higher!</div>
                    </div>
                </div>
                <span class="text-xl font-black text-green-600 dark:text-green-400 tabular-nums">{{ number_format(auth()->user()->points ?? 0) }} pts</span>
            </div>
        </div>
        @endif

        {{-- Leaderboard Table --}}
        <div class="rounded-2xl border border-zinc-200/80 dark:border-zinc-700/80 bg-white dark:bg-zinc-800 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-zinc-100 dark:border-zinc-700/60 bg-gradient-to-r from-amber-50 to-yellow-50 dark:from-amber-900/10 dark:to-yellow-900/10">
                <h3 class="font-bold text-zinc-900 dark:text-white flex items-center gap-2">
                    <span class="w-8 h-8 rounded-lg bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center text-amber-600 text-sm">📋</span>
                    Full Rankings
                </h3>
            </div>
            <table class="w-full">
                <thead>
                    <tr class="border-b border-zinc-100 dark:border-zinc-700/60 bg-zinc-50/50 dark:bg-zinc-900/30">
                        <th class="px-5 py-3 text-left text-[11px] font-semibold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider w-16">Rank</th>
                        <th class="px-5 py-3 text-left text-[11px] font-semibold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">User</th>
                        <th class="px-5 py-3 text-right text-[11px] font-semibold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Points</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100 dark:divide-zinc-700/50">
                    @foreach($leaders as $index => $leader)
                    <tr class="{{ auth()->id() === $leader->id ? 'bg-green-50/60 dark:bg-green-900/10' : '' }} hover:bg-zinc-50 dark:hover:bg-zinc-700/30 transition">
                        <td class="px-5 py-4 whitespace-nowrap">
                            @if($index === 0)
                            <span class="text-2xl">🥇</span>
                            @elseif($index === 1)
                            <span class="text-2xl">🥈</span>
                            @elseif($index === 2)
                            <span class="text-2xl">🥉</span>
                            @else
                            <span class="text-sm font-bold text-zinc-400 dark:text-zinc-500 tabular-nums">#{{ $index + 1 }}</span>
                            @endif
                        </td>
                        <td class="px-5 py-4 whitespace-nowrap">
                            <a href="{{ route('profiles.show', $leader) }}" class="flex items-center gap-3 hover:text-green-600 transition group">
                                <div class="w-10 h-10 rounded-full {{ $index < 3 ? 'bg-gradient-to-br from-amber-300 to-amber-500 text-white' : 'bg-zinc-100 dark:bg-zinc-700 text-zinc-600 dark:text-zinc-300' }} flex items-center justify-center font-bold shadow-sm group-hover:scale-105 transition-transform">
                                    {{ strtoupper(substr($leader->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="font-semibold text-zinc-900 dark:text-white group-hover:text-green-600 transition">{{ $leader->name }}</div>
                                    @if($leader->username)
                                    <div class="text-xs text-zinc-400 dark:text-zinc-500">{{ '@' . $leader->username }}</div>
                                    @endif
                                </div>
                            </a>
                        </td>
                        <td class="px-5 py-4 whitespace-nowrap text-right">
                            <span class="text-lg font-black {{ $index < 3 ? 'text-amber-600 dark:text-amber-400' : 'text-zinc-900 dark:text-white' }} tabular-nums">{{ number_format($leader->points) }}</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-layouts::app>
