<x-layouts::app :title="__('Communities')">
    <div class="max-w-7xl mx-auto space-y-8 p-2 sm:p-4">
        {{-- Header --}}
        <div class="relative rounded-2xl overflow-hidden bg-gradient-to-r from-primary via-primary/80 to-primary/60 p-6 sm:p-8 text-on-primary shadow-xl">
            <div class="absolute inset-0 opacity-10" style="background-image: url('https://images.unsplash.com/photo-1459865264687-595d652de67e?w=600&q=30'); background-size: cover; background-position: center;"></div>
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/4"></div>
            <div class="relative z-10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold mb-2 flex items-center gap-3">
                        <span class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur flex items-center justify-center text-xl">👥</span>
                        Communities
                    </h1>
                    <p class="text-on-primary/70 text-sm sm:text-base">Find your tribe. Join fan communities and connect with fellow supporters.</p>
                </div>
                <button onclick="document.getElementById('create-form').classList.toggle('hidden')" class="inline-flex items-center gap-2 rounded-xl bg-white/20 backdrop-blur-sm hover:bg-white/30 px-5 py-2.5 text-sm font-semibold text-on-primary transition-all border border-white/20 hover:scale-105">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Create Community
                </button>
            </div>
        </div>

        {{-- Create Community Form --}}
        <div id="create-form" class="hidden rounded-2xl border border-outline-variant/20 dark:border-outline-variant/30 bg-surface-container dark:bg-surface-container p-6 sm:p-8 shadow-lg glass-edge">
            <h2 class="font-bold text-lg text-on-surface mb-6 flex items-center gap-2">
                <span class="w-8 h-8 rounded-lg bg-primary/20 flex items-center justify-center text-primary text-sm">✨</span>
                Create a New Community
            </h2>
            <form action="{{ route('communities.store') }}" method="POST" enctype="multipart/form-data" class="grid gap-5 sm:grid-cols-2">
                @csrf
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-on-surface mb-1.5">Name</label>
                    <input type="text" name="name" required class="w-full rounded-xl p-4 border-outline-variant/20 dark:border-outline-variant/30 dark:bg-surface-container-high dark:text-on-surface focus:border-primary focus:ring-primary/20 text-sm">
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-on-surface mb-1.5">Description</label>
                    <textarea name="description" rows="3" required class="w-full rounded-xl p-4 border-outline-variant/20 dark:border-outline-variant/30 dark:bg-surface-container-high dark:text-on-surface focus:border-primary focus:ring-primary/20 text-sm"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-on-surface mb-1.5">Rules</label>
                    <textarea name="rules" rows="3" class="w-full rounded-xl p-4 border-outline-variant/20 dark:border-outline-variant/30 dark:bg-surface-container-high dark:text-on-surface focus:border-primary focus:ring-primary/20 text-sm" placeholder="Community guidelines..."></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-on-surface mb-1.5">Link to a Club (optional)</label>
                    <select name="club_id" class="w-full rounded-xl p-4 border-outline-variant/20 dark:border-outline-variant/30 dark:bg-surface-container-high dark:text-on-surface focus:border-primary focus:ring-primary/20 text-sm">
                        <option value="">No club</option>
                        @foreach(\App\Models\Club::orderBy('name')->get() as $club)
                        <option value="{{ $club->id }}">{{ $club->name }}</option>
                        @endforeach
                    </select>
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-on-surface mb-1.5">Banner Image</label>
                        <input type="file" name="banner" accept="image/*" class="text-sm text-on-surface-variant file:mr-3 file:rounded-lg file:border-0 file:bg-primary/10 dark:file:bg-primary/20 file:px-4 file:py-2 file:text-sm file:font-medium file:text-on-primary hover:file:bg-primary/20">
                    </div>
                </div>
                <div class="sm:col-span-2 flex justify-end pt-2">
                    <button type="submit" class="rounded-xl bg-gradient-to-r from-primary to-primary/80 px-8 py-2.5 text-sm font-semibold text-on-primary hover:from-primary/90 hover:to-primary/70 transition-all shadow-md shadow-primary/20 hover:scale-105">Create Community</button>
                </div>
            </form>
        </div>

        {{-- Communities Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($communities as $community)
            <a href="{{ route('communities.show', $community) }}" class="group rounded-2xl border border-outline-variant/20 dark:border-outline-variant/30 bg-surface-container dark:bg-surface-container overflow-hidden hover:border-primary/40 dark:hover:border-primary/50 transition-all duration-500 hover-lift shadow-sm glass-edge">
                @if($community->banner)
                <div class="h-36 bg-cover bg-center relative overflow-hidden" style="background-image: url('{{ asset('storage/' . $community->banner) }}')">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent"></div>
                </div>
                @else
                <div class="h-36 bg-gradient-to-br from-primary via-primary/80 to-primary/60 flex items-center justify-center relative overflow-hidden">
                    <div class="absolute inset-0 opacity-20" style="background-image: radial-gradient(circle, white 1px, transparent 1px); background-size: 20px 20px;"></div>
                    <span class="text-5xl relative z-10 group-hover:scale-110 transition-transform duration-300">⚽</span>
                </div>
                @endif
                <div class="p-5">
                    <div class="flex items-center gap-2 mb-2">
                        <h3 class="font-bold text-on-surface group-hover:text-primary dark:group-hover:text-primary transition-colors text-lg">{{ $community->name }}</h3>
                        @if($community->club)
                        <span class="inline-flex items-center rounded-full bg-primary/10 dark:bg-primary/20 px-2.5 py-0.5 text-xs font-medium text-on-primary border border-primary/20 dark:border-primary/30">{{ $community->club->name }}</span>
                        @endif
                    </div>
                    <p class="text-sm text-on-surface-variant line-clamp-2 leading-relaxed">{{ $community->description }}</p>
                    <div class="flex items-center justify-between mt-4 pt-4 border-t border-outline-variant/20 dark:border-outline-variant/30">
                        <span class="inline-flex items-center gap-1.5 text-xs text-on-surface-variant">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            {{ $community->members_count }} members
                        </span>
                        <span class="inline-flex items-center gap-1.5 text-xs text-on-surface-variant">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                            {{ $community->posts_count ?? $community->posts()->count() }} posts
                        </span>
                    </div>
                </div>
            </a>
            @empty
            <div class="col-span-full rounded-2xl border-2 border-dashed border-outline-variant/30 dark:border-outline-variant/40 p-16 text-center">
                <div class="text-5xl mb-4">👥</div>
                <h3 class="font-bold text-lg text-on-surface mb-2">No communities yet</h3>
                <p class="text-sm text-on-surface-variant mb-4">Be the first to create one!</p>
            </div>
            @endforelse
        </div>

        <div class="mt-6">{{ $communities->links() }}</div>
    </div>
</x-layouts::app>
