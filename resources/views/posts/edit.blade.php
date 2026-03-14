<x-layouts::app :title="__('Edit Post')">
    <div class="max-w-3xl mx-auto space-y-6 p-2 sm:p-4">
        <div class="rounded-2xl border border-zinc-200/80 dark:border-zinc-700/80 bg-white dark:bg-zinc-800 shadow-sm overflow-hidden">
            <div class="p-6">
                <h2 class="text-xl font-bold text-zinc-900 dark:text-white mb-6">{{ __('Edit Post') }}</h2>

                <form action="{{ route('posts.update', $post) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="space-y-4">
                        <div>
                            <label for="body" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">{{ __('Content') }}</label>
                            <textarea id="body" name="body" rows="6" required maxlength="5000"
                                class="w-full rounded-xl border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-900 px-4 py-3 text-zinc-900 dark:text-zinc-100 focus:border-green-500 focus:ring-green-500 placeholder-zinc-400 resize-none"
                                placeholder="{{ __('What\'s on your mind?') }}">{{ old('body', $post->body) }}</textarea>
                            @error('body')
                                <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        @if ($post->media)
                            <div>
                                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">{{ __('Current Media') }}</label>
                                <div class="grid gap-2 {{ count($post->media) > 1 ? 'grid-cols-2' : '' }}">
                                    @foreach ($post->media as $media)
                                        <img src="{{ $media['url'] }}" alt="" class="rounded-xl w-full object-cover max-h-48">
                                    @endforeach
                                </div>
                                <p class="text-xs text-zinc-500 mt-1">{{ __('Media cannot be changed after posting.') }}</p>
                            </div>
                        @endif

                        <div class="flex items-center gap-3 pt-2">
                            <button type="submit"
                                class="inline-flex items-center px-6 py-2.5 rounded-xl bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-500 hover:to-emerald-500 text-white font-semibold text-sm shadow-lg shadow-green-500/25 transition-all">
                                {{ __('Update Post') }}
                            </button>
                            <a href="{{ route('posts.show', $post) }}"
                                class="inline-flex items-center px-4 py-2.5 rounded-xl border border-zinc-200 dark:border-zinc-700 text-sm font-medium text-zinc-700 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-700/50 transition-all">
                                {{ __('Cancel') }}
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts::app>
