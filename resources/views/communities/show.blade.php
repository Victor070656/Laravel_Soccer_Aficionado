<x-layouts::app :title="$community->name">
    <div class="max-w-7xl mx-auto space-y-8 p-2 sm:p-4">
        {{-- Banner --}}
        <div class="relative rounded-2xl overflow-hidden shadow-xl">
            @if($community->banner)
            <div class="h-52 sm:h-64 bg-cover bg-center" style="background-image: url('{{ asset('storage/' . $community->banner) }}')">
                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent"></div>
            </div>
            @else
            <div class="h-52 sm:h-64 bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 flex items-center justify-center relative">
                <div class="absolute inset-0 opacity-10" style="background-image: url('https://images.unsplash.com/photo-1459865264687-595d652de67e?w=600&q=30'); background-size: cover; background-position: center;"></div>
                <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/4"></div>
                <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/5 rounded-full translate-y-1/3 -translate-x-1/4"></div>
                <span class="text-7xl relative z-10 drop-shadow-lg">⚽</span>
            </div>
            @endif
        </div>

        {{-- Community Header Card --}}
        <div class="rounded-2xl border border-zinc-200/80 dark:border-zinc-700/80 bg-white dark:bg-zinc-800 p-6 shadow-sm -mt-12 relative z-10 mx-2 sm:mx-4">
            <div class="flex flex-col sm:flex-row items-start justify-between gap-4">
                <div class="flex-1">
                    <div class="flex items-center gap-3 flex-wrap">
                        <h1 class="text-2xl sm:text-3xl font-bold text-zinc-900 dark:text-white">{{ $community->name }}</h1>
                        @if($community->club)
                        <a href="{{ route('clubs.show', $community->club) }}" class="inline-flex items-center rounded-full bg-green-100 dark:bg-green-900/30 px-3 py-1 text-xs font-semibold text-green-700 dark:text-green-300 hover:bg-green-200 dark:hover:bg-green-900/50 transition">⚽ {{ $community->club->name }}</a>
                        @endif
                    </div>
                    <p class="mt-2 text-zinc-600 dark:text-zinc-400 text-sm leading-relaxed">{{ $community->description }}</p>
                    <div class="flex items-center gap-4 mt-3">
                        <span class="inline-flex items-center gap-1.5 text-sm text-zinc-500 dark:text-zinc-400">
                            <span class="w-5 h-5 rounded bg-zinc-100 dark:bg-zinc-700 flex items-center justify-center text-xs">👥</span>
                            {{ $community->members_count }} members
                        </span>
                        <span class="inline-flex items-center gap-1.5 text-sm text-zinc-500 dark:text-zinc-400">
                            <span class="w-5 h-5 rounded bg-zinc-100 dark:bg-zinc-700 flex items-center justify-center text-xs">📅</span>
                            Created {{ $community->created_at->diffForHumans() }}
                        </span>
                    </div>
                </div>
                <div class="flex-shrink-0">
                    @if($isMember)
                    <form action="{{ route('communities.leave', $community) }}" method="POST">
                        @csrf
                        <button class="rounded-xl border-2 border-zinc-200 dark:border-zinc-600 px-5 py-2.5 text-sm font-semibold text-zinc-600 dark:text-zinc-400 hover:bg-red-50 dark:hover:bg-red-900/20 hover:text-red-600 hover:border-red-300 dark:hover:border-red-700 transition-all">Leave Community</button>
                    </form>
                    @else
                    <form action="{{ route('communities.join', $community) }}" method="POST">
                        @csrf
                        <button class="rounded-xl bg-gradient-to-r from-green-600 to-emerald-500 px-6 py-2.5 text-sm font-semibold text-white hover:from-green-500 hover:to-emerald-400 transition-all shadow-md shadow-green-600/20 hover:scale-105">Join Community</button>
                    </form>
                    @endif
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Posts Feed --}}
            <div class="lg:col-span-2 space-y-5">
                {{-- Create Post --}}
                @if($isMember)
                <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data" class="rounded-2xl border border-zinc-200/80 dark:border-zinc-700/80 bg-white dark:bg-zinc-800 p-5 shadow-sm">
                    @csrf
                    <input type="hidden" name="community_id" value="{{ $community->id }}">
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0 w-10 h-10 rounded-full bg-gradient-to-br from-green-400 to-emerald-500 flex items-center justify-center text-white font-bold text-sm shadow-sm">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <div class="flex-1">
                            <textarea name="body" rows="3" placeholder="Write a post in {{ $community->name }}..." class="w-full rounded-xl border-zinc-200 dark:border-zinc-600 dark:bg-zinc-900/50 dark:text-white focus:border-green-500 focus:ring-green-500/20 text-sm resize-none" required></textarea>
                            <div class="flex items-center justify-between mt-3">
                                <label class="inline-flex items-center gap-2 text-xs text-zinc-400 hover:text-green-600 cursor-pointer transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    Add Photos
                                    <input type="file" name="media[]" multiple accept="image/*" class="hidden">
                                </label>
                                <button type="submit" class="rounded-xl bg-gradient-to-r from-green-600 to-emerald-500 px-5 py-2 text-sm font-semibold text-white hover:from-green-500 hover:to-emerald-400 transition-all shadow-md shadow-green-600/20 hover:scale-105">Post</button>
                            </div>
                        </div>
                    </div>
                </form>
                @endif

                {{-- Post Feed --}}
                @forelse($posts as $post)
                <div class="rounded-2xl border border-zinc-200/80 dark:border-zinc-700/80 bg-white dark:bg-zinc-800 shadow-sm hover:shadow-md transition-shadow overflow-hidden">
                    <div class="p-5">
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0 w-10 h-10 rounded-full bg-gradient-to-br from-green-400 to-emerald-500 flex items-center justify-center text-white font-bold text-sm shadow-sm">
                                {{ strtoupper(substr($post->user->name, 0, 1)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('profiles.show', $post->user) }}" class="font-semibold text-zinc-900 dark:text-white hover:text-green-600 text-sm transition">{{ $post->user->name }}</a>
                                    <span class="text-xs text-zinc-400 dark:text-zinc-500">{{ $post->created_at->diffForHumans() }}</span>
                                </div>
                                <a href="{{ route('posts.show', $post) }}" class="block mt-2">
                                    <p class="text-zinc-700 dark:text-zinc-300 text-sm leading-relaxed">{{ $post->body }}</p>
                                </a>
                            </div>
                        </div>

                        @if($post->media)
                        <div class="mt-4 grid gap-2 {{ count($post->media) > 1 ? 'grid-cols-2' : '' }}">
                            @foreach($post->media as $media)
                            <img loading="lazy" decoding="async" src="{{ asset('storage/' . $media) }}" alt="" class="rounded-xl w-full object-cover max-h-64">
                            @endforeach
                        </div>
                        @endif
                    </div>

                    <div class="flex items-center gap-5 px-5 py-3 border-t border-zinc-100 dark:border-zinc-700/60 bg-zinc-50/50 dark:bg-zinc-800/80">
                        <form action="{{ route('posts.like', $post) }}" method="POST" class="inline">@csrf
                            <button class="flex items-center gap-1.5 text-xs font-medium {{ auth()->user()->hasLiked($post) ? 'text-green-600 font-bold' : 'text-zinc-400 hover:text-green-600' }} transition">
                                <span class="text-base">♥</span> {{ $post->likes_count }}
                            </button>
                        </form>
                        <a href="{{ route('posts.show', $post) }}" class="flex items-center gap-1.5 text-xs font-medium text-zinc-400 hover:text-green-600 transition">
                            <span class="text-base">💬</span> {{ $post->comments_count }}
                        </a>
                    </div>
                </div>
                @empty
                <div class="rounded-2xl border-2 border-dashed border-zinc-200 dark:border-zinc-700 p-12 text-center">
                    <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center">
                        <span class="text-3xl">📝</span>
                    </div>
                    <h3 class="text-lg font-bold text-zinc-600 dark:text-zinc-400 mb-1">No Posts Yet</h3>
                    @if($isMember)
                    <p class="text-sm text-zinc-400 dark:text-zinc-500">Be the first to share something with the community!</p>
                    @else
                    <p class="text-sm text-zinc-400 dark:text-zinc-500">Join this community to start posting!</p>
                    @endif
                </div>
                @endforelse

                <div>{{ $posts->links() }}</div>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                {{-- Rules --}}
                @if($community->rules)
                <div class="rounded-2xl border border-zinc-200/80 dark:border-zinc-700/80 bg-white dark:bg-zinc-800 shadow-sm overflow-hidden">
                    <div class="px-5 py-3.5 border-b border-zinc-100 dark:border-zinc-700/60 bg-gradient-to-r from-amber-50 to-yellow-50 dark:from-amber-900/10 dark:to-yellow-900/10">
                        <h3 class="font-bold text-zinc-900 dark:text-white flex items-center gap-2 text-sm">
                            <span class="w-7 h-7 rounded-lg bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center text-amber-600 text-xs">📋</span>
                            Community Rules
                        </h3>
                    </div>
                    <div class="p-5 text-sm text-zinc-600 dark:text-zinc-400 whitespace-pre-line leading-relaxed">{{ $community->rules }}</div>
                </div>
                @endif

                {{-- Moderators --}}
                <div class="rounded-2xl border border-zinc-200/80 dark:border-zinc-700/80 bg-white dark:bg-zinc-800 shadow-sm overflow-hidden">
                    <div class="px-5 py-3.5 border-b border-zinc-100 dark:border-zinc-700/60 bg-gradient-to-r from-violet-50 to-purple-50 dark:from-violet-900/10 dark:to-purple-900/10">
                        <h3 class="font-bold text-zinc-900 dark:text-white flex items-center gap-2 text-sm">
                            <span class="w-7 h-7 rounded-lg bg-violet-100 dark:bg-violet-900/30 flex items-center justify-center text-violet-600 text-xs">🛡️</span>
                            Moderators
                        </h3>
                    </div>
                    <div class="p-5 space-y-2.5">
                        @foreach($community->moderators as $mod)
                        <a href="{{ route('profiles.show', $mod) }}" class="flex items-center gap-2.5 hover:text-green-600 group transition">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-violet-400 to-purple-500 flex items-center justify-center text-white text-xs font-bold shadow-sm">{{ strtoupper(substr($mod->name, 0, 1)) }}</div>
                            <span class="text-sm text-zinc-700 dark:text-zinc-300 font-medium group-hover:text-green-600 transition">{{ $mod->name }}</span>
                        </a>
                        @endforeach
                    </div>
                </div>

                {{-- Members --}}
                <div class="rounded-2xl border border-zinc-200/80 dark:border-zinc-700/80 bg-white dark:bg-zinc-800 shadow-sm overflow-hidden">
                    <div class="px-5 py-3.5 border-b border-zinc-100 dark:border-zinc-700/60 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/10 dark:to-indigo-900/10">
                        <h3 class="font-bold text-zinc-900 dark:text-white flex items-center gap-2 text-sm">
                            <span class="w-7 h-7 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 text-xs">👥</span>
                            Members ({{ $community->members_count }})
                        </h3>
                    </div>
                    <div class="p-5">
                        <div class="flex flex-wrap gap-1.5">
                            @foreach($community->members->take(20) as $member)
                            <a href="{{ route('profiles.show', $member) }}" title="{{ $member->name }}" class="w-9 h-9 rounded-full bg-gradient-to-br from-zinc-200 to-zinc-300 dark:from-zinc-600 dark:to-zinc-700 flex items-center justify-center text-xs font-bold text-zinc-600 dark:text-zinc-300 hover:ring-2 hover:ring-green-500 hover:scale-110 transition-all shadow-sm">
                                {{ strtoupper(substr($member->name, 0, 1)) }}
                            </a>
                            @endforeach
                            @if($community->members->count() > 20)
                            <span class="w-9 h-9 rounded-full bg-zinc-100 dark:bg-zinc-700 flex items-center justify-center text-xs font-semibold text-zinc-500 dark:text-zinc-400">+{{ $community->members->count() - 20 }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts::app>
