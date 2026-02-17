<x-layouts::app :title="__('Communities')">
    <div class="max-w-7xl mx-auto space-y-8 p-2 sm:p-4">
        {{-- Header --}}
        <div class="relative rounded-2xl overflow-hidden bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 p-6 sm:p-8 text-white shadow-xl">
            <div class="absolute inset-0 opacity-10" style="background-image: url('https://images.unsplash.com/photo-1459865264687-595d652de67e?w=1200&q=50'); background-size: cover; background-position: center;"></div>
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/4"></div>
            <div class="relative z-10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold mb-2 flex items-center gap-3">
                        <span class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur flex items-center justify-center text-xl">👥</span>
                        Communities
                    </h1>
                    <p class="text-blue-100 text-sm sm:text-base">Find your tribe. Join fan communities and connect with fellow supporters.</p>
                </div>
                <button onclick="document.getElementById('create-form').classList.toggle('hidden')" class="inline-flex items-center gap-2 rounded-xl bg-white/20 backdrop-blur-sm hover:bg-white/30 px-5 py-2.5 text-sm font-semibold text-white transition-all border border-white/20 hover:scale-105">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Create Community
                </button>
            </div>
        </div>

        {{-- Create Community Form --}}
        <div id="create-form" class="hidden rounded-2xl border border-zinc-200/80 dark:border-zinc-700/80 bg-white dark:bg-zinc-800 p-6 sm:p-8 shadow-lg">
            <h2 class="font-bold text-lg text-zinc-900 dark:text-white mb-6 flex items-center gap-2">
                <span class="w-8 h-8 rounded-lg bg-green-100 dark:bg-green-900/30 flex items-center justify-center text-green-600 text-sm">✨</span>
                Create a New Community
            </h2>
            <form action="{{ route('communities.store') }}" method="POST" enctype="multipart/form-data" class="grid gap-5 sm:grid-cols-2">
                @csrf
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">Name</label>
                    <input type="text" name="name" required class="w-full rounded-xl border-zinc-200 dark:border-zinc-600 dark:bg-zinc-900/50 dark:text-white focus:border-green-500 focus:ring-green-500/20 text-sm">
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">Description</label>
                    <textarea name="description" rows="3" required class="w-full rounded-xl border-zinc-200 dark:border-zinc-600 dark:bg-zinc-900/50 dark:text-white focus:border-green-500 focus:ring-green-500/20 text-sm"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">Rules</label>
                    <textarea name="rules" rows="3" class="w-full rounded-xl border-zinc-200 dark:border-zinc-600 dark:bg-zinc-900/50 dark:text-white focus:border-green-500 focus:ring-green-500/20 text-sm" placeholder="Community guidelines..."></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">Link to a Club (optional)</label>
                    <select name="club_id" class="w-full rounded-xl border-zinc-200 dark:border-zinc-600 dark:bg-zinc-900/50 dark:text-white focus:border-green-500 focus:ring-green-500/20 text-sm">
                        <option value="">No club</option>
                        @foreach(\App\Models\Club::orderBy('name')->get() as $club)
                        <option value="{{ $club->id }}">{{ $club->name }}</option>
                        @endforeach
                    </select>
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">Banner Image</label>
                        <input type="file" name="banner" accept="image/*" class="text-sm text-zinc-500 file:mr-3 file:rounded-lg file:border-0 file:bg-green-50 dark:file:bg-green-900/30 file:px-4 file:py-2 file:text-sm file:font-medium file:text-green-700 dark:file:text-green-400 hover:file:bg-green-100">
                    </div>
                </div>
                <div class="sm:col-span-2 flex justify-end pt-2">
                    <button type="submit" class="rounded-xl bg-gradient-to-r from-green-600 to-emerald-500 px-8 py-2.5 text-sm font-semibold text-white hover:from-green-500 hover:to-emerald-400 transition-all shadow-md shadow-green-600/20 hover:scale-105">Create Community</button>
                </div>
            </form>
        </div>

        {{-- Communities Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($communities as $community)
            <a href="{{ route('communities.show', $community) }}" class="group rounded-2xl border border-zinc-200/80 dark:border-zinc-700/80 bg-white dark:bg-zinc-800 overflow-hidden hover:border-green-300 dark:hover:border-green-700/60 transition-all duration-500 hover-lift shadow-sm">
                @if($community->banner)
                <div class="h-36 bg-cover bg-center relative overflow-hidden" style="background-image: url('{{ asset('storage/' . $community->banner) }}')">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent"></div>
                </div>
                @else
                <div class="h-36 bg-gradient-to-br from-green-500 via-emerald-500 to-teal-600 flex items-center justify-center relative overflow-hidden">
                    <div class="absolute inset-0 opacity-20" style="background-image: radial-gradient(circle, white 1px, transparent 1px); background-size: 20px 20px;"></div>
                    <span class="text-5xl relative z-10 group-hover:scale-110 transition-transform duration-300">⚽</span>
                </div>
                @endif
                <div class="p-5">
                    <div class="flex items-center gap-2 mb-2">
                        <h3 class="font-bold text-zinc-900 dark:text-white group-hover:text-green-600 dark:group-hover:text-green-400 transition-colors text-lg">{{ $community->name }}</h3>
                        @if($community->club)
                        <span class="inline-flex items-center rounded-full bg-green-100 dark:bg-green-900/40 px-2.5 py-0.5 text-xs font-medium text-green-700 dark:text-green-400 border border-green-200/50 dark:border-green-800/50">{{ $community->club->name }}</span>
                        @endif
                    </div>
                    <p class="text-sm text-zinc-500 dark:text-zinc-400 line-clamp-2 leading-relaxed">{{ $community->description }}</p>
                    <div class="flex items-center justify-between mt-4 pt-4 border-t border-zinc-100 dark:border-zinc-700/50">
                        <span class="inline-flex items-center gap-1.5 text-xs text-zinc-400">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            {{ $community->members_count }} members
                        </span>
                        <span class="inline-flex items-center gap-1.5 text-xs text-zinc-400">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                            {{ $community->posts_count ?? $community->posts()->count() }} posts
                        </span>
                    </div>
                </div>
            </a>
            @empty
            <div class="col-span-full rounded-2xl border-2 border-dashed border-zinc-200 dark:border-zinc-700 p-16 text-center">
                <div class="text-5xl mb-4">👥</div>
                <h3 class="font-bold text-lg text-zinc-900 dark:text-white mb-2">No communities yet</h3>
                <p class="text-sm text-zinc-500 mb-4">Be the first to create one!</p>
            </div>
            @endforelse
        </div>

        <div class="mt-6">{{ $communities->links() }}</div>
    </div>
</x-layouts::app>
