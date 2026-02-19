<x-layouts::app :title="__('Ad Management')">
    <div class="max-w-7xl mx-auto space-y-6 p-2 sm:p-4">
        {{-- Header --}}
        <div class="relative rounded-2xl overflow-hidden bg-gradient-to-r from-rose-600 via-pink-600 to-fuchsia-600 p-6 sm:p-8 text-white shadow-xl">
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/4"></div>
            <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/5 rounded-full translate-y-1/3 -translate-x-1/4"></div>
            <div class="relative z-10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold mb-2 flex items-center gap-3">
                        <span class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur flex items-center justify-center text-xl">📢</span>
                        Ad Management
                    </h1>
                    <p class="text-rose-100 text-sm sm:text-base">Create, manage, and control advertisements across the site.</p>
                </div>
                <a href="{{ route('admin.ads.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-white/20 backdrop-blur-sm hover:bg-white/30 px-5 py-2.5 text-sm font-semibold text-white transition-all border border-white/20 hover:scale-105">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    New Ad
                </a>
            </div>
        </div>

        {{-- Filters --}}
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('admin.ads.index') }}" class="px-4 py-2 rounded-xl text-sm font-medium transition {{ !request('placement') && !request('status') ? 'bg-rose-100 dark:bg-rose-900/30 text-rose-700 dark:text-rose-300' : 'bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-400 hover:bg-zinc-200 dark:hover:bg-zinc-700' }}">
                All
            </a>
            @foreach(['sidebar' => '📱 Sidebar', 'feed' => '📰 Feed', 'banner' => '🖼️ Banner', 'welcome' => '🏠 Welcome'] as $key => $label)
            <a href="{{ route('admin.ads.index', ['placement' => $key]) }}" class="px-4 py-2 rounded-xl text-sm font-medium transition {{ request('placement') === $key ? 'bg-rose-100 dark:bg-rose-900/30 text-rose-700 dark:text-rose-300' : 'bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-400 hover:bg-zinc-200 dark:hover:bg-zinc-700' }}">
                {{ $label }}
            </a>
            @endforeach
            <div class="border-l border-zinc-200 dark:border-zinc-700 mx-1"></div>
            @foreach(['active' => '🟢 Active', 'inactive' => '⏸️ Inactive', 'expired' => '🔴 Expired'] as $key => $label)
            <a href="{{ route('admin.ads.index', ['status' => $key]) }}" class="px-4 py-2 rounded-xl text-sm font-medium transition {{ request('status') === $key ? 'bg-rose-100 dark:bg-rose-900/30 text-rose-700 dark:text-rose-300' : 'bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-400 hover:bg-zinc-200 dark:hover:bg-zinc-700' }}">
                {{ $label }}
            </a>
            @endforeach
        </div>

        {{-- Ads Grid --}}
        <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
            @forelse($ads as $ad)
            <div class="rounded-2xl border border-zinc-200/80 dark:border-zinc-700/80 bg-white dark:bg-zinc-800 shadow-sm overflow-hidden group">
                {{-- Image Preview --}}
                <div class="relative aspect-[16/9] bg-zinc-100 dark:bg-zinc-900">
                    <img src="{{ $ad->image_url }}" alt="{{ $ad->title }}" class="w-full h-full object-cover">
                    {{-- Status Badge --}}
                    <div class="absolute top-3 left-3">
                        @if($ad->isRunning())
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-green-500/90 backdrop-blur px-2.5 py-1 text-xs font-semibold text-white shadow">
                            <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span>
                            Live
                        </span>
                        @elseif(!$ad->is_active)
                        <span class="inline-flex items-center rounded-full bg-zinc-500/90 backdrop-blur px-2.5 py-1 text-xs font-semibold text-white shadow">
                            Disabled
                        </span>
                        @elseif($ad->ends_at?->isPast())
                        <span class="inline-flex items-center rounded-full bg-red-500/90 backdrop-blur px-2.5 py-1 text-xs font-semibold text-white shadow">
                            Expired
                        </span>
                        @elseif($ad->starts_at?->isFuture())
                        <span class="inline-flex items-center rounded-full bg-amber-500/90 backdrop-blur px-2.5 py-1 text-xs font-semibold text-white shadow">
                            Scheduled
                        </span>
                        @endif
                    </div>
                    {{-- Placement Badge --}}
                    <div class="absolute top-3 right-3">
                        <span class="inline-flex items-center rounded-full bg-black/50 backdrop-blur px-2.5 py-1 text-xs font-medium text-white">
                            {{ ucfirst($ad->placement) }}
                        </span>
                    </div>
                </div>

                {{-- Details --}}
                <div class="p-4 space-y-3">
                    <div>
                        <h3 class="font-bold text-zinc-900 dark:text-white text-sm">{{ $ad->title }}</h3>
                        @if($ad->link_url)
                        <p class="text-xs text-zinc-400 dark:text-zinc-500 truncate mt-0.5">🔗 {{ $ad->link_url }}</p>
                        @endif
                    </div>

                    {{-- Stats --}}
                    <div class="flex items-center gap-4 text-xs text-zinc-500 dark:text-zinc-400">
                        <span class="flex items-center gap-1">👁️ {{ number_format($ad->view_count) }} views</span>
                        <span class="flex items-center gap-1">👆 {{ number_format($ad->click_count) }} clicks</span>
                        @if($ad->view_count > 0)
                        <span class="font-medium text-green-600 dark:text-green-400">{{ round(($ad->click_count / $ad->view_count) * 100, 1) }}% CTR</span>
                        @endif
                    </div>

                    {{-- Dates --}}
                    <div class="text-xs text-zinc-400 dark:text-zinc-500 space-y-0.5">
                        @if($ad->starts_at)
                        <div>Start: {{ $ad->starts_at->format('M d, Y H:i') }}</div>
                        @endif
                        @if($ad->ends_at)
                        <div>End: {{ $ad->ends_at->format('M d, Y H:i') }}</div>
                        @endif
                    </div>

                    {{-- Actions --}}
                    <div class="flex items-center gap-2 pt-2 border-t border-zinc-100 dark:border-zinc-700/50">
                        <form action="{{ route('admin.ads.toggle', $ad) }}" method="POST" class="flex-1">
                            @csrf
                            <button type="submit" class="w-full text-center rounded-lg px-3 py-2 text-xs font-semibold transition {{ $ad->is_active ? 'bg-amber-50 dark:bg-amber-900/20 text-amber-700 dark:text-amber-400 hover:bg-amber-100 dark:hover:bg-amber-900/30' : 'bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-400 hover:bg-green-100 dark:hover:bg-green-900/30' }}">
                                {{ $ad->is_active ? '⏸️ Disable' : '▶️ Enable' }}
                            </button>
                        </form>
                        <a href="{{ route('admin.ads.edit', $ad) }}" class="flex-1 text-center rounded-lg px-3 py-2 text-xs font-semibold bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-400 hover:bg-blue-100 dark:hover:bg-blue-900/30 transition">
                            ✏️ Edit
                        </a>
                        <form action="{{ route('admin.ads.destroy', $ad) }}" method="POST" class="flex-shrink-0" onsubmit="return confirm('Delete this ad permanently?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="rounded-lg px-3 py-2 text-xs font-semibold bg-red-50 dark:bg-red-900/20 text-red-700 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-900/30 transition">
                                🗑️
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full">
                <div class="rounded-2xl border-2 border-dashed border-zinc-200 dark:border-zinc-700 p-12 text-center">
                    <div class="w-20 h-20 mx-auto mb-4 rounded-2xl bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center">
                        <span class="text-4xl">📢</span>
                    </div>
                    <h2 class="text-xl font-bold text-zinc-700 dark:text-zinc-300 mb-2">No Ads Yet</h2>
                    <p class="text-sm text-zinc-400 dark:text-zinc-500 max-w-md mx-auto mb-4">Create your first advertisement to start monetizing the platform.</p>
                    <a href="{{ route('admin.ads.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-rose-600 to-pink-500 px-6 py-2.5 text-sm text-white font-semibold hover:from-rose-500 hover:to-pink-400 transition-all shadow-md">
                        Create First Ad
                    </a>
                </div>
            </div>
            @endforelse
        </div>

        <div>{{ $ads->withQueryString()->links() }}</div>
    </div>
</x-layouts::app>
