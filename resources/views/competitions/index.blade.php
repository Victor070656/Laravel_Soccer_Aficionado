<x-layouts::app :title="__('Competitions')">
    <div class="space-y-6">
        <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Competitions</h1>

        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @forelse($competitions as $competition)
            <a href="{{ route('competitions.show', $competition) }}" class="block rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-4 hover:shadow-md transition group">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 rounded-lg bg-green-100 dark:bg-green-900/30 flex items-center justify-center text-green-600 dark:text-green-400 font-bold text-sm">
                        {{ strtoupper(substr($competition->name, 0, 2)) }}
                    </div>
                    <div>
                        <div class="font-semibold text-zinc-900 dark:text-white group-hover:text-green-600">{{ $competition->name }}</div>
                        <div class="text-xs text-zinc-400">{{ ucfirst($competition->type) }} · {{ $competition->country ?? 'International' }}</div>
                    </div>
                </div>
                @if($competition->season)
                <div class="text-xs text-zinc-400">Season: {{ $competition->season }}</div>
                @endif
                <div class="text-xs text-zinc-400 mt-1">{{ $competition->clubs_count }} clubs</div>
            </a>
            @empty
            <div class="col-span-full text-center py-12 text-zinc-400">No competitions found.</div>
            @endforelse
        </div>

        {{ $competitions->links() }}
    </div>
</x-layouts::app>
