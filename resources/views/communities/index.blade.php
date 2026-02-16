<x-layouts::app :title="__('Communities')">
    <div class="max-w-6xl mx-auto space-y-6">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Communities</h1>
            <a href="#create" class="rounded-lg bg-green-600 px-4 py-2 text-sm text-white hover:bg-green-700" onclick="document.getElementById('create-form').classList.toggle('hidden')">Create Community</a>
        </div>

        {{-- Create Community Form --}}
        <div id="create-form" class="hidden rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-6">
            <h2 class="font-bold text-zinc-900 dark:text-white mb-4">Create a new community</h2>
            <form action="{{ route('communities.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Name</label>
                    <input type="text" name="name" required class="w-full rounded-lg border-zinc-300 dark:border-zinc-600 dark:bg-zinc-900 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Description</label>
                    <textarea name="description" rows="3" required class="w-full rounded-lg border-zinc-300 dark:border-zinc-600 dark:bg-zinc-900 dark:text-white"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Rules</label>
                    <textarea name="rules" rows="3" class="w-full rounded-lg border-zinc-300 dark:border-zinc-600 dark:bg-zinc-900 dark:text-white" placeholder="Community guidelines..."></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Link to a Club (optional)</label>
                    <select name="club_id" class="w-full rounded-lg border-zinc-300 dark:border-zinc-600 dark:bg-zinc-900 dark:text-white">
                        <option value="">No club</option>
                        @foreach(\App\Models\Club::orderBy('name')->get() as $club)
                        <option value="{{ $club->id }}">{{ $club->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Banner Image</label>
                    <input type="file" name="banner" accept="image/*" class="text-sm text-zinc-500">
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="rounded-lg bg-green-600 px-6 py-2 text-sm text-white hover:bg-green-700">Create</button>
                </div>
            </form>
        </div>

        {{-- Communities Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($communities as $community)
            <a href="{{ route('communities.show', $community) }}" class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 overflow-hidden hover:border-green-400 transition group">
                @if($community->banner)
                <div class="h-32 bg-cover bg-center" style="background-image: url('{{ asset('storage/' . $community->banner) }}')"></div>
                @else
                <div class="h-32 bg-gradient-to-r from-green-500 to-emerald-600 flex items-center justify-center">
                    <span class="text-4xl">⚽</span>
                </div>
                @endif
                <div class="p-4">
                    <div class="flex items-center gap-2">
                        <h3 class="font-bold text-zinc-900 dark:text-white group-hover:text-green-600">{{ $community->name }}</h3>
                        @if($community->club)
                        <span class="inline-flex items-center rounded-full bg-green-100 dark:bg-green-900 px-2 py-0.5 text-xs text-green-700 dark:text-green-300">{{ $community->club->name }}</span>
                        @endif
                    </div>
                    <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400 line-clamp-2">{{ $community->description }}</p>
                    <div class="flex items-center justify-between mt-3 pt-3 border-t border-zinc-100 dark:border-zinc-700">
                        <span class="text-xs text-zinc-400">{{ $community->members_count }} members</span>
                        <span class="text-xs text-zinc-400">{{ $community->posts_count ?? $community->posts()->count() }} posts</span>
                    </div>
                </div>
            </a>
            @empty
            <div class="col-span-full text-center py-12 text-zinc-400">
                <p class="text-lg">No communities yet</p>
                <p class="text-sm mt-1">Be the first to create one!</p>
            </div>
            @endforelse
        </div>

        <div class="mt-6">{{ $communities->links() }}</div>
    </div>
</x-layouts::app>
