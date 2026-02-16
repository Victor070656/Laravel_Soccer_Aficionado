<x-layouts::app :title="$community->name">
    <div class="max-w-6xl mx-auto space-y-6">
        {{-- Banner --}}
        <div class="rounded-xl overflow-hidden">
            @if($community->banner)
            <div class="h-48 bg-cover bg-center" style="background-image: url('{{ asset('storage/' . $community->banner) }}')"></div>
            @else
            <div class="h-48 bg-gradient-to-r from-green-500 to-emerald-600 flex items-center justify-center">
                <span class="text-6xl">⚽</span>
            </div>
            @endif
        </div>

        {{-- Community Header --}}
        <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-6">
            <div class="flex items-start justify-between">
                <div>
                    <div class="flex items-center gap-3">
                        <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">{{ $community->name }}</h1>
                        @if($community->club)
                        <a href="{{ route('clubs.show', $community->club) }}" class="inline-flex items-center rounded-full bg-green-100 dark:bg-green-900 px-3 py-1 text-xs font-medium text-green-700 dark:text-green-300 hover:bg-green-200">{{ $community->club->name }}</a>
                        @endif
                    </div>
                    <p class="mt-2 text-zinc-600 dark:text-zinc-400">{{ $community->description }}</p>
                    <div class="flex items-center gap-4 mt-3">
                        <span class="text-sm text-zinc-500">{{ $community->members_count }} members</span>
                        <span class="text-sm text-zinc-500">Created {{ $community->created_at->diffForHumans() }}</span>
                    </div>
                </div>
                <div>
                    @if($isMember)
                    <form action="{{ route('communities.leave', $community) }}" method="POST">
                        @csrf
                        <button class="rounded-lg border border-zinc-300 dark:border-zinc-600 px-4 py-2 text-sm text-zinc-600 dark:text-zinc-400 hover:bg-red-50 hover:text-red-600 hover:border-red-300">Leave</button>
                    </form>
                    @else
                    <form action="{{ route('communities.join', $community) }}" method="POST">
                        @csrf
                        <button class="rounded-lg bg-green-600 px-4 py-2 text-sm text-white hover:bg-green-700">Join Community</button>
                    </form>
                    @endif
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Posts --}}
            <div class="lg:col-span-2 space-y-4">
                @if($isMember)
                <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data" class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-4">
                    @csrf
                    <input type="hidden" name="community_id" value="{{ $community->id }}">
                    <textarea name="body" rows="3" placeholder="Write a post in {{ $community->name }}..." class="w-full rounded-lg border-zinc-300 dark:border-zinc-600 dark:bg-zinc-900 dark:text-white text-sm" required></textarea>
                    <div class="flex items-center justify-between mt-2">
                        <input type="file" name="media[]" multiple accept="image/*" class="text-xs text-zinc-500">
                        <button type="submit" class="rounded-lg bg-green-600 px-4 py-2 text-sm text-white hover:bg-green-700">Post</button>
                    </div>
                </form>
                @endif

                @forelse($posts as $post)
                <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-4">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 rounded-full bg-green-100 dark:bg-green-900 flex items-center justify-center text-green-700 dark:text-green-300 font-bold text-sm">
                            {{ strtoupper(substr($post->user->name, 0, 1)) }}
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('profiles.show', $post->user) }}" class="font-semibold text-zinc-900 dark:text-white hover:text-green-600 text-sm">{{ $post->user->name }}</a>
                                <span class="text-xs text-zinc-400">{{ $post->created_at->diffForHumans() }}</span>
                            </div>
                            <a href="{{ route('posts.show', $post) }}" class="block mt-2">
                                <p class="text-zinc-700 dark:text-zinc-300 text-sm">{{ $post->body }}</p>
                            </a>
                            @if($post->media)
                            <div class="mt-3 grid gap-2 {{ count($post->media) > 1 ? 'grid-cols-2' : '' }}">
                                @foreach($post->media as $media)
                                <img src="{{ asset('storage/' . $media) }}" alt="" class="rounded-lg w-full object-cover max-h-64">
                                @endforeach
                            </div>
                            @endif
                            <div class="flex items-center gap-4 mt-3 pt-3 border-t border-zinc-100 dark:border-zinc-700">
                                <form action="{{ route('posts.like', $post) }}" method="POST" class="inline">@csrf
                                    <button class="text-xs {{ auth()->user()->hasLiked($post) ? 'text-green-600 font-bold' : 'text-zinc-400 hover:text-green-600' }}">♥ {{ $post->likes_count }}</button>
                                </form>
                                <a href="{{ route('posts.show', $post) }}" class="text-xs text-zinc-400 hover:text-green-600">💬 {{ $post->comments_count }}</a>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-12 text-zinc-400">
                    <p>No posts yet in this community.</p>
                    @if($isMember)
                    <p class="text-sm mt-1">Be the first to share something!</p>
                    @else
                    <p class="text-sm mt-1">Join to start posting!</p>
                    @endif
                </div>
                @endforelse

                <div>{{ $posts->links() }}</div>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                {{-- Rules --}}
                @if($community->rules)
                <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-4">
                    <h3 class="font-bold text-zinc-900 dark:text-white mb-2">📋 Community Rules</h3>
                    <div class="text-sm text-zinc-600 dark:text-zinc-400 whitespace-pre-line">{{ $community->rules }}</div>
                </div>
                @endif

                {{-- Moderators --}}
                <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-4">
                    <h3 class="font-bold text-zinc-900 dark:text-white mb-3">🛡️ Moderators</h3>
                    <div class="space-y-2">
                        @foreach($community->moderators as $mod)
                        <a href="{{ route('profiles.show', $mod) }}" class="flex items-center gap-2 hover:text-green-600">
                            <div class="w-7 h-7 rounded-full bg-zinc-100 dark:bg-zinc-700 flex items-center justify-center text-xs font-bold">{{ strtoupper(substr($mod->name, 0, 1)) }}</div>
                            <span class="text-sm text-zinc-700 dark:text-zinc-300">{{ $mod->name }}</span>
                        </a>
                        @endforeach
                    </div>
                </div>

                {{-- Members --}}
                <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-4">
                    <h3 class="font-bold text-zinc-900 dark:text-white mb-3">👥 Members ({{ $community->members_count }})</h3>
                    <div class="flex flex-wrap gap-1">
                        @foreach($community->members->take(20) as $member)
                        <a href="{{ route('profiles.show', $member) }}" title="{{ $member->name }}" class="w-8 h-8 rounded-full bg-zinc-100 dark:bg-zinc-700 flex items-center justify-center text-xs font-bold text-zinc-600 dark:text-zinc-300 hover:ring-2 hover:ring-green-500">
                            {{ strtoupper(substr($member->name, 0, 1)) }}
                        </a>
                        @endforeach
                        @if($community->members->count() > 20)
                        <span class="w-8 h-8 rounded-full bg-zinc-200 dark:bg-zinc-600 flex items-center justify-center text-xs text-zinc-500">+{{ $community->members->count() - 20 }}</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts::app>
