<x-layouts::app :title="__('Clubs')">
    <div class="space-y-6">
        <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Clubs</h1>

        <form method="GET" class="flex gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search clubs..." class="flex-1 rounded-lg border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white text-sm">
            <input type="text" name="country" value="{{ request('country') }}" placeholder="Country" class="rounded-lg border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white text-sm w-40">
            <button type="submit" class="rounded-lg bg-green-600 px-4 py-2 text-sm text-white hover:bg-green-700">Search</button>
        </form>

        <div class="grid gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
            @forelse($clubs as $club)
            <a href="{{ route('clubs.show', $club) }}" class="block rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-4 hover:shadow-md transition group">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-12 h-12 rounded-lg flex items-center justify-center text-lg font-bold" style="background-color: {{ $club->primary_color ?? '#16a34a' }}20; color: {{ $club->primary_color ?? '#16a34a' }}">
                        {{ strtoupper(substr($club->short_name ?? $club->name, 0, 3)) }}
                    </div>
                    <div>
                        <div class="font-semibold text-zinc-900 dark:text-white group-hover:text-green-600 transition">{{ $club->name }}</div>
                        <div class="text-xs text-zinc-400">{{ $club->country }}</div>
                    </div>
                </div>
                @if($club->stadium)
                <div class="text-xs text-zinc-400">📍 {{ $club->stadium }}</div>
                @endif
                <div class="text-xs text-zinc-400 mt-1">{{ $club->fans_count }} fans</div>
            </a>
            @empty
            <div class="col-span-full text-center py-12 text-zinc-400">No clubs found.</div>
            @endforelse
        </div>

        {{ $clubs->links() }}
    </div>
</x-layouts::app>
