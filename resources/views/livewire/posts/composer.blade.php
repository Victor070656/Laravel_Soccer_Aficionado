<div class="{{ $wrapperClass }}" id="post-composer-{{ $mode }}">
    <div class="flex items-start gap-3">
        @if (auth()->user()?->avatar)
            <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}"
                class="flex-shrink-0 w-10 h-10 rounded-full object-cover shadow-md glow-primary">
        @else
            <div
                class="flex-shrink-0 w-10 h-10 rounded-full bg-gradient-to-br from-primary to-primary/60 flex items-center justify-center text-on-primary font-bold text-sm shadow-md glow-primary">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
        @endif

        <div class="flex-1 min-w-0">
            <div class="flex flex-wrap gap-2 mb-4">
                @foreach ($types as $postType => $config)
                    <button type="button" wire:click="$set('type', '{{ $postType }}')"
                        class="flex items-center gap-1.5 px-3 py-2 rounded-lg text-xs font-medium transition-all {{ $type === $postType ? 'bg-primary-container text-on-primary-container scale-105' : 'bg-surface-container-high text-on-surface-variant hover:bg-surface-container' }}"
                        title="{{ $config['label'] }}">
                        <span class="text-base">{{ $config['icon'] }}</span>
                        <span class="hidden sm:inline">{{ $config['label'] }}</span>
                    </button>
                @endforeach
            </div>

            <form wire:submit.prevent="submit" class="space-y-3" enctype="multipart/form-data">
                <textarea wire:model.live.debounce.250ms="body" rows="3" placeholder="{{ $this->placeholder() }}"
                    class="{{ $descriptionClass }}"></textarea>

                <div class="{{ $controlsClass }}">
                    <div class="flex items-center gap-3 flex-wrap">
                        <span class="text-label-sm text-on-surface-variant">
                            {{ strlen($body) }}/{{ $maxBodyLength }}
                        </span>

                        @if ($type === 'match_reaction' || $type === 'goal_reaction')
                            <span class="badge-live !py-0.5 !px-2 text-xs">LIVE MATCH</span>
                        @endif

                        <label
                            class="inline-flex items-center gap-2 text-xs text-on-surface-variant hover:text-primary cursor-pointer transition-all px-3 py-2 rounded-lg hover:bg-primary/10">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Add Media
                            <input type="file" wire:model="media" multiple accept="image/*,video/*" class="hidden">
                        </label>
                    </div>

                    <button type="submit"
                        class="{{ $mode === 'dashboard' ? 'focus-ring btn-primary rounded-xl text-sm font-bold hover:scale-105 glow-primary uppercase tracking-wide disabled:opacity-50' : 'rounded-xl bg-primary-container px-5 py-2.5 text-sm font-semibold text-on-primary-container hover:bg-primary-container/90 transition-all disabled:opacity-50' }}"
                        @disabled(trim($body) === '')>
                        {{ $this->submitButtonLabel() }}
                    </button>
                </div>

                @if ($media)
                    <div class="{{ $previewClass }}">
                        @foreach ($media as $index => $uploadedMedia)
                            <div
                                class="relative overflow-hidden rounded-xl border border-outline-variant/20 bg-surface-container/40">
                                @if (str_starts_with($uploadedMedia->getMimeType() ?? '', 'video/'))
                                    <video controls class="h-40 w-full object-cover">
                                        <source src="{{ $uploadedMedia->temporaryUrl() }}"
                                            type="{{ $uploadedMedia->getMimeType() }}">
                                    </video>
                                @else
                                    <img src="{{ $uploadedMedia->temporaryUrl() }}" alt="Selected media preview"
                                        class="h-40 w-full object-cover">
                                @endif

                                <button type="button" wire:click="removeMedia({{ $index }})"
                                    class="absolute right-2 top-2 rounded-full bg-black/70 px-2 py-1 text-[10px] font-semibold uppercase tracking-wider text-white">
                                    Remove
                                </button>
                            </div>
                        @endforeach
                    </div>
                @endif

                @error('body')
                    <p class="text-sm font-medium text-red-500">{{ $message }}</p>
                @enderror
                @error('media')
                    <p class="text-sm font-medium text-red-500">{{ $message }}</p>
                @enderror
                @error('media.*')
                    <p class="text-sm font-medium text-red-500">{{ $message }}</p>
                @enderror
            </form>
        </div>
    </div>
</div>
