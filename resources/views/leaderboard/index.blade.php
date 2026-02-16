<x-layouts::app :title="__('Leaderboard')">
    <div class="max-w-3xl mx-auto space-y-6">
        <div class="text-center">
            <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">🏆 Leaderboard</h1>
            <p class="text-zinc-500 mt-1">Top fans ranked by points</p>
        </div>

        {{-- Current User Rank --}}
        @if($currentRank)
        <div class="rounded-xl border border-green-200 dark:border-green-800 bg-green-50 dark:bg-green-900/20 p-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <span class="text-lg font-bold text-green-600">#{{ $currentRank }}</span>
                    <span class="font-medium text-zinc-900 dark:text-white">Your ranking</span>
                </div>
                <span class="text-lg font-bold text-green-600">{{ auth()->user()->points ?? 0 }} pts</span>
            </div>
        </div>
        @endif

        {{-- Leaderboard Table --}}
        <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 overflow-hidden">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-zinc-100 dark:border-zinc-700">
                        <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 uppercase tracking-wider">Rank</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 uppercase tracking-wider">User</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-zinc-500 uppercase tracking-wider">Points</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100 dark:divide-zinc-700">
                    @foreach($leaders as $index => $leader)
                    <tr class="{{ auth()->id() === $leader->id ? 'bg-green-50 dark:bg-green-900/10' : '' }} hover:bg-zinc-50 dark:hover:bg-zinc-700/50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($index === 0)
                            <span class="text-2xl">🥇</span>
                            @elseif($index === 1)
                            <span class="text-2xl">🥈</span>
                            @elseif($index === 2)
                            <span class="text-2xl">🥉</span>
                            @else
                            <span class="text-sm font-bold text-zinc-500">#{{ $index + 1 }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <a href="{{ route('profiles.show', $leader) }}" class="flex items-center gap-3 hover:text-green-600">
                                <div class="w-10 h-10 rounded-full {{ $index < 3 ? 'bg-amber-100 dark:bg-amber-900 text-amber-700 dark:text-amber-300' : 'bg-zinc-100 dark:bg-zinc-700 text-zinc-600 dark:text-zinc-300' }} flex items-center justify-center font-bold">
                                    {{ strtoupper(substr($leader->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="font-semibold text-zinc-900 dark:text-white">{{ $leader->name }}</div>
                                    @if($leader->username)
                                    <div class="text-xs text-zinc-400">{{ '@' . $leader->username }}</div>
                                    @endif
                                </div>
                            </a>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <span class="text-lg font-bold {{ $index < 3 ? 'text-green-600' : 'text-zinc-900 dark:text-white' }}">{{ number_format($leader->points) }}</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-layouts::app>
