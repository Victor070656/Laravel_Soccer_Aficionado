<x-layouts::app :title="$club->name . ' - Admin'">
    <div class="max-w-5xl mx-auto space-y-6">
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-blue-600 via-indigo-600 to-violet-600 p-8 text-white">
            <div class="absolute top-0 right-0 w-48 h-48 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/4"></div>
            <div class="relative flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    @if($club->logo)
                    <img src="{{ $club->logo }}" class="w-16 h-16 rounded-xl object-contain bg-white/10 p-2" alt="">
                    @else
                    <div class="w-16 h-16 rounded-xl bg-white/10 flex items-center justify-center text-3xl">🛡️</div>
                    @endif
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold">{{ $club->name }}</h1>
                        <p class="text-blue-100">{{ $club->country ?? '' }}{{ $club->venue['city'] ?? '' ? ' · ' . $club->venue['city'] : '' }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('clubs.show', $club->id) }}" class="rounded-xl bg-white/20 backdrop-blur-sm px-5 py-2.5 text-sm font-semibold hover:bg-white/30 transition border border-white/20">View Public</a>
                    @if(!$localClub)
                    <form action="{{ route('admin.clubs.syncClub', $club->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="rounded-xl bg-green-500/20 backdrop-blur-sm px-5 py-2.5 text-sm font-semibold hover:bg-green-500/30 transition border border-green-400/30">Sync to DB</button>
                    </form>
                    @endif
                    <a href="{{ route('admin.clubs.index') }}" class="rounded-xl bg-white/10 backdrop-blur-sm px-5 py-2.5 text-sm font-semibold hover:bg-white/20 transition border border-white/20">← Back</a>
                </div>
            </div>
        </div>

        @if(session('success'))
        <div class="rounded-xl bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 px-4 py-3 text-sm text-green-700 dark:text-green-300">
            {{ session('success') }}
        </div>
        @endif

        {{-- Sync Status --}}
        <div class="rounded-2xl border {{ $localClub ? 'border-green-200 dark:border-green-800 bg-green-50 dark:bg-green-900/10' : 'border-amber-200 dark:border-amber-800 bg-amber-50 dark:bg-amber-900/10' }} p-4">
            <div class="flex items-center gap-3">
                <span class="text-lg">{{ $localClub ? '✅' : '⚠️' }}</span>
                <div>
                    <p class="text-sm font-medium {{ $localClub ? 'text-green-700 dark:text-green-300' : 'text-amber-700 dark:text-amber-300' }}">
                        {{ $localClub ? 'Synced to local database (ID: ' . $localClub->id . ')' : 'Not yet synced to local database' }}
                    </p>
                    <p class="text-xs {{ $localClub ? 'text-green-600 dark:text-green-400' : 'text-amber-600 dark:text-amber-400' }}">
                        {{ $localClub ? 'Users can follow this club and join its communities' : 'Sync this club to allow users to follow it and create communities' }}
                    </p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Club Details --}}
            <div class="rounded-2xl border border-zinc-200/80 dark:border-zinc-700/80 bg-white dark:bg-zinc-800 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-zinc-100 dark:border-zinc-700/60 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/10 dark:to-indigo-900/10">
                    <h3 class="font-bold text-zinc-900 dark:text-white text-sm">📋 Club Details</h3>
                </div>
                <div class="p-5 space-y-3">
                    @foreach([
                        'Country' => $club->country,
                        'City' => $club->venue['city'] ?? null,
                        'Stadium' => $club->venue['name'] ?? null,
                        'Capacity' => $club->venue['capacity'] ?? null ? number_format($club->venue['capacity']) : null,
                        'Founded' => $club->founded,
                        'API Team ID' => $club->id,
                    ] as $label => $value)
                    @if($value)
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-zinc-500">{{ $label }}</span>
                        <span class="font-medium text-zinc-900 dark:text-white">{{ $value }}</span>
                    </div>
                    @endif
                    @endforeach
                </div>
            </div>

            {{-- Local DB Info --}}
            @if($localClub)
            <div class="rounded-2xl border border-zinc-200/80 dark:border-zinc-700/80 bg-white dark:bg-zinc-800 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-zinc-100 dark:border-zinc-700/60 bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/10 dark:to-emerald-900/10">
                    <h3 class="font-bold text-zinc-900 dark:text-white text-sm">💾 Local Database Record</h3>
                </div>
                <div class="p-5 space-y-3">
                    @foreach([
                        'Local ID' => $localClub->id,
                        'Slug' => $localClub->slug,
                        'Fans' => $localClub->fans()->count(),
                        'Communities' => $localClub->communities()->count(),
                        'Status' => $localClub->is_active ? 'Active' : 'Inactive',
                        'Created' => $localClub->created_at->format('M d, Y'),
                    ] as $label => $value)
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-zinc-500">{{ $label }}</span>
                        <span class="font-medium text-zinc-900 dark:text-white">{{ $value }}</span>
                    </div>
                    @endforeach

                    <div class="pt-3 border-t border-zinc-100 dark:border-zinc-700/50 flex gap-2">
                        <form action="{{ route('admin.clubs.toggleActive', $localClub) }}" method="POST">
                            @csrf
                            <button class="rounded-lg px-3 py-1.5 text-xs font-semibold {{ $localClub->is_active ? 'bg-amber-100 text-amber-700 hover:bg-amber-200' : 'bg-green-100 text-green-700 hover:bg-green-200' }} transition">
                                {{ $localClub->is_active ? 'Deactivate' : 'Activate' }}
                            </button>
                        </form>
                        <form action="{{ route('admin.clubs.destroy', $localClub) }}" method="POST" onsubmit="return confirm('Remove local record? This won\'t affect the API data.')">
                            @csrf @method('DELETE')
                            <button class="rounded-lg px-3 py-1.5 text-xs font-semibold bg-red-100 text-red-700 hover:bg-red-200 transition">Remove Local</button>
                        </form>
                    </div>
                </div>
            </div>
            @endif
        </div>

        {{-- Squad --}}
        <div class="rounded-2xl border border-zinc-200/80 dark:border-zinc-700/80 bg-white dark:bg-zinc-800 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-zinc-100 dark:border-zinc-700/60 bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/10 dark:to-emerald-900/10">
                <h3 class="font-bold text-zinc-900 dark:text-white text-sm">👤 Squad ({{ $squad->count() }} players from API)</h3>
            </div>
            <div class="p-5">
                @if($squad->count())
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                    @foreach($squad as $player)
                    <div class="flex items-center gap-3 p-3 rounded-xl bg-zinc-50 dark:bg-zinc-700/30">
                        @if($player->photo)
                        <img src="{{ $player->photo }}" class="w-10 h-10 rounded-full object-cover" alt="">
                        @else
                        <div class="w-10 h-10 rounded-full bg-zinc-200 dark:bg-zinc-600 flex items-center justify-center text-xs">👤</div>
                        @endif
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-medium text-zinc-900 dark:text-white truncate">{{ $player->name }}</p>
                            <p class="text-xs text-zinc-400">{{ $player->position ?? 'Unknown' }}{{ $player->number ? ' · #' . $player->number : '' }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <p class="text-sm text-zinc-400 text-center py-4">No squad data available from the API.</p>
                @endif
            </div>
        </div>
    </div>
</x-layouts::app>
