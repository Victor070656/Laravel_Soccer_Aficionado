<x-layouts::app :title="__('Create Poll')">
    <div class="max-w-3xl mx-auto space-y-6 p-2 sm:p-4">
        <div>
            <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Create Poll</h1>
            <p class="text-sm text-zinc-500 mt-1">Create a new poll for users to vote on.</p>
        </div>

        <form method="POST" action="{{ route('admin.polls.store') }}" class="space-y-6" x-data="{ optionCount: 2 }">
            @csrf

            <div class="rounded-2xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-6 space-y-5">
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Title *</label>
                    <input type="text" name="title" value="{{ old('title') }}" required class="w-full rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 px-4 py-2 text-sm focus:ring-2 focus:ring-green-500" />
                    @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Description</label>
                    <textarea name="description" rows="2" class="w-full rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 px-4 py-2 text-sm focus:ring-2 focus:ring-green-500">{{ old('description') }}</textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Type *</label>
                        <select name="type" required class="w-full rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 px-4 py-2 text-sm focus:ring-2 focus:ring-green-500">
                            <option value="general">General</option>
                            <option value="motm">Man of the Match</option>
                            <option value="prediction">Prediction</option>
                            <option value="gotw">Goal of the Week</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Closes At</label>
                        <input type="datetime-local" name="closes_at" value="{{ old('closes_at') }}" class="w-full rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 px-4 py-2 text-sm focus:ring-2 focus:ring-green-500" />
                    </div>
                </div>

                @if($matches->count())
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Related Match (optional)</label>
                    <select name="match_id" class="w-full rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 px-4 py-2 text-sm focus:ring-2 focus:ring-green-500">
                        <option value="">— None —</option>
                        @foreach($matches as $match)
                        <option value="{{ $match->id }}">{{ $match->homeClub?->name ?? '?' }} vs {{ $match->awayClub?->name ?? '?' }} ({{ $match->kick_off?->format('M d') }})</option>
                        @endforeach
                    </select>
                </div>
                @endif

                {{-- Options --}}
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">Options (min 2, max 10) *</label>
                    <div class="space-y-2" id="options-container">
                        <template x-for="i in optionCount" :key="i">
                            <input type="text" :name="'options[' + (i-1) + '][label]'" placeholder="Option label..." required class="w-full rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 px-4 py-2 text-sm focus:ring-2 focus:ring-green-500" />
                        </template>
                    </div>
                    <button type="button" x-show="optionCount < 10" @click="optionCount++" class="mt-2 text-sm text-green-600 hover:text-green-700 font-medium">+ Add option</button>
                    @error('options') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" class="rounded-xl bg-green-600 px-6 py-2.5 text-sm font-semibold text-white hover:bg-green-700 transition shadow-sm">Create Poll</button>
                <a href="{{ route('admin.polls.index') }}" class="rounded-xl border border-zinc-200 dark:border-zinc-700 px-6 py-2.5 text-sm font-medium text-zinc-700 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-700 transition">Cancel</a>
            </div>
        </form>
    </div>
</x-layouts::app>
