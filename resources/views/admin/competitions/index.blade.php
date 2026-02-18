<x-layouts::app :title="__('Competition Management')">
    <div class="max-w-7xl mx-auto space-y-6">
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-amber-600 via-orange-600 to-red-600 p-8">
            <div class="absolute top-0 right-0 w-48 h-48 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/4"></div>
            <div class="relative">
                <h1 class="text-3xl font-bold text-white">🏆 Competition Management</h1>
                <p class="text-amber-100 mt-1">Configured leagues from the football API · Season {{ $seasonDisplay }}</p>
            </div>
        </div>

        {{-- Competitions Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($competitions as $comp)
            <a href="{{ route('admin.competitions.show', $comp->id) }}" class="rounded-2xl border border-zinc-200 dark:border-zinc-700/50 bg-white dark:bg-zinc-800/50 overflow-hidden hover:border-amber-300 dark:hover:border-amber-600/50 hover:shadow-lg transition-all group">
                @if($comp->banner)
                <div class="h-32 bg-cover bg-center" style="background-image: url('{{ $comp->banner }}')">
                    <div class="h-full bg-gradient-to-t from-black/60 to-transparent"></div>
                </div>
                @else
                <div class="h-32 bg-gradient-to-br from-amber-500 to-orange-600 flex items-center justify-center">
                    <span class="text-5xl">🏆</span>
                </div>
                @endif
                <div class="p-5">
                    <div class="flex items-center gap-3 mb-3">
                        @if($comp->logo)
                        <img src="{{ $comp->logo }}" class="w-10 h-10 object-contain" alt="">
                        @endif
                        <div>
                            <h3 class="font-bold text-zinc-900 dark:text-white group-hover:text-amber-600 dark:group-hover:text-amber-400 transition">{{ $comp->name }}</h3>
                            <p class="text-xs text-zinc-400">{{ $comp->country ?? '' }} · {{ $comp->season ?? '' }}</p>
                        </div>
                    </div>
                    @if($comp->description)
                    <p class="text-sm text-zinc-500 dark:text-zinc-400 line-clamp-2">{{ Str::limit($comp->description, 100) }}</p>
                    @endif
                </div>
            </a>
            @empty
            <div class="col-span-full rounded-2xl border border-zinc-200 dark:border-zinc-700/50 bg-white dark:bg-zinc-800/50 p-12 text-center">
                <div class="text-4xl mb-2">🏆</div>
                <p class="text-zinc-400">No competitions configured. Check your services.football_api config.</p>
            </div>
            @endforelse
        </div>
    </div>
</x-layouts::app>
